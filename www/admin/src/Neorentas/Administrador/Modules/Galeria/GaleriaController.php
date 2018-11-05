<?php

namespace Neorentas\Administrador\Modules\Galeria;

use Neorentas\Administrador\Modules\Galeria\Models\GaleriaModel;
use Neorentas\Administrador\Modules\Galeria\Views\GaleriaView;
use Neorentas\Administrador\System\ProcesadorArchivo;
use Neorentas\Administrador\System\Variables;
use ZCode\Lighting\Controller\BaseController;
use ZCode\Lighting\Http\Request;

class GaleriaController extends BaseController
{
    public function run()
    {
        $idPerfil = $this->session->getString('idPerfil');
        $html     = '';

        if ($idPerfil != 1) {
            $this->response = $html;
            return;
        }

        $idSitio       = $this->request->getUrlVar(1);
        $accionGaleria = $this->request->getUrlVar(2);

        if ($accionGaleria) {
            $idAccion = $this->request->getUrlVar(3);

            $html = $this->cargarEdicion($idAccion, $idSitio);
            $this->response = $html;
            return;
        }

        $this->session->setInt('idSitio', intval($idSitio));

        /** @var GaleriaModel $model */
        $model = $this->createModel('GaleriaModel');
        $carpeta  = $model->cargarCarpeta($idSitio);
        $pagina   = $model->buscarPagina($idSitio);
        $galerias = $model->buscarGalerias($idSitio);

        /** @var GaleriaView $view */
        $view = $this->createView('GaleriaView');
        $html = $view->cargarGaleria($idSitio, $pagina, $carpeta, $galerias);

        $this->response = $html;
    }

    public function runAjax()
    {
        $accion = $this->request->getPostVar('accion', Request::STRING);

        if (strlen($accion) > 0) {
            switch ($accion) {
                case 'guardarPagina':
                    $this->guardarPagina();
                    break;
                case 'subirImagen':
                    $this->subirImagen();
                    break;
                case 'borrarImagen':
                    $this->borrarImagen();
                    break;
                case 'guardarGaleria':
                    $this->guardarGaleria();
                    break;
                case 'borrarGaleria':
                    $this->borrarGaleria();
                    break;
                case 'guardarOrden':
                    $this->guardarOrden();
                    break;
                case 'guardarOrdenImagenes':
                    $this->guardarOrdenImagenes();
                    break;
            }
        }
    }

    private function guardarPagina()
    {
        $idSitio  = $this->request->getPostVar('idSitio', Request::INTEGER);
        $idPagina = $this->request->getPostVar('idPagina', Request::INTEGER);
        $titulo1  = $this->request->getPostVar('titulo1', Request::STRING);
        $titulo2  = $this->request->getPostVar('titulo2', Request::STRING);

        /** @var GaleriaModel $model */
        $model = $this->createModel('GaleriaModel');
        $guardar = $model->guardarPagina($idSitio, $idPagina, $titulo1, $titulo2);

        if (!$guardar) {
            $this->generateJsonResponse(false, 'No se ha podido actualizar los datos de la página.');
            return;
        }

        $datos['idPagina'] = $guardar;

        $this->generateJsonResponse(true, 'Datos guardados.', $datos);
    }

    private function subirImagen()
    {
        $idSitio = $this->request->getPostVar('idSitio', Request::INTEGER);
        $campo   = $this->request->getPostVar('imagenSubir', Request::STRING);
        $idItem  = $this->request->getPostVar('idItem', Request::INTEGER); // pagina o evento

        /** @var GaleriaModel $model */
        $model = $this->createModel('GaleriaModel');
        $dir = 'uploads/'.$model->cargarCarpeta($idSitio);

        if ($campo == 'fondo') {
            $dir = $dir.'/fondos/';
        } else {
            $dir = $dir.'/galerias/galeria-'.$idItem.'/';
        }

        $indice = 'files-'.$campo.'-'.$idItem;
        $directorio = Variables::DIR_UPLOAD.$dir;

        $archivo = ProcesadorArchivo::guardarArchivo($indice, $directorio, true, false);
        $nombreArchivo = $archivo->nombre.'.'.$archivo->extension;
        $guardar = $model->guardarImagen($idSitio, $idItem, $nombreArchivo, $campo);

        if (!$guardar) {
            $archivoBorrar = $directorio.$nombreArchivo;
            if (is_file($archivoBorrar)) {
                unlink($archivoBorrar);
            }

            $this->generateJsonResponse(false, 'No se ha podido guardar la imagen.');
            return;
        }

        if ($campo == 'fondo') {
            $idItem = $guardar->id_pagina;
        }

        $imagen = Variables::URL.$dir.$guardar->{$campo};

        /** @var GaleriaView $view */
        $view = $this->createView('GaleriaView');

        $html = $view->htmlimagen($imagen, $idItem, $campo);

        $datos['html']   = $html;
        $datos['idItem'] = $idItem;
        $datos['campo']  = $campo;

        $this->generateJsonResponse(true, '', $datos);
    }

    private function borrarImagen()
    {
        $idSitio = $this->request->getPostVar('idSitio', Request::INTEGER);
        $idItem  = $this->request->getPostVar('idItem', Request::INTEGER);
        $campo   = $this->request->getPostVar('campo', Request::STRING);

        /** @var GaleriaModel $model */
        $model = $this->createModel('GaleriaModel');
        $carpeta = $model->cargarCarpeta($idSitio);

        $entradaBorrada = $model->borrarImagen($idItem, $campo);

        if (!$entradaBorrada) {
            $this->generateJsonResponse(false, 'No se ha podido borrar la imagen.');
            return;
        }

        $carpetaUpload = 'uploads/'.$carpeta;

        if ($campo == 'fondo') {
            $carpetaUpload = $carpetaUpload.'/fondos/';
        } else {
            $carpetaUpload = $carpetaUpload.'/galerias/galeria-'.$idSitio.'/';
        }

        $dir = Variables::DIR_UPLOAD.$carpetaUpload;

        $archivoBorrar = $dir.$entradaBorrada->{$campo};
        if (is_file($archivoBorrar)) {
            unlink($archivoBorrar);
        }

        /** @var GaleriaView $view */
        $view = $this->createView('GaleriaView');
        $html = $view->htmlSubirImagen($idItem, $campo);

        $datos['html'] = $html;

        $this->generateJsonResponse(true, '', $datos);
    }

    private function cargarEdicion($idGaleria, $idSitio)
    {
        if (!$idGaleria) {
            $idGaleria = 0;
        }

        /** @var GaleriaModel $model */
        $model = $this->createModel('GaleriaModel');
        $carpeta  = $model->cargarCarpeta($idSitio);
        $galeria  = $model->buscarGaleria($idGaleria);

        $imagenes = '';

        if ($galeria) {
            $imagenes = $model->buscarImagenes($idGaleria);
        }

        /** @var GaleriaView $view */
        $view = $this->createView('GaleriaView');
        $html = $view->cargarEditar($idSitio, $carpeta, $galeria, $imagenes);

        return $html;
    }

    private function guardarGaleria()
    {
        $idSitio     = $this->request->getPostVar('idSitio', Request::INTEGER);
        $idGaleria   = $this->request->getPostVar('idGaleria', Request::INTEGER);
        $titulo      = $this->request->getPostVar('titulo', Request::STRING);
        $descripcion = $this->request->getPostVar('descripcion', Request::STRING);

        /** @var GaleriaModel $model */
        $model = $this->createModel('GaleriaModel');
        $galeria = $model->guardarGaleria($idSitio, $idGaleria, $titulo, $descripcion);

        if (!$galeria) {
            $this->generateJsonResponse(false, 'No se ha podido actualizar los datos.');
            return;
        }

        $datos['idGaleria'] = $galeria->id_galeria;

        $this->generateJsonResponse(true, 'Datos guardados.', $datos);
    }

    private function borrarGaleria()
    {
        $idSitio     = $this->request->getPostVar('idSitio', Request::INTEGER);
        $idGaleria   = $this->request->getPostVar('idGaleria', Request::INTEGER);

        /** @var GaleriaModel $model */
        $model = $this->createModel('GaleriaModel');
        $galeria = $model->borrarGaleria($idGaleria);

        if (!$galeria) {
            $this->generateJsonResponse(false, 'No se ha podido borrar la galería.');
            return;
        }

        $this->generateJsonResponse(true, 'La galería ha sido borrada.');
    }

    private function guardarOrden()
    {
        $galerias = $this->request->getPostVar('galerias', Request::ARRAY_VAR);

        if (!$galerias) {
            $this->generateJsonResponse(false, 'Hubo un error en el sistema, no se recibieron las galerias.');
            return;
        }

        $totalGalerias = sizeof($galerias);
        $error        = false;

        /** @var GaleriaModel $model */
        $model  = $this->createModel('GaleriaModel');

        for ($i = 0; $i < $totalGalerias; $i++) {
            $idGaleria = intval(str_replace(('galeria-'), '', $galerias[$i]));

            if (!$model->guardarOrden($idGaleria, ($i + 1))) {
                $error = true;
                break;
            }
        }

        if ($error) {
            $this->generateJsonResponse(false, 'Hubo un error al guardar el orden de las galerias.');
            return;
        }

        $this->generateJsonResponse(true, 'Galerias reordenados.');
    }

    private function guardarOrdenImagenes()
    {
        $imagenes = $this->request->getPostVar('imagenes', Request::ARRAY_VAR);

        if (!$imagenes) {
            $this->generateJsonResponse(false, 'Hubo un error en el sistema, no se recibieron las imagenes.');
            return;
        }

        $totalImagenes = sizeof($imagenes);
        $error         = false;

        /** @var GaleriaModel $model */
        $model  = $this->createModel('GaleriaModel');

        for ($i = 0; $i < $totalImagenes; $i++) {
            $idImagen = intval(str_replace(('imagen-'), '', $imagenes[$i]));

            if (!$model->guardarOrdenImagen($idImagen, ($i + 1))) {
                $error = true;
                break;
            }
        }

        if ($error) {
            $this->generateJsonResponse(false, 'Hubo un error al guardar el orden de las imagenes.');
            return;
        }

        $this->generateJsonResponse(true, 'Imagenes reordenados.');
    }
}
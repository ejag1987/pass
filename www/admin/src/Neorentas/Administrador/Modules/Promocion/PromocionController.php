<?php

namespace Neorentas\Administrador\Modules\Promocion;

use Neorentas\Administrador\Modules\Promocion\Models\PromocionModel;
use Neorentas\Administrador\Modules\Promocion\Views\PromocionView;
use Neorentas\Administrador\System\ProcesadorArchivo;
use Neorentas\Administrador\System\Variables;
use ZCode\Lighting\Controller\BaseController;
use ZCode\Lighting\Http\Request;

class PromocionController extends BaseController
{
    public function run()
    {
        $idPerfil = $this->session->getString('idPerfil');
        $html     = '';

        if ($idPerfil != 1) {
            $this->response = $html;
            return;
        }

        $idSitio = $this->request->getUrlVar(1);

        $this->session->setInt('idSitio', intval($idSitio));

        /** @var PromocionModel $model */
        $model = $this->createModel('PromocionModel');
        $carpeta     = $model->cargarCarpeta($idSitio);
        $promociones = $model->cargarPromociones($idSitio);
        $pagina      = $model->buscarPagina($idSitio);

        /** @var PromocionView $view */
        $view = $this->createView('PromocionView');
        $html = $view->cargarPromocion($idSitio, $carpeta, $promociones, $pagina);

        $this->response = $html;
    }

    public function runAjax()
    {
        $accion = $this->request->getPostVar('accion', Request::STRING);

        if (strlen($accion) > 0) {
            switch ($accion) {
                case 'nuevaPromocion':
                    $this->accionNuevo();
                    break;
                case 'subirImagen':
                    $this->subirImagen();
                    break;
                case 'borrarImagen':
                    $this->borrarImagen();
                    break;
                case 'guardarPromocion':
                    $this->guardarPromocion();
                    break;
                case 'borrarPromocion':
                    $this->borrarPromocion();
                    break;
                case 'guardarOrden':
                    $this->guardarOrden();
                    break;
                case 'guardarPagina':
                    $this->guardarPagina();
                    break;
            }
        }
    }

    private function accionNuevo()
    {
        $idSitio = $this->request->getPostVar('idSitio', Request::INTEGER);

        /** @var PromocionModel $model */
        $model = $this->createModel('PromocionModel');
        $carpeta = $model->cargarCarpeta($idSitio);
        $promocion = $model->crearPromocion($idSitio);

        /** @var PromocionView $view */
        $view = $this->createView('PromocionView');
        $html = $view->cargarHtmlPromocion($promocion, $carpeta);

        $datos['html']  = $html;
        $this->generateJsonResponse(true, '', $datos);
    }

    private function subirImagen()
    {
        $idSitio     = $this->request->getPostVar('idSitio', Request::INTEGER);
        /** $idPromocion => id promocion o id sitio **/
        $idPromocion = $this->request->getPostVar('idPromocion', Request::INTEGER);
        $campo       = $this->request->getPostVar('imagenSubir', Request::STRING);

        /** @var PromocionModel $model */
        $model = $this->createModel('PromocionModel');
        $carpeta = 'uploads/'.$model->cargarCarpeta($idSitio);

        if ($campo == 'fondo') {
            $dir = $carpeta.'/fondos/';
        } else {
            $promocion = $model->buscarPromocion($idPromocion);
            $fecha     = $promocion->fecha_creacion;
            $dir = $carpeta.'/promociones/'.date('Y', strtotime($fecha)).'/'.date('m', strtotime($fecha)).'/';
        }

        $archivo = ProcesadorArchivo::guardarArchivo('files-'.$campo.'-'.$idPromocion, Variables::DIR_UPLOAD.$dir, true, false);
        $guardar = $model->guardarImagen($idSitio, $idPromocion, $archivo->nombre.'.'.$archivo->extension, $campo);

        if (!$guardar) {
            $archivoBorrar = $dir.'/'.$archivo->nombre.'.'.$archivo->extension;
            if (is_file($archivoBorrar)) {
                unlink($archivoBorrar);
            }

            $this->generateJsonResponse(false, 'No se ha podido guardar la imagen.');
            return;
        }

        if ($campo == 'fondo') {
            $idPromocion = $guardar->id_pagina;
        }

        $imagen = Variables::URL.$dir.$guardar->{$campo};

        /** @var PromocionView $view */
        $view = $this->createView('PromocionView');
        $html = $view->htmlimagen($imagen, $idPromocion, $campo);

        $datos['html']        = $html;
        $datos['idPromocion'] = $idPromocion;
        $datos['campo']       = $campo;

        $this->generateJsonResponse(true, '', $datos);
    }

    private function borrarImagen()
    {
        $idSitio     = $this->request->getPostVar('idSitio', Request::INTEGER);
        $idPromocion = $this->request->getPostVar('idPromocion', Request::INTEGER);
        $campo       = $this->request->getPostVar('campo', Request::STRING);

        /** @var PromocionModel $model */
        $model = $this->createModel('PromocionModel');
        $carpeta = $model->cargarCarpeta($idSitio);

        /** $idPromocion => id promocion o id pagina **/
        $entradaBorrada = $model->borrarImagen($idPromocion, $campo);

        if (!$entradaBorrada) {
            $this->generateJsonResponse(false, 'No se ha podido borrar la imagen.');
            return;
        }

        $carpetaUpload = 'uploads/'.$carpeta;

        if ($campo == 'fondo') {
            $carpetaUpload = $carpetaUpload.'/fondos/';
        } else {
            $fecha         = $entradaBorrada->fecha_creacion;
            $carpetaUpload = $carpetaUpload.'/promociones/'.date('Y', strtotime($fecha)).'/'.date('m', strtotime($fecha)).'/';
        }

        $dir = Variables::DIR_UPLOAD.$carpetaUpload;

        $archivoBorrar = $dir.'/'.$entradaBorrada->{$campo};
        if (is_file($archivoBorrar)) {
            unlink($archivoBorrar);
        }

        /** @var PromocionView $view */
        $view = $this->createView('PromocionView');
        $html = $view->htmlSubirImagen($idPromocion, $campo);

        $datos['html'] = $html;

        $this->generateJsonResponse(true, '', $datos);
    }

    private function guardarPromocion()
    {
        $idPromocion = $this->request->getPostVar('idPromocion', Request::INTEGER);
        $link        = $this->request->getPostVar('link-'.$idPromocion, Request::STRING);

        /** @var PromocionModel $model */
        $model = $this->createModel('PromocionModel');

        $guardar = $model->guardarPromocion($idPromocion, $link);

        if (!$guardar) {
            $this->generateJsonResponse(false, 'No se ha podido actualizar la promoción.');
            return;
        }

        $this->generateJsonResponse(true, 'La promoción ha sido actualizada.');
    }

    private function borrarPromocion()
    {
        $idSitio     = $this->request->getPostVar('idSitio', Request::INTEGER);
        $idPromocion = $this->request->getPostVar('idPromocion', Request::INTEGER);

        /** @var PromocionModel $model */
        $model = $this->createModel('PromocionModel');
        $borrar = $model->borrarPromocion($idPromocion);

        if (!$borrar) {
            $this->generateJsonResponse(false, 'No se ha podido borrar la promoción.');
            return;
        }

        $carpeta = $model->cargarCarpeta($idSitio);
        $promocion = $model->buscarPromocion($idPromocion);

        $fecha         = $promocion->fecha_creacion;
        $carpetaUpload = 'uploads/'.$carpeta.'/promociones/'.date('Y', strtotime($fecha)).'/'.date('m', strtotime($fecha)).'/';
        $dir           = Variables::DIR_UPLOAD.$carpetaUpload;

        $archivoBorrar = $dir.$promocion->foto1;
        if (is_file($archivoBorrar)) {
            unlink($archivoBorrar);
        }

        $archivoBorrar = $dir.$promocion->foto2;
        if (is_file($archivoBorrar)) {
            unlink($archivoBorrar);
        }

        $this->generateJsonResponse(true, 'La promoción ha sido borrada.');
    }

    private function guardarOrden()
    {
        $promociones = $this->request->getPostVar('promociones', Request::ARRAY_VAR);

        if (!$promociones) {
            $this->generateJsonResponse(false, 'Hubo un error en el sistema, no se recibieron las promociones.');
            return;
        }

        $totalPromociones = sizeof($promociones);
        $error            = false;

        /** @var PromocionModel $model */
        $model = $this->createModel('PromocionModel');

        for ($i = 0; $i < $totalPromociones; $i++) {
            $idPromocion = intval(str_replace(('promocion-'), '', $promociones[$i]));

            if (!$model->guardarOrden($idPromocion, ($i + 1))) {
                $error = true;
                break;
            }
        }

        if ($error) {
            $this->generateJsonResponse(false, 'Hubo un error al guardar el orden de las promociones.');
            return;
        }

        $this->generateJsonResponse(true, 'Promociones reordenadas.');
    }

    private function guardarPagina()
    {
        $idSitio  = $this->request->getPostVar('idSitio', Request::INTEGER);
        $idPagina = $this->request->getPostVar('idPagina', Request::INTEGER);
        $titulo1  = $this->request->getPostVar('titulo1', Request::STRING);
        $titulo2  = $this->request->getPostVar('titulo2', Request::STRING);

        /** @var PromocionModel $model */
        $model = $this->createModel('PromocionModel');
        $guardar = $model->guardarPagina($idSitio, $idPagina, $titulo1, $titulo2);

        if (!$guardar) {
            $this->generateJsonResponse(false, 'No se ha podido actualizar los datos de la página.');
            return;
        }

        $datos['idPagina'] = $guardar;

        $this->generateJsonResponse(true, 'Datos guardados.', $datos);
    }
}

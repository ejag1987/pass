<?php

namespace Neorentas\Administrador\Modules\Comercial;

use Neorentas\Administrador\Modules\Comercial\Models\ComercialModel;
use Neorentas\Administrador\Modules\Comercial\Views\ComercialView;
use Neorentas\Administrador\System\FuncionesGlobales;
use Neorentas\Administrador\System\ProcesadorArchivo;
use Neorentas\Administrador\System\Variables;
use ZCode\Lighting\Controller\BaseController;
use ZCode\Lighting\Http\Request;

class ComercialController extends BaseController
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

        /** @var ComercialModel $model */
        $model = $this->createModel('ComercialModel');
        $datos    = $model->cargarDatos($idSitio);
        $pagina   = $model->cargarPagina($idSitio);
        $serviciosSitio = $model->cargarServiciosSitio($idSitio);
        $servicios = $model->cargarServicios();

        /** @var ComercialView $view */
        $view = $this->createView('ComercialView');
        $html = $view->cargarComercial($idSitio, $datos, $pagina, $servicios, $serviciosSitio);
        $this->response = $html;
    }

    public function runAjax()
    {
        $accion = $this->request->getPostVar('accion', Request::STRING);

        if (strlen($accion) > 0) {
            switch ($accion) {
                case 'guardarDatos':
                    $this->guardarDatos();
                    break;
                case 'subirImagen':
                    $this->subirImagen();
                    break;
                case 'borrarImagen':
                    $this->borrarImagen();
                    break;
                case 'guardarPieRender':
                    $this->guardarPieRender();
                    break;
                case 'guardarServicio':
                    $this->guardarServicio();
                    break;
            }
        }
    }

    private function guardarDatos()
    {
        $idSitio         = $this->request->getPostVar('idSitio', Request::INTEGER);
        $idPagina        = $this->request->getPostVar('idPagina', Request::INTEGER);
        $titulo1         = $this->request->getPostVar('titulo1', Request::STRING);
        $titulo2         = $this->request->getPostVar('titulo2', Request::STRING);
        $texto           = $this->request->getPostVar('texto', Request::STRING);
        $terreno         = $this->request->getPostVar('terreno', Request::STRING);
        $contruida       = $this->request->getPostVar('contruida', Request::STRING);
        $arrendable      = $this->request->getPostVar('arrendable', Request::STRING);
        $estacionemiento = $this->request->getPostVar('estacionemiento', Request::STRING);
        $fechaApertura   = $this->request->getPostVar('fecha_apertura', Request::STRING);
        $horario         = $this->request->getPostVar('horario-tiendas', Request::STRING);

        /** @var ComercialModel $model */
        $model = $this->createModel('ComercialModel');
        $sitio = $model->buscarCarpeta($idSitio);
        $guardar = $model->guardarSitio($idSitio, $idPagina, $sitio, $titulo1, $titulo2, $texto, $terreno, $contruida, $arrendable,
            $estacionemiento, $fechaApertura, $horario);

        if (!$guardar) {
            $this->generateJsonResponse(false, 'No se ha podido actualizar el sitio.');
            return;
        }

        $datos['idPagina'] = $guardar;

        $this->generateJsonResponse(true, 'Sitio actualizado.', $datos);
    }

    private function subirImagen()
    {
        $tipo     = $this->request->getPostVar('tipo', Request::STRING);
        $idPagina = $this->request->getPostVar('idPagina', Request::INTEGER);
        $idSitio  = $this->request->getPostVar('idSitio', Request::INTEGER);

        /** @var ComercialModel $model */
        $model = $this->createModel('ComercialModel');
        $carpeta = $model->buscarCarpeta($idSitio);

        $tipoImagen = '';

        switch ($tipo) {
            case 'logo':
                $tipoImagen = 'imagenes';
                break;
            case 'fondos':
                $tipoImagen = 'fondos';
                break;
            case 'render1':
                $tipoImagen = 'imagenes';
                break;
            case 'render2':
                $tipoImagen = 'imagenes';
                break;
        }

        $dir     = Variables::DIR_UPLOAD.'uploads/'.$carpeta.'/'.$tipoImagen;
        $archivo = ProcesadorArchivo::guardarArchivo('files', $dir, false, false);
        $nombreArchivo = $archivo->nombre.'.'.$archivo->extension;

        $guardar = false;

        switch ($tipoImagen) {
            case 'imagenes':
                $guardar = $model->guardarImagen($idSitio, $tipo, $nombreArchivo);
                break;
            case 'fondos':
                $guardar = $model->guardarFondo($idSitio, $idPagina, $carpeta, $nombreArchivo);
                break;
        }

        /** @var ComercialView $view */
        $view = $this->createView('ComercialView');

        if (!$guardar) {
            $archivoBorrar = $dir.'/'.$nombreArchivo;
            if (is_file($archivoBorrar)) {
                unlink($archivoBorrar);
            }

            $html = $view->htmlSubirImagen($tipo);

            $datos['html'] = $html;
            $datos['tipo'] = $tipo;

            $this->generateJsonResponse(false, 'No se ha podido guardar la imagen.', $datos);
            return;
        }

        $imagenUrl = Variables::URL.'uploads/'.$carpeta.'/'.$tipoImagen.'/'.$nombreArchivo;

        $html = $view->htmlImagen($tipo, $imagenUrl);

        $datos['html'] = $html;
        $datos['tipo'] = $tipo;
        if ($tipo == 'fondos') {
            $datos['idPagina'] = $guardar;
        }

        $this->generateJsonResponse(true, 'Imagen guardada.', $datos);
        return;
    }

    private function borrarImagen()
    {
        $tipo     = $this->request->getPostVar('idTipo', Request::STRING);
        $idPagina = $this->request->getPostVar('idPagina', Request::INTEGER);
        $idSitio  = $this->request->getPostVar('idSitio', Request::INTEGER);

        $tipoImagen = '';

        switch ($tipo) {
            case 'logo':
                $tipoImagen = 'imagenes';
                break;
            case 'fondos':
                $tipoImagen = 'fondos';
                break;
            case 'render1':
                $tipoImagen = 'imagenes';
                break;
            case 'render2':
                $tipoImagen = 'imagenes';
                break;
        }

        /** @var ComercialModel $model */
        $model = $this->createModel('ComercialModel');
        $carpeta = $model->buscarCarpeta($idSitio);
        $dir     = Variables::DIR_UPLOAD.'uploads/'.$carpeta.'/'.$tipoImagen;

        $borrar = false;

        switch ($tipoImagen) {
            case 'imagenes':
                $borrar = $model->borrarImagen($idSitio, $tipo);
                break;
            case 'fondos':
                $borrar = $model->borrarFondo($idPagina);
                break;
        }

        if (!$borrar) {
            $this->generateJsonResponse(false, 'No se ha podido borrar la imagen.');
            return;
        }

        $archivoBorrar = $dir.'/'.$borrar;
        if (is_file($archivoBorrar)) {
            unlink($archivoBorrar);
        }

        /** @var ComercialView $view */
        $view = $this->createView('ComercialView');
        $html = $view->htmlSubirImagen($tipo);

        $datos['html'] = $html;
        $datos['tipo'] = $tipo;

        $this->generateJsonResponse(true, 'Imagen borrada.', $datos);
    }

    private function guardarServicio()
    {
        $idSitio   = $this->request->getPostVar('idSitio', Request::INTEGER);
        $servicios = $this->request->getPostVar('servicios', Request::ARRAY_VAR);

        /** @var ComercialModel $model */
        $model = $this->createModel('ComercialModel');
        $serviciosGuardados = $model->cargarServiciosAnteriores($idSitio);

        $serviciosNuevos = [];
        $serviciosBorrar = [];

        $arrayServicios = FuncionesGlobales::objectToArray($serviciosGuardados);

        if ($serviciosGuardados) {
            if (sizeof($servicios) > 0) {
                // compara
                $serviciosNuevos = array_diff($servicios, $arrayServicios);
                $serviciosBorrar = array_diff($arrayServicios, $servicios);
            } else {
                $serviciosBorrar = $arrayServicios;
            }
        } else {
            if (sizeof($servicios) > 0) {
                $serviciosNuevos = $servicios;
            }
        }

        $cambiarServicios = $model->guardarServicios($idSitio, $serviciosNuevos, $serviciosBorrar);

        if (!$cambiarServicios) {
            $this->generateJsonResponse(false, 'No se ha podido guardar los servicios.');
            return;
        }

        $this->generateJsonResponse(true, 'Servicios guardados.');
    }

    private function guardarPieRender()
    {
        $idSitio    = $this->request->getPostVar('idSitio', Request::INTEGER);
        $pieImagen1 = $this->request->getPostVar('pie-foto1', Request::STRING);
        $pieImagen2 = $this->request->getPostVar('pie-foto2', Request::STRING);

        /** @var ComercialModel $model */
        $model = $this->createModel('ComercialModel');
        $guardar = $model->guardarPieRender($idSitio, $pieImagen1, $pieImagen2);

        if (!$guardar) {
            $this->generateJsonResponse(false, 'No se ha podido actualizar el sitio.');
            return;
        }

        $this->generateJsonResponse(true, 'Sitio actualizado.');
    }
}

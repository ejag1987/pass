<?php

namespace Neorentas\Administrador\Modules\Portada;

use Neorentas\Administrador\Modules\Portada\Models\PortadaModel;
use Neorentas\Administrador\Modules\Portada\Views\PortadaView;
use Neorentas\Administrador\System\ProcesadorArchivo;
use Neorentas\Administrador\System\Variables;
use ZCode\Lighting\Controller\BaseController;
use ZCode\Lighting\Http\Request;

class PortadaController extends BaseController
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

        /** @var PortadaModel $model */
        $model = $this->createModel('PortadaModel');
        $slide   = $model->cargarSlide($idSitio);
        $calugas = $model->cargarCaluga($idSitio);
       $calugasite = $model->cargarCalugaSite($idSitio);
        $carpeta = $model->cargarCarpeta($idSitio);

        /** @var PortadaView $view */
        $view = $this->createView('PortadaView');
        $html = $view->cargarPortada($idSitio, $carpeta, $slide, $calugas, $calugasite);

        $this->response = $html;
    }

    public function runAjax()
    {
        $accion = $this->request->getPostVar('accion', Request::STRING);

        if (strlen($accion) > 0) {
            switch ($accion) {
                case 'nuevoSlide':
                    $this->nuevoSlide();
                    break;
                case 'nuevaCaluga':
                    $this->nuevaCaluga();
                    break;
                case 'nuevaCalugaSite':
                    $this->nuevaCalugaSite();
                    break;
                case 'subirImagen':
                    $this->subirImagen();
                    break;
                case 'guardarDatos':
                    $this->guardarDatos();
                    break;
                case 'guardarDatoSlide':
                    $this->guardarDatoSlide();
                    break;
                case 'borrarImagen':
                    $this->borrarImagen();
                    break;
                case 'borrarSlideCaluga':
                    $this->borrarSlideCaluga();
                    break;
                case 'guardarOrden':
                    $this->guardarOrden();
                    break;
            }
        }
    }

    private function nuevoSlide()
    {
        $idSitio = $this->request->getPostVar('idSitio', Request::INTEGER);

        /** @var PortadaModel $model */
        $model = $this->createModel('PortadaModel');
        $slide = $model->crearSlide($idSitio);

        /** @var PortadaView $view */
        $view = $this->createView('PortadaView');
        $html = $view->htmlNuevoSlide($slide);

        $datos['html']  = $html;
        $datos['idSlide'] = $slide->id_portada_slide;
        $this->generateJsonResponse(true, '', $datos);
    }

    private function nuevaCaluga()
    {
        $idSitio = $this->request->getPostVar('idSitio', Request::INTEGER);

        /** @var PortadaModel $model */
        $model = $this->createModel('PortadaModel');
        $caluga = $model->crearCaluga($idSitio);

        /** @var PortadaView $view */
        $view = $this->createView('PortadaView');
        $html = $view->htmlNuevaCaluga($caluga);

        $datos['html']  = $html;
        $datos['idCaluga'] = $caluga->id_portada_caluga;
        $this->generateJsonResponse(true, '', $datos);
    }

    private function nuevaCalugaSite()
    {
        $idSitio = $this->request->getPostVar('idSitio', Request::INTEGER);

        /** @var PortadaModel $model */
        $model = $this->createModel('PortadaModel');
        $calugasite = $model->crearCalugaSite($idSitio);

        /** @var PortadaView $view */
        $view = $this->createView('PortadaView');
        $html = $view->htmlNuevaCalugaSite($calugasite);

        $datos['html']  = $html;
        $datos['idCaluga'] = $calugasite->id_portada_caluga;
        $this->generateJsonResponse(true, '', $datos);
    }


    private function subirImagen()
    {
        $idSitio = $this->request->getPostVar('idSitio', Request::INTEGER);
        $id      = $this->request->getPostVar('id', Request::INTEGER);
        $bloque  = $this->request->getPostVar('bloque', Request::STRING);

        /** @var PortadaModel $model */
        $model = $this->createModel('PortadaModel');
        $carpeta = $model->cargarCarpeta($idSitio);
        $dir     = Variables::DIR_UPLOAD.'/uploads/'.$carpeta.'/'.$bloque;

        $archivo = ProcesadorArchivo::guardarArchivo('files-'.$id, $dir, true, false);
        $nombreArchivo = $archivo->nombre.'.'.$archivo->extension;

        $guardar = $model->guardarImagen($id, $bloque, $nombreArchivo);

        if (!$guardar) {
            $archivoBorrar = $dir.'/'.$archivo->nombre.'.'.$archivo->extension;
            if (is_file($archivoBorrar)) {
                unlink($archivoBorrar);
            }

            $this->generateJsonResponse(false, 'No se ha podido guardar la imagen.');
            return;
        }

        $imagen = Variables::URL.'uploads/'.$carpeta.'/'.$bloque.'/'.$guardar->imagen;

        /** @var PortadaView $view */
        $view = $this->createView('PortadaView');
        $html = $view->htmlImagen($imagen, $id);

        $datos['html']   = $html;
        $datos['bloque'] = $bloque;
        $datos['id']     = $id;
        $this->generateJsonResponse(true, '', $datos);
    }

  private function guardarDatos()
    {
        $idSlide  = $this->request->getPostVar('idSlide', Request::INTEGER);
        $link   = $this->request->getPostVar('link-'.$idSlide, Request::STRING);
        $bloque = $this->request->getPostVar('databloque', Request::STRING);

        /** @var PortadaModel $model */
        $model = $this->createModel('PortadaModel');
        $guardar = $model->guardar($idSlide, $link, $bloque);

        if (!$guardar) {
            $this->generateJsonResponse(false, 'No se ha podido actualizar.');
            return;
        }

        $this->generateJsonResponse(true, 'Listo!! guardado.');
    }

  private function guardarDatoSlide()
    {
        $idSlide  = $this->request->getPostVar('idSlide', Request::INTEGER);
        $link   = $this->request->getPostVar('link-'.$idSlide, Request::STRING);
        $bloque = $this->request->getPostVar('databloque', Request::STRING);
        $comentario = $this->request->getPostVar('comentario-'.$idSlide, Request::STRING);

        /** @var PortadaModel $model */
        $model = $this->createModel('PortadaModel');
        $guardar = $model->guardarSlide($idSlide, $link, $bloque, $comentario);

        if (!$guardar) {
            $this->generateJsonResponse(false, 'No se ha podido actualizar.');
            return;
        }

        $this->generateJsonResponse(true, 'Listo!! guardado.');
    }

    private function borrarImagen()
    {
        $idSitio = $this->request->getPostVar('idSitio', Request::INTEGER);
        $id      = $this->request->getPostVar('id', Request::INTEGER);
        $bloque  = $this->request->getPostVar('bloque', Request::STRING);

        /** @var PortadaModel $model */
        $model = $this->createModel('PortadaModel');
        $borrar = $model->borrarImagen($id, $bloque);

        if (!$borrar) {
            $this->generateJsonResponse(false, 'No se ha podido borrar la imagen.');
            return;
        }

        $carpeta = $model->cargarCarpeta($idSitio);
        $dir  = Variables::DIR_UPLOAD.'/uploads/'.$carpeta.'/'.$bloque;

        $archivoBorrar = $dir.'/'.$borrar->imagen;
        if (is_file($archivoBorrar)) {
            unlink($archivoBorrar);
        }

        /** @var PortadaView $view */
        $view = $this->createView('PortadaView');
        $html = $view->htmlSubirImagen($id, $bloque);

        $datos['html'] = $html;
        $datos['id'] = $id;

        $this->generateJsonResponse(true, '', $datos);
    }

    private function borrarSlideCaluga()
    {
        $id     = $this->request->getPostVar('id', Request::INTEGER);
        $bloque = $this->request->getPostVar('bloque', Request::STRING);

        /** @var PortadaModel $model */
        $model = $this->createModel('PortadaModel');
        $borrar = $model->cambiarEstado($id, $bloque);

        if (!$borrar) {
            $this->generateJsonResponse(false, 'No se ha podido borrar.');
            return;
        }

        $this->generateJsonResponse(true, 'Listo!!! borrado.');

    }

    private function guardarOrden()
    {
        $caja  = $this->request->getPostVar('caja', Request::ARRAY_VAR);
        $grupo = $this->request->getPostVar('grupo', Request::STRING);

        if (!$caja) {
            $this->generateJsonResponse(false, 'Hubo un error en el sistema, no se recibieron los datos.');
            return;
        }

        $totalCajas = sizeof($caja);
        $error      = false;

        /** @var PortadaModel $model */
        $model = $this->createModel('PortadaModel');

        for ($i = 0; $i < $totalCajas; $i++) {
            $idCaja = intval(str_replace(($grupo.'-'), '', $caja[$i]));

            if (!$model->actualizarOrden($idCaja, ($i + 1), $grupo)) {
                $error = true;
                break;
            }
        }

        if ($error) {
            $this->generateJsonResponse(false, 'Hubo un error al guardar el orden.');
            return;
        }

        $this->generateJsonResponse(true, 'Listo!! se guardo el orden.');
    }
}
<?php

namespace Neorentas\Administrador\Modules\Rrss;

use Neorentas\Administrador\Modules\Rrss\Models\RrssModel;
use Neorentas\Administrador\Modules\Rrss\Views\RrssView;
use Neorentas\Administrador\System\ProcesadorArchivo;
use Neorentas\Administrador\System\Variables;
use ZCode\Lighting\Controller\BaseController;
use ZCode\Lighting\Http\Request;
use ZCode\Lighting\Http\ServerInfo;

class RrssController extends BaseController
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

        /** @var RrssModel $model */
        $model = $this->createModel('RrssModel');
        $redesSociales = $model->cargarRedesSociales($idSitio);
        $carpeta       = $model->cargarCarpeta($idSitio);

        /** @var RrssView $view */
        $view = $this->createView('RrssView');
        $html = $view->cargarRedesSociales($idSitio, $carpeta, $redesSociales);

        $this->response = $html;
    }

    public function runAjax()
    {
        $accion = $this->request->getPostVar('accion', Request::STRING);

        if (strlen($accion) > 0) {
            switch ($accion) {
                case 'nuevaRed':
                    $this->nuevaRed();
                    break;
                case 'subirIcono':
                    $this->subirIcono();
                    break;
                case 'borrarIcono':
                    $this->borrarIcono();
                    break;
                case 'guardarRed':
                    $this->guardarRed();
                    break;
                case 'borrarRed':
                    $this->borrarRed();
                    break;
                case 'guardarOrdenRedesSociales':
                    $this->guardarOrdenRedesSociales();
                    break;
            }
        }
    }

    private function nuevaRed()
    {
        $idSitio = $this->request->getPostVar('idSitio', Request::INTEGER);

        /** @var RrssModel $model */
        $model = $this->createModel('RrssModel');
        $carpeta   = $model->cargarCarpeta($idSitio);
        $redSocial = $model->crearRed($idSitio);

        /** @var RrssView $view */
        $view = $this->createView('RrssView');
        $html = $view->cargarHtmlRedSocial($carpeta, $redSocial);

        $datos['html']  = $html;
        $datos['idRed'] = $redSocial->id_red_social;
        $this->generateJsonResponse(true, '', $datos);
    }

    private function subirIcono()
    {
        $idRed   = $this->request->getPostVar('idRed', Request::INTEGER);
        $idSitio = $this->request->getPostVar('idSitio', Request::INTEGER);

        /** @var RrssModel $model */
        $model = $this->createModel('RrssModel');
        $carpeta = $model->cargarCarpeta($idSitio);
        $dir     = Variables::DIR_UPLOAD.'/uploads/'.$carpeta.'/redes_sociales';

        $archivo = ProcesadorArchivo::guardarArchivo('files-'.$idRed, $dir, false, false);

        $guardar = $model->guardarIcono($idRed, $archivo->nombre.'.'.$archivo->extension);

        if (!$guardar) {
            $archivoBorrar = $dir.'/'.$archivo->nombre.'.'.$archivo->extension;
            if (is_file($archivoBorrar)) {
                unlink($archivoBorrar);
            }

            $this->generateJsonResponse(false, 'No se ha podido guardar el logo.');
            return;
        }

        $imagenIcono = Variables::URL.'uploads/'.$carpeta.'/redes_sociales/'.$guardar->icono;

        /** @var RrssView $view */
        $view = $this->createView('RrssView');
        $html = $view->htmlImagen($imagenIcono, $idRed);

        $datos['html'] = $html;
        $datos['idRed'] = $idRed;

        $this->generateJsonResponse(true, '', $datos);
    }

    private function borrarIcono()
    {
        $idRed   = $this->request->getPostVar('idRed', Request::INTEGER);
        $idSitio = $this->request->getPostVar('idSitio', Request::INTEGER);

        /** @var RrssModel $model */
        $model = $this->createModel('RrssModel');
        $borrar = $model->borrarIcono($idRed);

        if (!$borrar) {
            $this->generateJsonResponse(false, 'No se ha podido borrar el ícono.');
            return;
        }

        $carpeta = $model->cargarCarpeta($idSitio);
        $dir  = Variables::DIR_UPLOAD.'/uploads/'.$carpeta.'/redes_sociales';

        $archivoBorrar = $dir.'/'.$borrar->icono;
        if (is_file($archivoBorrar)) {
            unlink($archivoBorrar);
        }

        /** @var RrssView $view */
        $view = $this->createView('RrssView');
        $html = $view->htmlSubirImagen($idRed);

        $datos['html'] = $html;
        $datos['idRed'] = $idRed;

        $this->generateJsonResponse(true, '', $datos);
    }

    private function guardarRed()
    {
        $idRed  = $this->request->getPostVar('idRed', Request::INTEGER);
        $nombre = $this->request->getPostVar('nombre-'.$idRed, Request::STRING);
        $link   = $this->request->getPostVar('link-'.$idRed, Request::STRING);

        /** @var RrssModel $model */
        $model = $this->createModel('RrssModel');
        $guardar = $model->guardarRed($idRed, $nombre, $link);

        if (!$guardar) {
            $this->generateJsonResponse(false, 'No se ha podido actualizar la red social.');
            return;
        }

        $this->generateJsonResponse(true, 'La red social ha sido actualizada.');
    }

    private function borrarRed()
    {
        $idRed  = $this->request->getPostVar('idRed', Request::INTEGER);

        /** @var RrssModel $model */
        $model = $this->createModel('RrssModel');
        $borrar = $model->cambiarEstadoRed($idRed);

        if (!$borrar) {
            $this->generateJsonResponse(false, 'No se ha podido borrar la red social.');
            return;
        }

        $this->generateJsonResponse(true, 'La red social ha sido borrada.');
    }

    private function guardarOrdenRedesSociales()
    {
        $redesSociales = $this->request->getPostVar('redesSociales', Request::ARRAY_VAR);

        if (!$redesSociales) {
            $this->generateJsonResponse(false, 'Hubo un error en el sistema, no se recibieron las redes sociales.');
            return;
        }

        $totalRedes = sizeof($redesSociales);
        $error      = false;

        /** @var RrssModel $model */
        $model = $this->createModel('RrssModel');

        for ($i = 0; $i < $totalRedes; $i++) {
            $idRed = intval(str_replace(('red-social-'), '', $redesSociales[$i]));

            if (!$model->actualizarOrdenRedes($idRed, ($i + 1))) {

                $error = true;
                break;
            }
        }

        if ($error) {
            $this->generateJsonResponse(false, 'Hubo un error al guardar el orden de las redes sociales.');
            return;
        }

    }
}

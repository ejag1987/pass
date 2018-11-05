<?php

namespace Neorentas\Administrador\Modules\Contacto;

use Neorentas\Administrador\Modules\Contacto\Models\ContactoModel;
use Neorentas\Administrador\Modules\Contacto\Views\ContactoView;
use Neorentas\Administrador\System\ProcesadorArchivo;
use Neorentas\Administrador\System\Variables;
use ZCode\Lighting\Controller\BaseController;
use ZCode\Lighting\Http\Request;

class ContactoController extends BaseController
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

        /** @var ContactoModel $model */
        $model = $this->createModel('ContactoModel');
        $idPagina = $model->buscarPagina($idSitio);
        $datos    = $model->cargarDatos($idSitio);
        $pagina   = $model->cargarPagina($idPagina);

        /** @var ContactoView $view */
        $view = $this->createView('ContactoView');
        $html = $view->cargarContacto($idPagina, $datos, $pagina);

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
            }
        }
    }

    private function guardarDatos()
    {
        $idSitio    = $this->request->getPostVar('idSitio', Request::INTEGER);
        $idPagina   = $this->request->getPostVar('idPagina', Request::INTEGER);
        $titulo1    = $this->request->getPostVar('titulo1', Request::STRING);
        $titulo2    = $this->request->getPostVar('titulo2', Request::STRING);
        $direccion  = $this->request->getPostVar('direccion', Request::STRING);
        $correo     = $this->request->getPostVar('correo', Request::STRING);
        $telefono   = $this->request->getPostVar('telefono', Request::STRING);
        $googleMaps = $this->request->getPostVar('google-maps', Request::STRING);
        $comoLlegar = $this->request->getPostVar('como-llegar', Request::STRING);

        /** @var ContactoModel $model */
        $model = $this->createModel('ContactoModel');
        $sitio = $model->buscarCarpeta($idSitio);
        $guardar = $model->guardarContacto($idSitio, $idPagina, $sitio, $titulo1, $titulo2, $direccion, $correo, $telefono,
            $googleMaps, $comoLlegar);

        if (!$guardar) {
            $this->generateJsonResponse(false, 'No se ha podido actualizar los datos de contacto.');
            return;
        }

        $datos['idPagina'] = $guardar;

        $this->generateJsonResponse(true, 'Datos guardados.', $datos);
    }

    private function subirImagen()
    {
        $idPagina = $this->request->getPostVar('idPagina', Request::INTEGER);
        $idSitio  = $this->request->getPostVar('idSitio', Request::INTEGER);

        /** @var ContactoModel $model */
        $model = $this->createModel('ContactoModel');
        $carpeta = $model->buscarCarpeta($idSitio);

        $fondo = '';

        if ($idPagina > 0) {
            $fondo = $model->buscarImagen($idPagina);
        }

        $dir  = Variables::DIR_UPLOAD.'uploads/'.$carpeta.'/fondos';
        $archivo = ProcesadorArchivo::guardarArchivo('files', $dir, true, false);

        $guardar = $model->guardarImagen($idSitio, $idPagina, $carpeta, $archivo->nombre.'.'.$archivo->extension);

        /** @var ContactoView $view */
        $view = $this->createView('ContactoView');

        if (!$guardar) {
            $archivoBorrar = $dir.'/'.$archivo->nombre.'.'.$archivo->extension;
            if (is_file($archivoBorrar)) {
                unlink($archivoBorrar);
            }

            $html = $view->htmlSubirImagen();

            $datos['html'] = $html;

            $this->generateJsonResponse(false, 'No se ha podido guardar el fondo.', $datos);
            return;
        }

        if ($fondo != '') {
            $archivoBorrar = $dir.'/'.$fondo;
            if (is_file($archivoBorrar)) {
                unlink($archivoBorrar);
            }
        }

        $imagenFondo = Variables::URL.'uploads/'.$carpeta.'/fondos/'.$archivo->nombre.'.'.$archivo->extension;


        $html = $view->htmlImagen($imagenFondo);

        $datos['html'] = $html;
        $datos['idPagina'] = $guardar;

        $this->generateJsonResponse(true, 'Fondo guardado.', $datos);
        return;
    }

    private function borrarImagen()
    {
        $idPagina = $this->request->getPostVar('idPagina', Request::INTEGER);
        $idSitio  = $this->request->getPostVar('idSitio', Request::INTEGER);

        /** @var ContactoModel $model */
        $model = $this->createModel('ContactoModel');
        $fondo   = $model->buscarImagen($idPagina);
        $borrar  = $model->borrarImagen($idPagina);
        $carpeta = $model->buscarCarpeta($idSitio);

        if (!$borrar) {
            $this->generateJsonResponse(false, 'No se ha podido borrar el fondo.');
            return;
        }

        $archivoBorrar = Variables::DIR_UPLOAD.'uploads/'.$carpeta.'/fondos/'.$fondo;
        if (is_file($archivoBorrar)) {
            unlink($archivoBorrar);
        }

        /** @var ContactoView $view */
        $view = $this->createView('ContactoView');
        $html = $view->htmlSubirImagen();

        $datos['html'] = $html;

        $this->generateJsonResponse(true, 'Fondo borrado.', $datos);
        return;
    }

}
<?php

namespace Neorentas\Administrador\Modules\Configuracion;

use Neorentas\Administrador\Modules\Configuracion\Models\ConfiguracionModel;
use Neorentas\Administrador\Modules\Configuracion\Views\ConfiguracionView;
use Neorentas\Administrador\System\ProcesadorArchivo;
use Neorentas\Administrador\System\Variables;
use ZCode\Lighting\Controller\BaseController;
use ZCode\Lighting\Http\Request;

class ConfiguracionController extends BaseController
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

        /** @var ConfiguracionModel $model */
        $model = $this->createModel('ConfiguracionModel');
        $idPagina = $model->buscarPagina($idSitio);
        $datos    = $model->cargarDatos($idSitio);
        $pagina   = $model->cargarPagina($idPagina);
        $imagenes = $model->cargarImagenes($idPagina);

        /** @var ConfiguracionView $view */
        $view = $this->createView('ConfiguracionView');
        $html = $view->cargarConfiguracion($idPagina, $datos, $pagina, $imagenes);

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
                case 'guardarTexto':
                    $this->guardarTexto();
                    break;
            }
        }
    }

    private function guardarDatos()
    {
        $idSitio = $this->request->getPostVar('idSitio', Request::INTEGER);
        $nombre  = $this->request->getPostVar('nombre', Request::STRING);
        $url     = $this->request->getPostVar('url', Request::STRING);

        $lastChar = (substr($url, -1));

        if ($lastChar != '/') {
            $url .= '/';
        }

        /** @var ConfiguracionModel $model */
        $model = $this->createModel('ConfiguracionModel');
        $guardar = $model->guardarSitio($idSitio, $nombre, $url);

        if (!$guardar) {
            $this->generateJsonResponse(false, 'No se ha podido actualizar el sitio.');
            return;
        }

        $this->generateJsonResponse(true, 'Sitio actualizado.');
    }

    private function subirImagen()
    {
        $tipo     = $this->request->getPostVar('tipo', Request::STRING);
        $idPagina = $this->request->getPostVar('idPagina', Request::INTEGER);
        $idSitio  = $this->request->getPostVar('idSitio', Request::INTEGER);

        /** @var ConfiguracionModel $model */
        $model = $this->createModel('ConfiguracionModel');
        $carpeta = $model->buscarCarpeta($idSitio);

        $dir     = Variables::DIR_UPLOAD.'uploads/'.$carpeta.'/'.$tipo;
        $archivo = ProcesadorArchivo::guardarArchivo('files', $dir, false, false);
        $nombreArchivo = $archivo->nombre.'.'.$archivo->extension;

        $guardar = false;

        switch ($tipo) {
            case 'imagenes':
                $guardar = $model->guardarImagen($idSitio, $idPagina, $nombreArchivo);
                break;
            case 'fondos':
                $guardar = $model->guardarFondo($idSitio, $idPagina, $nombreArchivo);
                break;
        }

        /** @var ConfiguracionView $view */
        $view = $this->createView('ConfiguracionView');

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

        $imagenUrl = Variables::URL.'uploads/'.$carpeta.'/'.$tipo.'/'.$nombreArchivo;

        $html = $view->htmlImagen($tipo, $imagenUrl);

        $datos['html'] = $html;
        $datos['tipo'] = $tipo;
        $datos['idPagina'] = $guardar;

        $this->generateJsonResponse(true, 'Imagen guardada.', $datos);
        return;
    }

    private function borrarImagen()
    {
        $tipo     = $this->request->getPostVar('idTipo', Request::STRING);
        $idPagina = $this->request->getPostVar('idPagina', Request::INTEGER);
        $idSitio  = $this->request->getPostVar('idSitio', Request::INTEGER);

        /** @var ConfiguracionModel $model */
        $model = $this->createModel('ConfiguracionModel');
        $carpeta = $model->buscarCarpeta($idSitio);
        $dir     = Variables::DIR_UPLOAD.'uploads/'.$carpeta.'/'.$tipo;

        $borrar = false;

        switch ($tipo) {
            case 'imagenes':
                $borrar = $model->borrarImagen($idSitio);
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

        /** @var ConfiguracionView $view */
        $view = $this->createView('ConfiguracionView');
        $html = $view->htmlSubirImagen($tipo);

        $datos['html'] = $html;
        $datos['tipo'] = $tipo;

        $this->generateJsonResponse(true, 'Imagen borrada.', $datos);
    }

    private function guardarTexto()
    {
        $idSitio  = $this->request->getPostVar('idSitio', Request::INTEGER);
        $idPagina = $this->request->getPostVar('idPagina', Request::INTEGER);
        $titulo1  = $this->request->getPostVar('titulo1', Request::STRING);
        $titulo2  = $this->request->getPostVar('titulo2', Request::STRING);
        $texto    = $this->request->getPostVar('texto', Request::STRING);

        /** @var ConfiguracionModel $model */
        $model = $this->createModel('ConfiguracionModel');
        $guardar = $model->guardarPaginaInformacion($idSitio, $idPagina, $texto, $titulo1, $titulo2);

        if (!$guardar) {
            $this->generateJsonResponse(false, 'No se ha podido guardar la información de la página.');
            return;
        }
        $datos['idPagina'] = $guardar;

        $this->generateJsonResponse(true, 'La información de la página ha sido guardada.', $datos);
    }

}

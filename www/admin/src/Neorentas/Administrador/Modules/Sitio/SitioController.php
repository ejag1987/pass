<?php

namespace Neorentas\Administrador\Modules\Sitio;

use Neorentas\Administrador\Modules\Sitio\Models\SitioModel;
use Neorentas\Administrador\Modules\Sitio\Views\SitioView;
use Neorentas\Administrador\System\FuncionesGlobales;
use ZCode\Lighting\Controller\BaseController;
use ZCode\Lighting\Http\Request;

class SitioController extends BaseController
{
    public function run()
    {
        $idPerfil = $this->session->getString('idPerfil');
        $html     = '';

        if ($idPerfil != 1) {
            $this->response = $html;
            return;
        }

        // Implement run() method.
        $idSitio = $this->request->getUrlVar(1);

        /** @var SitioModel $model */
        $model = $this->createModel('SitioModel');
        $sitio = $model->cargarSitio($idSitio);

        /** @var SitioView $view */
        $view = $this->createView('SitioView');
        $html = $view->cargarSitio($sitio);


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
            }
        }
    }

    private function guardarDatos()
    {
        $idSitio = $this->request->getPostVar('idSitio', Request::INTEGER);
        $nombre  = $this->request->getPostVar('nombre', Request::STRING);
        $url     = $this->request->getPostVar('url', Request::STRING);
        $correo  = $this->request->getPostVar('e-mail', Request::STRING);

        $lastChar = (substr($url, -1));

        if ($lastChar != '/') {
            $url .= '/';
        }

        /** @var SitioModel $model */
        $model = $this->createModel('SitioModel');
        $guardar = $model->guardarSitio($idSitio, $nombre, $url, $correo);

        if (!$guardar) {
            $this->generateJsonResponse(false, 'No se ha podido actualizar el sitio.');
            return;
        }

        $this->session->setString('urlPadre', $url);

        $this->generateJsonResponse(true, 'Sitio actualizado.');
    }
}
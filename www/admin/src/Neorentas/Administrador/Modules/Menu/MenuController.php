<?php

namespace Neorentas\Administrador\Modules\Menu;

use Neorentas\Administrador\Modules\Menu\Models\MenuModel;
use Neorentas\Administrador\Modules\Menu\Views\MenuView;
use ZCode\Lighting\Controller\BaseController;
use ZCode\Lighting\Http\Request;
use ZCode\Lighting\Http\ServerInfo;

class MenuController extends BaseController
{
    public function run()
    {
        $modulo      = $this->serverInfo->getData(ServerInfo::MODULE);
        $nombreSitio = $this->session->getString('nombreSitio');
        $idPerfil    = $this->session->getString('idPerfil');
        $html        = '';

        $idSitio = $this->session->getInt('idSitio');

        if ($modulo !== 'Login') {
            if (!$nombreSitio) {
                $nombreSitio = 'Paseo';
            }

            /** @var MenuModel $model */
            $model = $this->createModel('MenuModel');
            $paseos = $model->cargarPaseos();

            /** @var MenuView $view */
            $view = $this->createView('MenuView');
            $html = $view->cargarMenu($nombreSitio, $paseos, $idSitio);

            if ($idPerfil == 3) {
                $html = $view->cargarMenuMin($nombreSitio);
            }
        }

        $this->response = $html;
    }

    public function runAjax()
    {
        $accion = $this->request->getPostVar('accion', Request::STRING);

        if (strlen($accion) > 0) {
            switch ($accion) {
                case 'cambiarPaseo':
                    $this->cambiarPaseo();
                    break;
            }
        }
    }

    private function cambiarPaseo()
    {
        $idSitio = $this->request->getPostVar('idSitio', Request::INTEGER);

        $this->session->setInt('idSitio', intval($idSitio));

        /** @var MenuView $view */
        $view = $this->createView('MenuView');
        $html = $view->cargarMenuPaseos($idSitio);



        $datos['html'] = $html;

        $this->generateJsonResponse(true, '', $datos);
    }
}
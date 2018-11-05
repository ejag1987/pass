<?php

namespace Neorentas\Administrador\Modules\Header;

use Neorentas\Administrador\Modules\Header\Views\HeaderView;
use ZCode\Lighting\Controller\BaseController;
use ZCode\Lighting\Http\ServerInfo;

class HeaderController extends BaseController
{
    public function run()
    {
        $modulo     = $this->serverInfo->getData(ServerInfo::MODULE);
        $aliasSitio = $this->session->getString('alias');
        $html       = '';

        if ($modulo !== 'Login') {
            $html = $this->generarHeader();
        }

        $this->response = $html;

    }

    public function runAjax()
    {
        // Implement runAjax() method.
    }

    private function generarHeader()
    {
        $idUsuario   = $this->session->getString('idUsuario');
        $usuario     = $this->session->getString('numbre');
        $nombreSitio = $this->session->getString('nombreSitio');

        /** @var HeaderView $view */
        $view = $this->createView('HeaderView');
        $html = $view->cargarHeader($usuario, $idUsuario, $nombreSitio);
        $this->response = $html;

        return $html;
    }
}
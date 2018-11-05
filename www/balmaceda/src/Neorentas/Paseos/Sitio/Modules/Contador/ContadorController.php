<?php

namespace Neorentas\Paseos\Sitio\Modules\Contador;

use Neorentas\Paseos\Sitio\Modules\Contador\Views\ContadorView;
use ZCode\Lighting\Controller\BaseController;

class ContadorController extends BaseController
{
    public function run()
    {
        $fecha = '';

        /** @var ContadorView $view */
        $view = $this->createView('ContadorView');
        $html = $view->cargarLanding($fecha);

        $this->response = $html;
    }

    public function runAjax()
    {
        // Implement runAjax() method.
    }
}

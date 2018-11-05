<?php

namespace Neorentas\Paseos\Sitio\Modules\Landing;

use Neorentas\Paseos\Sitio\Modules\Landing\Views\LandingView;
use ZCode\Lighting\Controller\BaseController;

class LandingController extends BaseController
{
    public function run()
    {
        $fecha = '';

        /** @var LandingView $view */
        $view = $this->createView('LandingView');
        $html = $view->cargarLanding($fecha);

        $this->response = $html;
    }

    public function runAjax()
    {
        // Implement runAjax() method.
    }
}

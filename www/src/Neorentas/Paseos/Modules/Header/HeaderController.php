<?php

namespace Neorentas\Paseos\Modules\Header;

use Neorentas\Paseos\Modules\Header\Models\HeaderModel;
use Neorentas\Paseos\Modules\Header\Views\HeaderView;
use ZCode\Lighting\Controller\BaseController;

class HeaderController extends BaseController
{
    public function run()
    {
        /** @var HeaderModel $model */
        $model = $this->createModel('HeaderModel');
        $redesSociales = $model->cargarRedes();

        /** @var HeaderView $view */
        $view = $this->createView('HeaderView');
        $html = $view->cargarHeader($redesSociales);

        $this->response = $html;
    }

    public function runAjax()
    {
        // Implement runAjax() method.
    }

}
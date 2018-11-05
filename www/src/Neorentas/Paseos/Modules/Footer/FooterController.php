<?php

namespace Neorentas\Paseos\Modules\Footer;

use Neorentas\Paseos\Modules\Footer\Models\FooterModel;
use Neorentas\Paseos\Modules\Footer\Views\FooterView;
use ZCode\Lighting\Controller\BaseController;

class FooterController extends BaseController
{
    public function run()
    {
        /** @var FooterModel $model */
        $model = $this->createModel('FooterModel');
        $configuracion = $model->cargarConfiguracion();

        /** @var FooterView $view */
        $view = $this->createView('FooterView');
        $html = $view->cargarFooter($configuracion);

        $this->response = $html;
    }

    public function runAjax()
    {
        // Implement runAjax() method.
    }

}

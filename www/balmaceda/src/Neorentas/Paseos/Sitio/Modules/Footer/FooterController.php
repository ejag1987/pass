<?php

namespace Neorentas\Paseos\Sitio\Modules\Footer;

use Neorentas\Paseos\Sitio\Modules\Footer\Models\FooterModel;
use Neorentas\Paseos\Sitio\Modules\Footer\Views\FooterView;
use Neorentas\Paseos\Sitio\System\Variables;
use ZCode\Lighting\Controller\BaseController;

class FooterController extends BaseController
{
    public function run()
    {
        /** @var FooterModel $model */
        $model = $this->createModel('FooterModel');
        $datos = $model->cargarConfiguracion(Variables::ID_SITIO);

        /** @var FooterView $view */
        $view = $this->createView('FooterView');
        $html = $view->cargarFooter($datos);

        $this->response = $html;
    }

    public function runAjax()
    {
        // Implement runAjax() method.
    }

}

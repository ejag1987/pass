<?php

namespace Neorentas\Paseos\Sitio\Modules\Header;

use Neorentas\Paseos\Sitio\Modules\Header\Models\HeaderModel;
use Neorentas\Paseos\Sitio\Modules\Header\Views\HeaderView;
use Neorentas\Paseos\Sitio\System\Variables;
use ZCode\Lighting\Controller\BaseController;
use ZCode\Lighting\Http\ServerInfo;

class HeaderController extends BaseController
{
    public function run()
    {
        /** @var HeaderModel $model */
        $model = $this->createModel('HeaderModel');
        $redesSociales = $model->cargarRedes(Variables::ID_SITIO);
        $carpeta = $model->cargarCarpeta(Variables::ID_SITIO);

        $modulo = $this->serverInfo->getData(ServerInfo::MODULE);

        /** @var HeaderView $view */
        $view = $this->createView('HeaderView');
        $html = $view->cargarHeader($carpeta, $modulo, $redesSociales);

        $this->response = $html;
    }

    public function runAjax()
    {
        // Implement runAjax() method.
    }

}
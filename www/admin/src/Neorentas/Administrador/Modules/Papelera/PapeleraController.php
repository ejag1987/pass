<?php

namespace Neorentas\Administrador\Modules\Papelera;

use Neorentas\Administrador\Modules\Papelera\Models\PapeleraModel;
use Neorentas\Administrador\Modules\Papelera\Views\PapeleraView;
use Neorentas\Administrador\System\ProcesadorArchivo;
use Neorentas\Administrador\System\Variables;
use ZCode\Lighting\Controller\BaseController;
use ZCode\Lighting\Http\Request;

class PapeleraController extends BaseController
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

        /** @var PapeleraModel $model */
        $model = $this->createModel('PapeleraModel');
        $slide   = $model->cargarSlide($idSitio);
        $calugas = $model->cargarCaluga($idSitio);
        $carpeta = $model->cargarCarpeta($idSitio);

        /** @var PapeleraView $view */
        $view = $this->createView('PapeleraView');
        //$html = $view->cargarPapelera($idSitio, $carpeta, $slide, $calugas);

        $this->response = $html;
    }

    public function runAjax()
    {
        // TODO: Implement runAjax() method.
    }
}

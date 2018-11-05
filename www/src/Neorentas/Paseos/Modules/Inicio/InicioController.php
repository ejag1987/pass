<?php

namespace Neorentas\Paseos\Modules\Inicio;

use Neorentas\Paseos\Modules\Inicio\Models\InicioModel;
use Neorentas\Paseos\Modules\Inicio\Views\InicioView;
use ZCode\Lighting\Controller\BaseController;

class InicioController extends BaseController
{
    public function run()
    {
        /** @var InicioModel $model */
        $model = $this->createModel('InicioModel');
        $carpeta = $model->cargarCarpeta(1);
        $slide   = $model->cargarSlide(1);
        $marcas  = $model->cargarMarcas(1);
        $calugas = $model->cargarCalugas(1);

        /** @var InicioView $view */
        $view = $this->createView('InicioView');
        $html = $view->cargarPagina($carpeta, $slide, $marcas, $calugas);

        $this->response = $html;
    }

    public function runAjax()
    {
        // Implement runAjax() method.
    }
}
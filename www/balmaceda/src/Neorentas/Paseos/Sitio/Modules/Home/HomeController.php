<?php

namespace Neorentas\Paseos\Sitio\Modules\Home;

use Neorentas\Paseos\Sitio\Modules\Home\Models\HomeModel;
use Neorentas\Paseos\Sitio\Modules\Home\Views\HomeView;
use Neorentas\Paseos\Sitio\System\Variables;
use ZCode\Lighting\Controller\BaseController;
use ZCode\Lighting\Http\Request;

class HomeController extends BaseController
{
    private $carpeta;

    public function run()
    {
        /** @var HomeModel $model */
        $model = $this->createModel('HomeModel');
        $carpeta = $model->cargarCarpeta(Variables::ID_SITIO);
        $slide   = $model->cargarSlide(Variables::ID_SITIO);
        $marcas  = $model->cargarMarcas(Variables::ID_SITIO);
        $calugas = $model->cargarCalugas(Variables::ID_SITIO);
        $calugasite = $model->cargarCalugasSite(Variables::ID_SITIO);

        /** @var HomeView $view */
        $view = $this->createView('HomeView');
        $html = $view->cargarPagina($carpeta, $slide, $marcas, $calugas, $calugasite);

        $this->response = $html;
    }

    public function runAjax()
    {
        $accion = $this->request->getPostVar('accion', Request::STRING);

        if (strlen($accion) > 0) {
            switch ($accion) {
                case 'mostrarImagen':
                    $this->mostrarImagen();
                    break;
            }
        }
    }

    private function mostrarImagen()
    {
        /** @var HomeModel $model */
        $homeModel = $this->createModel('HomeModel');

        /** @var PortadaModel $model */
        $model = $this->createModel('HomeModel');
        $calugas = $model->cargarCalugas(Variables::ID_SITIO);
        $url= Variables::URL.'uploads/'.$homeModel->cargarCarpeta(Variables::ID_SITIO).'/caluga/';

        $datos = [
            'calugas' => $calugas, 
            'url' => $url
        ];

        $this->generateJsonResponse(true, '', $datos);
    }


}

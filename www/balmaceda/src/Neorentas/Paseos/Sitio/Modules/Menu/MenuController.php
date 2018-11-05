<?php

namespace Neorentas\Paseos\Sitio\Modules\Menu;

use Neorentas\Paseos\Sitio\Modules\Menu\Models\MenuModel;
use Neorentas\Paseos\Sitio\Modules\Menu\Views\MenuView;
use Neorentas\Paseos\Sitio\System\Variables;
use ZCode\Lighting\Controller\BaseController;
use ZCode\Lighting\Http\ServerInfo;

class MenuController extends BaseController
{
    public function run()
    {
        $modulo = $this->serverInfo->getData(ServerInfo::MODULE);
        $html   = '';

        if ($modulo !== 'Contador' && $modulo !== 'Landing') {
            /** @var MenuModel $model */
            $model = $this->createModel('MenuModel');

            $carpeta   = $model->buscarCarpeta(Variables::ID_SITIO);
            $datosMenu = $model->cargarMenus(Variables::ID_SITIO);

            $menu = $this->procesarMenu($datosMenu);

            /** @var MenuView $view */
            $view = $this->createView('MenuView');
            $html = $view->cargarMenus($carpeta, $menu);
        }

        $this->response = $html;
    }

    public function runAjax()
    {
        // Implement runAjax() method.
    }

    private function procesarMenu($datos, $idPadre = 0)
    {
        $menu = [];

        if ($datos) {
            foreach ($datos as $item) {
                if (intval($item->id_padre) === $idPadre) {
                    $idMenu = intval($item->id_menu);
                    $menu[$idMenu] = $item;

                    $hijos = $this->procesarMenu($datos, $idMenu);

                    if ($hijos) {
                        $menu[$idMenu]->submenu = $hijos;
                    }
                }
            }
        }


        return $menu;
    }
}
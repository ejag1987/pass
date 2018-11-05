<?php

namespace Neorentas\Paseos\Modules\Menu;

use Neorentas\Paseos\Modules\Menu\Models\MenuModel;
use Neorentas\Paseos\Modules\Menu\Views\MenuView;
use ZCode\Lighting\Controller\BaseController;

class MenuController extends BaseController
{
    public function run()
    {
        /** @var MenuModel $model */
        $model = $this->createModel('MenuModel');
        $datosMenu  = $model->cargarMenus(1);

        $menu = $this->procesarMenu($datosMenu);

        // Implement run() method.
        /** @var MenuView $view */
        $view = $this->createView('MenuView');
        $html = $view->cargarMenus($menu);

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
<?php

namespace Neorentas\Paseos\Sitio\Modules\Menu\Views;

use Neorentas\Paseos\System\Variables;
use ZCode\Lighting\View\BaseView;

class MenuView extends BaseView
{
    public function cargarMenus($carpeta, $menu)
    {
        $tmpl = $this->loadTemplate('menu');

        $this->addCss('menus');
        $this->addJs('menu');

        $numeroMenus = sizeof($menu);
        $menuHtml = $this->cargarMenu($menu, $numeroMenus);

        $tmpl->addSearchReplace('{#LOGO#}', Variables::URL.'uploads/'.$carpeta.'/imagenes/logo_balmaceda.png');
        $tmpl->addSearchReplace('{#MENU#}', $menuHtml);

        return $tmpl->getHtml();
    }

    private function cargarMenu($datos, $numeroMenus)
    {
        $html = '';
        $menuPadre = 0;

        foreach ($datos as $item) {
            $link = '';
            if ($item->alias) {
                $link = $this->serverInfo->getBaseUrl().'pagina/'.$item->alias;

                if ($item->alias == '/') {
                    $link = $this->serverInfo->getBaseUrl();
                }
            }

            if ($item->link) {
                $link = $item->link;
            }

            $target = '_self';
            if ($item->link != '') {
                $target = '_blank';
            }

            if (isset($item->submenu)) {
                $html .= '<div class="navbar-item has-dropdown is-hoverable" id="'.$item->id_menu.'"><a class="navbar-link" href="'.$link.'" target="'.$target.'">'.$item->nombre.'</a>';
                $html .= '<div class="navbar-dropdown">';

                $html .= $this->cargarMenu($item->submenu, $numeroMenus);
                $html .= '</div>';
                $html .= '</div>';
            } else {
                $html .= '<a class="navbar-item" href="'.$link.'" target="'.$target.'" id="'.$item->id_menu.'">'.$item->nombre.'</a>';
            }

            if ($item->id_padre == 0 && $menuPadre < ($numeroMenus-1)) {
                $html .= '<span class="separador is-hidden-touch">|</span>';
            }

            if ($item->id_padre == 0) {
                $menuPadre++;
            }
        }

        return $html;
    }
}
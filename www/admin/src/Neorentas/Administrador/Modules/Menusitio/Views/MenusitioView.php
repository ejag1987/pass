<?php

namespace Neorentas\Administrador\Modules\Menusitio\Views;

use ZCode\Lighting\View\BaseView;

class MenusitioView extends BaseView
{
    /**
     * @param $idSitio
     * @param $menus
     * @param $paginas
     * @return mixed
     */
    public function cargarMenu($idSitio, $menus, $paginas)
    {
        $tmpl = $this->loadTemplate('menu');

        $this->addGlobalCss('select2');
        $this->addGlobalJs('select2.full.min');
        $this->addGlobalJs('es');

        $this->addCss('toggle-switch');

        $this->addGlobalJs('jquery-ui.min');
        $this->addJs('jquery.mjs.nestedSortable');

        $this->addGlobalCss('jquery-confirm');
        $this->addGlobalJs('jquery-confirm');

        $this->addGlobalJs('validacion');

        $this->addCss('menu');
        $this->addJs('menu');

        $htmlMenu = $this->cargarHtmlMenu($menus, $paginas);

        $optionPaginas = '';
        if ($paginas) {
            $optionPaginas = $this->generateSelectOptions($paginas);
        }

        $tmpl->addSearchReplace('{#MENU#}', $htmlMenu);
        $tmpl->addSearchReplace('{#ID_SITIO#}', $idSitio);
        $tmpl->addSearchReplace('{#PAGINAS#}', $optionPaginas);


        return $tmpl->getHtml();
    }

    /**
     * @param $datos
     * @param $paginas
     * @return string
     */
    private function cargarHtmlMenu($datos, $paginas)
    {
        $html = '';

        if ($datos) {
            foreach ($datos as $item) {
                $tmpl = $this->loadTemplate('item_menu');

                $opcionPagina = $this->generateSelectOptions($paginas, $item->alias);

                $checked = 'checked';

                if ($item->eliminado == 1) {
                    $checked = '';
                }


                $tmpl->addSearchReplace('{#ID_MENU#}', $item->id_menu);
                $tmpl->addSearchReplace('{#NOMBRE#}', $item->nombre);
                $tmpl->addSearchReplace('{#PAGINA_SELECCION#}', $opcionPagina);
                $tmpl->addSearchReplace('{#LINK#}', $item->link);
                $tmpl->addSearchReplace('{#CHECKED#}', $checked);

                //var_dump($item);
                if (isset($item->submenu)) {
                    $html .= '<li class="" id="menu-'.$item->id_menu.'">';

                    $html .= $tmpl->getHtml();

                    $html .= '<ol class="sub-menu">';
                    $html .= $this->cargarHtmlMenu($item->submenu, $paginas);
                    $html .= '</ol>';
                } else {
                    $html .= '<li class="" id="menu-'.$item->id_menu.'">';
                    $html .= $tmpl->getHtml();
                }

                $html .= '</li>';
            }
        }

        return $html;
    }

    /**
     * @param $item
     * @param $paginas
     * @return string
     */
    public function crearNuevoMenu($item, $paginas)
    {
        $tmpl = $this->loadTemplate('item_menu');

        $opcionPagina = $this->generateSelectOptions($paginas, $item->alias);

        $checked = 'checked';

        if ($item->eliminado == 1) {
            $checked = '';
        }

        $tmpl->addSearchReplace('{#ID_MENU#}', $item->id_menu);
        $tmpl->addSearchReplace('{#NOMBRE#}', $item->nombre);
        $tmpl->addSearchReplace('{#PAGINA_SELECCION#}', $opcionPagina);
        $tmpl->addSearchReplace('{#LINK#}', $item->link);
        $tmpl->addSearchReplace('{#CHECKED#}', $checked);

        $html  = '<li class="mjs-nestedSortable-leaf" id="menu_'.$item->id_menu.'">';
        $html .= $tmpl->getHtml();
        $html .= '</li>';

        return $html;
    }
}
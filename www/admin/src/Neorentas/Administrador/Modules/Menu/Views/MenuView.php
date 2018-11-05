<?php

namespace Neorentas\Administrador\Modules\Menu\Views;


use ZCode\Lighting\View\BaseView;

class MenuView extends BaseView
{
    /**
     * @param $nombreSitio
     * @param $paseos
     * @param $idSitio
     * @return mixed
     */
    public function cargarMenu($nombreSitio, $paseos, $idSitio)
    {
        $tmpl = $this->loadTemplate('menu');

        // SELECT2
        $this->addGlobalCss('select2');
        $this->addGlobalCss('layout');
        $this->addGlobalJs('select2.full.min');
        $this->addGlobalJs('es');

        $this->addCss('menu');
        $this->addJs('menu');

        $optionSelect = '';


        if ($paseos) {
            if ($idSitio == 1) {
                $idSitio = 0;
            }
            $optionSelect = $this->generarOption($paseos, $idSitio);
        }

        $menuPaseos = '';
        if ($idSitio > 1) {
            $menuPaseos = $this->cargarMenuPaseos($idSitio);
        }


        $tmpl->addSearchReplace('{#PASEOS#}', $optionSelect);
        $tmpl->addSearchReplace('{#NOMBRE_SITIO#}', $nombreSitio);
        $tmpl->addSearchReplace('{#MENU_PASEOS#}', $menuPaseos);


        return $tmpl->getHtml();
    }

    public function cargarMenuPaseos($idSitio)
    {
        $tmpl = $this->loadTemplate('menu_paseos');

        $tmpl->addSearchReplace('{#BASE_URL#}', $this->serverInfo->getBaseUrl());
        $tmpl->addSearchReplace('{#ID_SITIO#}', $idSitio);


        return $tmpl->getHtml();
    }

    private function generarOption($items, $itemId = null)
    {
        $options = '';

        if ($items) {
            $numItems = sizeof($items);

            for ($i = 0; $i < $numItems; $i++) {
                $selected = '';

                if ($itemId !== null && $itemId === intval($items[$i]->item_id)) {
                    $selected = 'selected="selected"';
                }

                $options .= '<option value="'.$items[$i]->item_id.'" '.$selected.'>';
                $options .= $items[$i]->name;
                $options .= '</option>';
            }
        }

        return $options;
    }

    public function cargarMenuMin($nombreSitio)
    {
        $tmpl = $this->loadTemplate('menu_min');

        $tmpl->addSearchReplace('{#NOMBRE_SITIO#}', $nombreSitio);


        return $tmpl->getHtml();
    }
}
<?php

namespace Neorentas\Paseos\Sitio\Modules\Footer\Views;

use ZCode\Lighting\View\BaseView;

class FooterView extends BaseView
{
    public function cargarFooter($datos)
    {
        $tmpl = $this->loadTemplate('footer');

        $this->addCss('footer');

        $direccion = '';
        $comoLlegar = '';
        $telefonoMarcar = '';
        $telefono = '';
        $horario = '';

        if ($datos) {
            $direccion = $datos->direccion;
            $comoLlegar = $datos->como_llegar;
            $telefono       = $datos->telefono;
            if ($telefono != '') {
                $unwanted_array = [' '=>'', '('=>'', ')'=>''];
                $telefonoMarcar = '+'.strtr( $telefono, $unwanted_array );
            }
            $horario = $datos->horario;
        }

        $tmpl->addSearchReplace('{#DIRECCION#}', $direccion);
        $tmpl->addSearchReplace('{#COMO_LLEGAR#}', $comoLlegar);
        $tmpl->addSearchReplace('{#TELEFONO_MARCAR#}', $telefonoMarcar);
        $tmpl->addSearchReplace('{#TELEFONO#}', $telefono);
        $tmpl->addSearchReplace('{#HORARIO#}', $horario);

        return $tmpl->getHtml();
    }
}
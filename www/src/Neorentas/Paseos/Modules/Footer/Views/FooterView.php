<?php

namespace Neorentas\Paseos\Modules\Footer\Views;

use Neorentas\Paseos\System\FuncionesGlobales;
use ZCode\Lighting\View\BaseView;

class FooterView extends BaseView
{
    public function cargarFooter($configuracion)
    {
        $tmpl = $this->loadTemplate('footer');

        $this->addCss('footer');

        $direccion      = '';
        $telefono       = '';
        $telefonoMarcar = '';
        $correo         = '';

        if ($configuracion) {
            $direccion      = $configuracion->direccion;
            $telefono       = $configuracion->telefono;
            if ($telefono != '') {
                $unwanted_array = [' '=>'', '('=>'', ')'=>''];
                $telefonoMarcar = '+'.strtr( $telefono, $unwanted_array );
            }
            $correo         = $configuracion->correo;
        }

        $tmpl->addSearchReplace('{#DIRECCION#}', $direccion);
        $tmpl->addSearchReplace('{#TELEFONO#}', $telefono);
        $tmpl->addSearchReplace('{#TELEFONO_MARCAR#}', $telefonoMarcar);
        $tmpl->addSearchReplace('{#CORREO#}', $correo);

        return $tmpl->getHtml();
    }

    private function cargarMenu()
    {
        $tmpl = $this->loadTemplate('menu');

        return $tmpl->getHtml();
    }

    private function cargarDatosContacto($configuracion)
    {
        $tmpl = $this->loadTemplate('contacto');

        $correo         = '';
        $telefono       = '';
        $telefonoMarcar = '';

        $item = FuncionesGlobales::buscarValorObjeto($configuracion, 'correo', 'nombre');
        if ($item['existe']) {
            $correo = $item['item']->valor;
        }

        $item = FuncionesGlobales::buscarValorObjeto($configuracion, 'telefono', 'nombre');
        if ($item['existe']) {
            $telefono = $item['item']->valor;

            $unwanted_array = [' '=>'', '('=>'', ')'=>''];
            $telefonoMarcar = '+'.strtr( $telefono, $unwanted_array );
        }

        $tmpl->addSearchReplace('{#CORREO#}', $correo);
        $tmpl->addSearchReplace('{#TELEFONO#}', $telefono);
        $tmpl->addSearchReplace('{#MARCAR_TELEFONO#}', $telefonoMarcar);

        return $tmpl->getHtml();
    }
}
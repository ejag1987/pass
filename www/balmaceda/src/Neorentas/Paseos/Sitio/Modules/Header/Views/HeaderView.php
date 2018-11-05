<?php

namespace Neorentas\Paseos\Sitio\Modules\Header\Views;


use Neorentas\Paseos\Sitio\System\Variables;
use ZCode\Lighting\View\BaseView;

class HeaderView extends BaseView
{
    /**
     * @param $carpeta
     * @param $modulo
     * @param $redesSociales
     * @return mixed
     */
    public function cargarHeader($carpeta, $modulo, $redesSociales)
    {
        $tmpl = $this->loadTemplate('header');

        $this->addCss('header');

        $buscador = '';

        if ($modulo !== 'Contador' && $modulo !== 'Landing') {
            $buscador = $this->cargarBuscador();
        }

        $htmlRedes = $this->htmlRedesSociales($redesSociales, $carpeta);
        $tmpl->addSearchReplace('{#REDES_SOCIALES#}', $htmlRedes);
        $tmpl->addSearchReplace('{#BUSCADOR#}', $buscador);

        return $tmpl->getHtml();
    }

    /**
     * @return mixed
     */
    private function cargarBuscador()
    {
        $tmpl = $this->loadTemplate('buscador');

        return $tmpl->getHtml();
    }

    /**
     * @param $redesSociales
     * @param $carpeta
     * @return string
     */
    private function htmlRedesSociales($redesSociales, $carpeta)
    {
        $html = '';

        if ($redesSociales) {
            foreach ($redesSociales as $red) {
                $tmpl = $this->loadTemplate('redes_sociales');

                $imagen = Variables::URL.'uploads/'.$carpeta.'/redes_sociales/'.$red->icono;

                $tmpl->addSearchReplace('{#LINK#}', $red->link);
                $tmpl->addSearchReplace('{#NOMBRE#}', $red->nombre);
                $tmpl->addSearchReplace('{#IMAGEN_RED#}', $imagen);


                $html .= $tmpl->getHtml();
            }
        }


        return $html;
    }
}
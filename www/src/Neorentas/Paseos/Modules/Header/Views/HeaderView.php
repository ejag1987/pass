<?php

namespace Neorentas\Paseos\Modules\Header\Views;

use ZCode\Lighting\View\BaseView;

class HeaderView extends BaseView
{
    public function cargarHeader($redesSociales)
    {
        $tmpl = $this->loadTemplate('header');

        $this->addCss('header');

        $htmlRedes = $this->htmlRedesSociales($redesSociales);
        $tmpl->addSearchReplace('{#REDES_SOCIALES#}', $htmlRedes);

        return $tmpl->getHtml();
    }

    private function htmlRedesSociales($redesSociales)
    {
        $html = '';

        if ($redesSociales) {
            foreach ($redesSociales as $red) {
                $tmpl = $this->loadTemplate('redes_sociales');

                $imagen = $this->serverInfo->getBaseUrl().'uploads/paseo/redes_sociales/'.$red->icono;

                $tmpl->addSearchReplace('{#LINK#}', $red->link);
                $tmpl->addSearchReplace('{#NOMBRE#}', $red->nombre);
                $tmpl->addSearchReplace('{#IMAGEN_RED#}', $imagen);


                $html .= $tmpl->getHtml();
            }
        }


        return $html;
    }
}
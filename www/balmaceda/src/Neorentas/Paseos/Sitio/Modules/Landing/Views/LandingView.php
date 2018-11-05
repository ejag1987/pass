<?php

namespace Neorentas\Paseos\Sitio\Modules\Landing\Views;

use ZCode\Lighting\View\BaseView;

class LandingView extends BaseView
{
    public function cargarLanding($fecha)
    {
        $tmpl = $this->loadTemplate('landing');

        $this->addCss('owl.carousel');
        $this->addCss('owl.theme.default');
        $this->addCss('landing');

        $this->addJs('owl.carousel.min');
        $this->addJs('landing');

        $marcas = $this->cargarMarcas();
        $contenido = $this->cargarContador($fecha);

        $tmpl->addSearchReplace('{#FONDO_LANDING#}', 'http://paseo.extenddigital.cl/uploads/paseo_balmaceda/portada/fondo.jpg');
        $tmpl->addSearchReplace('{#MARCAS#}', $marcas);
        $tmpl->addSearchReplace('{#CONTADOR#}', $contenido);

        return $tmpl->getHtml();
    }

    private function cargarMarcas()
    {
        $tmpl = $this->loadTemplate('marcas');

        $tmpl->addSearchReplace('{#BASE_SITIO#}', 'http://paseo.extenddigital.cl/uploads/paseo_balmaceda/');
        return $tmpl->getHtml();
    }

    private function cargarContador($fecha)
    {
        $tmpl = $this->loadTemplate('contador');



        return $tmpl->getHtml();
    }
}
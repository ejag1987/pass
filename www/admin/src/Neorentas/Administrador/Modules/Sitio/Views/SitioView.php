<?php

namespace Neorentas\Administrador\Modules\Sitio\Views;

use ZCode\Lighting\View\BaseView;

class SitioView extends BaseView
{
    public function cargarSitio($sitio)
    {
        $tmpl = $this->loadTemplate('sitio');

        $this->addGlobalJs('validacion');

        $this->addCss('sitio');
        $this->addJs('sitio');

        $idSitio = 0;
        $nombre  = '';
        $carpeta = '';
        $url     = '';
        $correo  = '';

        if ($sitio) {
            $idSitio = $sitio->id_sitio;
            $nombre  = $sitio->nombre;
            $carpeta = $sitio->carpeta;
            $url     = $sitio->link;
            $correo  = $sitio->correo;
        }

        $tmpl->addSearchReplace('{#ID_SITIO#}', $idSitio);
        $tmpl->addSearchReplace('{#NOMBRE#}', $nombre);
        $tmpl->addSearchReplace('{#CARPETA#}', $carpeta);
        $tmpl->addSearchReplace('{#URL#}', $url);
        $tmpl->addSearchReplace('{#CORREO#}', $correo);

        return $tmpl->getHtml();
    }
}
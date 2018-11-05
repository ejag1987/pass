<?php

namespace Neorentas\Administrador\Modules\Header\Views;

use ZCode\Lighting\View\BaseView;

class HeaderView extends BaseView
{
    public function cargarHeader($usuario, $idUsuario, $nombreSitio)
    {
        $tmpl = $this->loadTemplate('header');

        $this->addCss('header');

        $tmpl->addSearchReplace('{#ID_USUARIO#}', $idUsuario);
        $tmpl->addSearchReplace('{#NOMBRE_USUARIO#}', $usuario);

        $tmpl->addSearchReplace('{#NOMBRE_SITIO#}', $nombreSitio);

        return $tmpl->getHtml();
    }
}
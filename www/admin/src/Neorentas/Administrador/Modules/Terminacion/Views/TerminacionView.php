<?php

namespace Neorentas\Administrador\Modules\Terminacion\Views;

use ZCode\Lighting\View\BaseView;

class TerminacionView extends BaseView
{
    public function cargarTerminaciones($idSitio, $terminaciones)
    {
        $tmpl = $this->loadTemplate('terminaciones');

        $this->addGlobalCss('jquery-confirm');
        $this->addGlobalJs('jquery-confirm');

        $this->addGlobalJs('validacion');

        $this->addCss('terminacion');
        $this->addJs('terminacion');

        $htmlTerminaciones = '';

        if ($terminaciones) {
            foreach ($terminaciones as $terminacion) {
                $htmlTerminaciones .= $this->cargarItem($terminacion);
            }
        }

        $tmpl->addSearchReplace('{#ID_SITIO#}', $idSitio);
        $tmpl->addSearchReplace('{#TERMINACIONES#}', $htmlTerminaciones);

        return $tmpl->getHtml();
    }

    private function cargarItem($terminacion)
    {
        $tmpl = $this->loadTemplate('item_terminacion');

        $tmpl->addSearchReplace('{#ID_TERMINACION#}', $terminacion->id_terminacion);
        $tmpl->addSearchReplace('{#NUMERO#}', $terminacion->numero);
        $tmpl->addSearchReplace('{#TERMINACION#}', $terminacion->descripcion);

        return $tmpl->getHtml();
    }

    public function cargarTerminacion()
    {
        $tmpl = $this->loadTemplate('item_terminacion');

        $tmpl->addSearchReplace('{#ID_TERMINACION#}', 0);
        $tmpl->addSearchReplace('{#NUMERO#}', '');
        $tmpl->addSearchReplace('{#TERMINACION#}', '');

        return $tmpl->getHtml();
    }
}
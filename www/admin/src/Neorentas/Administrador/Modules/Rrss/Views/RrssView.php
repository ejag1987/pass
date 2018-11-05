<?php

namespace Neorentas\Administrador\Modules\Rrss\Views;

use Neorentas\Administrador\System\Variables;
use ZCode\Lighting\View\BaseView;

class RrssView extends BaseView
{
    /**
     * @param $idSitio
     * @param $carpeta
     * @param $redesSociales
     * @return mixed
     */
    public function cargarRedesSociales($idSitio, $carpeta, $redesSociales)
    {
        $tmpl = $this->loadTemplate('redes_sociales');

        $this->addGlobalCss('upload');
        $this->addGlobalJs('jquery.ui.widget');
        $this->addGlobalJs('load-image.all.min');
        $this->addGlobalJs('canvas-to-blob.min');
        $this->addGlobalJs('jquery.iframe-transport');
        $this->addGlobalJs('jquery.fileupload');
        $this->addGlobalJs('jquery.fileupload-process');
        $this->addGlobalJs('jquery.fileupload-image');
        $this->addGlobalJs('jquery.fileupload-validate');

        $this->addGlobalCss('jquery-confirm');
        $this->addGlobalJs('jquery-confirm');

        $this->addGlobalJs('jquery-ui.min');

        $this->addGlobalJs('validacion');

        $this->addCss('rrss');
        $this->addJs('rrss');

        $html = '';

        if ($redesSociales) {
            foreach ($redesSociales as $red) {
                $html .= $this->cargarHtmlRedSocial($carpeta, $red);
            }
        }

        $tmpl->addSearchReplace('{#ID_SITIO#}', $idSitio);
        $tmpl->addSearchReplace('{#REDES_SOCIALES#}', $html);

        return $tmpl->getHtml();
    }

    /**
     * @param $carpeta
     * @param $red
     * @return mixed
     */
    public function cargarHtmlRedSocial($carpeta, $red)
    {
        $tmpl = $this->loadTemplate('red_social');

        /* si viene ícono o no */
        $htmlAccionIcono = $this->htmlSubirImagen($red->id_red_social);

        if ($red->icono != '') {
            $imagenIcono = Variables::URL.'uploads/'.$carpeta.'/redes_sociales/'.$red->icono;
            $htmlAccionIcono = $this->htmlImagen($imagenIcono, $red->id_red_social);
        }

        $tmpl->addSearchReplace('{#ID_RED_SOCIAL#}', $red->id_red_social);
        $tmpl->addSearchReplace('{#NOMBRE#}', $red->nombre);
        $tmpl->addSearchReplace('{#LINK#}', $red->link);
        $tmpl->addSearchReplace('{#HTML_ACCION_ICONO#}', $htmlAccionIcono);

        return $tmpl->getHtml();
    }

    /**
     * @param $imagenIcono
     * @param $idRed
     * @return mixed
     */
    public function htmlImagen($imagenIcono, $idRed)
    {
        $tmpl = $this->loadTemplate('imagen');

        $tmpl->addSearchReplace('{#IMAGEN#}', $imagenIcono);
        $tmpl->addSearchReplace('{#ID_RED_SOCIAL#}', $idRed);

        return $tmpl->getHtml();
    }

    /**
     * @param $idRed
     * @return mixed
     */
    public function htmlSubirImagen($idRed)
    {
        $tmpl = $this->loadTemplate('subir_imagen');

        $tmpl->addSearchReplace('{#ID_RED_SOCIAL#}', $idRed);

        return $tmpl->getHtml();
    }
}
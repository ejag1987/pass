<?php

namespace Neorentas\Administrador\Modules\Portada\Views;

use Neorentas\Administrador\System\Variables;
use ZCode\Lighting\View\BaseView;

class PortadaView extends BaseView
{
    public function cargarPortada($idSitio, $carpeta, $slides, $calugas, $calugasite)
    {
        $tmpl = $this->loadTemplate('portada');

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

        $this->addCss('portada');
        $this->addJs('portada');

        $htmlSlide  = '';
        $htmlCaluga = '';
        $htmlCalugaSite = '';

        if ($slides) {
            foreach ($slides as $slide) {
                $htmlSlide .= $this->htmlSlide($carpeta, $slide);
            }
        }

        if ($calugas) {
            foreach ($calugas as $caluga) {
                $htmlCaluga .= $this->htmlCaluga($carpeta, $caluga);
            }
        }
        if ($calugasite) {
            foreach ($calugasite as $site) {
                $htmlCalugaSite .= $this->htmlCalugaSite($carpeta, $site);
            }
        }

        $tmpl->addSearchReplace('{#ID_SITIO#}', $idSitio);
        $tmpl->addSearchReplace('{#SLIDE#}', $htmlSlide);
        $tmpl->addSearchReplace('{#CALUGAS#}', $htmlCaluga);
        $tmpl->addSearchReplace('{#SITE#}', $htmlCalugaSite);

        return $tmpl->getHtml();
    }

    public function htmlSlide($carpeta, $slide)
    {
        $tmpl = $this->loadTemplate('slide');

        $htmlAccionIcono = $this->htmlSubirImagen($slide->id_portada_slide, 'slide');

        if ($slide->imagen != '') {
            $imagenIcono = Variables::URL.'uploads/'.$carpeta.'/slide/'.$slide->imagen;
            $htmlAccionIcono = $this->htmlImagen($imagenIcono, $slide->id_portada_slide);
        }
        $tmpl->addSearchReplace('{#ID_SLIDE#}', $slide->id_portada_slide);
        $tmpl->addSearchReplace('{#LINK#}', $slide->link);
        $tmpl->addSearchReplace('{#HTML_ACCION_ICONO#}', $htmlAccionIcono);
        $tmpl->addSearchReplace('{#COMENTARIO#}', $slide->comentario);

        return $tmpl->getHtml();
    }

    public function htmlCaluga($carpeta, $caluga)
    {
        $tmpl = $this->loadTemplate('caluga');

        $htmlAccionIcono = $this->htmlSubirImagen($caluga->id_portada_caluga, 'caluga');

        if ($caluga->imagen != '') {
            $imagenIcono = Variables::URL.'uploads/'.$carpeta.'/caluga/'.$caluga->imagen;
            $htmlAccionIcono = $this->htmlImagen($imagenIcono, $caluga->id_portada_caluga);
        }

        $tmpl->addSearchReplace('{#ID_CALUGA#}', $caluga->id_portada_caluga);
        $tmpl->addSearchReplace('{#LINK#}', $caluga->link);
        $tmpl->addSearchReplace('{#HTML_ACCION_ICONO#}', $htmlAccionIcono);

        return $tmpl->getHtml();
    }

        public function htmlCalugaSite($carpeta, $site)
    {
        $tmpl = $this->loadTemplate('caluga');

        $htmlAccionIcono = $this->htmlSubirImagen($site->id_portada_caluga, 'caluga');

        if ($site->imagen != '') {
            $imagenIcono = Variables::URL.'uploads/'.$carpeta.'/caluga/'.$site->imagen;
            $htmlAccionIcono = $this->htmlImagen($imagenIcono, $site->id_portada_caluga);
        }

        $tmpl->addSearchReplace('{#ID_CALUGA#}', $site->id_portada_caluga);
        $tmpl->addSearchReplace('{#LINK#}', $site->link);
        $tmpl->addSearchReplace('{#HTML_ACCION_ICONO#}', $htmlAccionIcono);

        return $tmpl->getHtml();
    }
   

    public function htmlImagen($imagenIcono, $idSlide)
    {
        $tmpl = $this->loadTemplate('imagen');

        $tmpl->addSearchReplace('{#IMAGEN#}', $imagenIcono);
        $tmpl->addSearchReplace('{#ID#}', $idSlide);

        return $tmpl->getHtml();
    }

    public function htmlSubirImagen($idSlide, $bloque)
    {
        $tmpl = $this->loadTemplate('subir_imagen');

        $tmpl->addSearchReplace('{#ID#}', $idSlide);
        $tmpl->addSearchReplace('{#BLOQUE#}', $bloque);

        return $tmpl->getHtml();
    }

    public function htmlNuevoSlide($slide)
    {
        $tmpl = $this->loadTemplate('slide');

        $htmlAccionIcono = $this->htmlSubirImagen($slide->id_portada_slide, 'slide');

        $tmpl->addSearchReplace('{#ID_SLIDE#}', $slide->id_portada_slide);
        $tmpl->addSearchReplace('{#COMENTARIO#}', $slide->comentario);
        $tmpl->addSearchReplace('{#LINK#}', $slide->link);
        $tmpl->addSearchReplace('{#HTML_ACCION_ICONO#}', $htmlAccionIcono);

        return $tmpl->getHtml();
    }

    public function htmlNuevaCaluga($caluga)
    {
        $tmpl = $this->loadTemplate('caluga');

        $htmlAccionIcono = $this->htmlSubirImagen($caluga->id_portada_caluga, 'caluga');

        $tmpl->addSearchReplace('{#ID_CALUGA#}', $caluga->id_portada_caluga);
        $tmpl->addSearchReplace('{#LINK#}', $caluga->link);
        $tmpl->addSearchReplace('{#HTML_ACCION_ICONO#}', $htmlAccionIcono);

        return $tmpl->getHtml();
    }

        public function htmlNuevaCalugaSite($site)
    {
        $tmpl = $this->loadTemplate('caluga');

        $htmlAccionIcono = $this->htmlSubirImagen($site->id_portada_caluga, 'caluga');

        $tmpl->addSearchReplace('{#ID_CALUGA#}', $site->id_portada_caluga);
        $tmpl->addSearchReplace('{#LINK#}', $site->link);
        $tmpl->addSearchReplace('{#HTML_ACCION_ICONO#}', $htmlAccionIcono);

        return $tmpl->getHtml();
    }
}
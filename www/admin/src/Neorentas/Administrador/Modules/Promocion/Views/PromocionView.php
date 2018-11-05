<?php

namespace Neorentas\Administrador\Modules\Promocion\Views;

use Neorentas\Administrador\System\Variables;
use ZCode\Lighting\View\BaseView;

class PromocionView extends BaseView
{
    /**
     * @param $idSitio
     * @param $carpeta
     * @param $promociones
     * @param $pagina
     * @return mixed
     */
    public function cargarPromocion($idSitio, $carpeta, $promociones, $pagina)
    {
        $tmpl = $this->loadTemplate('listado');

        $this->addGlobalCss('upload');
        $this->addGlobalJs('jquery.ui.widget');
        $this->addGlobalJs('load-image.all.min');
        $this->addGlobalJs('canvas-to-blob.min');
        $this->addGlobalJs('jquery.iframe-transport');
        $this->addGlobalJs('jquery.fileupload');
        $this->addGlobalJs('jquery.fileupload-process');
        $this->addGlobalJs('jquery.fileupload-image');
        $this->addGlobalJs('jquery.fileupload-validate');

        $this->addGlobalJs('jquery-ui.min');

        $this->addGlobalCss('jquery-confirm');
        $this->addGlobalJs('jquery-confirm');

        $this->addGlobalJs('validacion');

        $this->addCss('promocion');
        $this->addJs('promocion');

        $htmlPromocion = '';

        if ($promociones) {
            foreach ($promociones as $promocion) {
                $htmlPromocion .= $this->cargarHtmlPromocion($promocion, $carpeta);
            }
        }

        $idPagina = 0;
        $titulo1 = '';
        $titulo2 = '';
        $fondo   = '';

        if ($pagina) {
            $idPagina = $pagina->id_pagina;
            $titulo1  = $pagina->titulo1;
            $titulo2  = $pagina->titulo2;
            $fondo    = $pagina->fondo;
        }

        $htmlAccionFondo = $this->htmlSubirImagen($idPagina, 'fondo');

        if ($fondo != '') {
            $dir = Variables::URL.'uploads/'.$carpeta.'/fondos/';

            $fondo = $dir.$pagina->fondo;
            $htmlAccionFondo = $this->htmlimagen($fondo, $pagina->id_pagina, 'fondo');
        }

        $tmpl->addSearchReplace('{#ID_SITIO#}', $idSitio);
        $tmpl->addSearchReplace('{#ID_PAGINA#}', $idPagina);
        $tmpl->addSearchReplace('{#HTML_FONDO#}', $htmlAccionFondo);
        $tmpl->addSearchReplace('{#TITULO1#}', $titulo1);
        $tmpl->addSearchReplace('{#TITULO2#}', $titulo2);
        $tmpl->addSearchReplace('{#PROMOCIONES#}', $htmlPromocion);

        return $tmpl->getHtml();
    }

    /**
     * @param $promocion
     * @param $carpeta
     * @return mixed
     */
    public function cargarHtmlPromocion($promocion, $carpeta)
    {
        $tmpl = $this->loadTemplate('promocion');

        /* si viene imagen o no */
        $htmlAccionImagen1 = $this->htmlSubirImagen($promocion->id_promocion, 'foto1');
        $fecha = $promocion->fecha_creacion;

        $dir = Variables::URL.'uploads/'.$carpeta.'/promociones/'.date('Y', strtotime($fecha)).'/'.date('m', strtotime($fecha)).'/';
        if ($promocion->foto1 != '') {
            $imagen1 = $dir.$promocion->foto1;
            $htmlAccionImagen1 = $this->htmlimagen($imagen1, $promocion->id_promocion, 'foto1');
        }

        /* si viene imagen o no */
        $htmlAccionImagen2 = $this->htmlSubirImagen($promocion->id_promocion, 'foto2');

        if ($promocion->foto2 != '') {
            $imagen2 = $dir.$promocion->foto2;
            $htmlAccionImagen2 = $this->htmlimagen($imagen2, $promocion->id_promocion, 'foto2');
        }

        $tmpl->addSearchReplace('{#ID_PROMOCION#}', $promocion->id_promocion);
        $tmpl->addSearchReplace('{#LINK#}', $promocion->link);
        $tmpl->addSearchReplace('{#HTML_ACCION_IMAGEN_1#}', $htmlAccionImagen1);
        $tmpl->addSearchReplace('{#HTML_ACCION_IMAGEN_2#}', $htmlAccionImagen2);

        return $tmpl->getHtml();
    }

    /**
     * @param $imagen
     * @param $idPromocion
     * @param $campo
     * @return mixed
     */
    public function htmlimagen($imagen, $idPromocion, $campo)
    {
        $tmpl = $this->loadTemplate('imagen');

        $tmpl->addSearchReplace('{#ID_PROMOCION#}', $idPromocion);
        $tmpl->addSearchReplace('{#IMAGEN#}', $imagen);
        $tmpl->addSearchReplace('{#CAMPO#}', $campo);

        return $tmpl->getHtml();
    }

    /**
     * @param $idPromocion
     * @param $imagenSubir
     * @return mixed
     */
    public function htmlSubirImagen($idPromocion, $imagenSubir)
    {
        $tmpl = $this->loadTemplate('subir_imagen');

        $tmpl->addSearchReplace('{#ID_PROMOCION#}', $idPromocion);
        $tmpl->addSearchReplace('{#IMAGEN#}', $imagenSubir);

        return $tmpl->getHtml();
    }
}
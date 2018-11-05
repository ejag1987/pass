<?php

namespace Neorentas\Administrador\Modules\Galeria\Views;

use Neorentas\Administrador\System\Variables;
use ZCode\Lighting\View\BaseView;

class GaleriaView extends BaseView
{
    public function cargarGaleria($idSitio, $pagina, $carpeta, $galerias)
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

        $this->addCss('galeria');
        $this->addJs('galeria');

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

        $htmlGaleria = '';
        if ($galerias) {
            foreach ($galerias as $galeria) {
                $htmlGaleria .= $this->cargarGaleriaItem($galeria, $carpeta);
            }
        }

        $tmpl->addSearchReplace('{#ID_SITIO#}', $idSitio);
        $tmpl->addSearchReplace('{#ID_PAGINA#}', $idPagina);
        $tmpl->addSearchReplace('{#TITULO1#}', $titulo1);
        $tmpl->addSearchReplace('{#TITULO2#}', $titulo2);
        $tmpl->addSearchReplace('{#HTML_FONDO#}', $htmlAccionFondo);
        $tmpl->addSearchReplace('{#GALERIAS#}', $htmlGaleria);

        return $tmpl->getHtml();
    }

    public function htmlSubirImagen($idEvento, $imagenSubir)
    {
        $tmpl = $this->loadTemplate('subir_imagen');

        $tmpl->addSearchReplace('{#ID_EVENTO#}', $idEvento);
        $tmpl->addSearchReplace('{#IMAGEN#}', $imagenSubir);

        return $tmpl->getHtml();
    }

    public function htmlimagen($imagen, $idItem, $campo)
    {
        $tmpl = $this->loadTemplate('imagen');

        $clase = '';
        if ($campo != 'fondo') {
            $clase = 'column is-2';
        }

        $tmpl->addSearchReplace('{#ID_ITEM#}', $idItem);
        $tmpl->addSearchReplace('{#IMAGEN#}', $imagen);
        $tmpl->addSearchReplace('{#CAMPO#}', $campo);
        $tmpl->addSearchReplace('{#CLASE_COLUMNA#}', $clase);

        return $tmpl->getHtml();
    }

    public function cargarGaleriaItem($galeria, $carpeta)
    {
        $tmpl = $this->loadTemplate('galeria');

        $imagen = '';
        if ($galeria->foto) {
            $dir = Variables::URL.'uploads/'.$carpeta.'/galerias/galeria-'.$galeria->id_galeria.'/';

            $imagen = '<img src="'.$dir.$galeria->foto.'" />';
        }

        $tmpl->addSearchReplace('{#ID_GALERIA#}', $galeria->id_galeria);
        $tmpl->addSearchReplace('{#IMAGEN_GALERIA#}', $imagen);
        $tmpl->addSearchReplace('{#TITULO#}', $galeria->titulo);
        $tmpl->addSearchReplace('{#DESCRIPCION#}', $galeria->descripcion);

        return $tmpl->getHtml();
    }

    public function cargarEditar($idSitio, $carpeta, $galeria, $imagenes)
    {
        $tmpl = $this->loadTemplate('editar');

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

        $this->addCss('galeria');
        $this->addCss('editar');
        $this->addJs('editar');

        $idGaleria    = 0;
        $titulo       = '';
        $descripcion  = '';
        $htmlImagenes = '';

        if ($galeria) {
            $idGaleria   = $galeria->id_galeria;
            $titulo      = $galeria->titulo;
            $descripcion = $galeria->descripcion;

            if ($imagenes) {
                foreach ($imagenes as $imagen) {
                    $dir = Variables::URL.'uploads/'.$carpeta.'/galerias/galeria-'.$idGaleria.'/'.$imagen->foto;

                    $htmlImagenes .= $this->htmlimagen($dir, $imagen->id_galeria_item, 'foto');
                }
            }
        }

        $tmpl->addSearchReplace('{#ID_SITIO#}', $idSitio);
        $tmpl->addSearchReplace('{#ID_GALERIA#}', $idGaleria);
        $tmpl->addSearchReplace('{#TITULO#}', $titulo);
        $tmpl->addSearchReplace('{#DESCRIPCION#}', $descripcion);
        $tmpl->addSearchReplace('{#IMAGEN#}', 'foto');

        $tmpl->addSearchReplace('{#IMAGENES#}', $htmlImagenes);

        return $tmpl->getHtml();
    }

}
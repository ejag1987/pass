<?php

namespace Neorentas\Administrador\Modules\Evento\Views;

use Neorentas\Administrador\System\Variables;
use ZCode\Lighting\View\BaseView;

class EventoView extends BaseView
{
    public function cargarEventos($idSitio, $pagina, $carpeta, $eventos)
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

        $this->addGlobalJs('inputmask');
        $this->addGlobalJs('jquery.inputmask');

        $this->addGlobalCss('jquery-confirm');
        $this->addGlobalJs('jquery-confirm');

        $this->addGlobalJs('validacion');

        $this->addCss('evento');
        $this->addJs('evento');

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

        $htmlEventos = '';
        if ($eventos) {
            foreach ($eventos as $evento) {
                $htmlEventos .= $this->cargarHtmlEvento($evento, $carpeta);
            }
        }

        $tmpl->addSearchReplace('{#ID_SITIO#}', $idSitio);
        $tmpl->addSearchReplace('{#ID_PAGINA#}', $idPagina);
        $tmpl->addSearchReplace('{#TITULO1#}', $titulo1);
        $tmpl->addSearchReplace('{#TITULO2#}', $titulo2);
        $tmpl->addSearchReplace('{#HTML_FONDO#}', $htmlAccionFondo);
        $tmpl->addSearchReplace('{#EVENTOS#}', $htmlEventos);

        return $tmpl->getHtml();
    }

    public function htmlSubirImagen($idEvento, $imagenSubir)
    {
        $tmpl = $this->loadTemplate('subir_imagen');

        $tmpl->addSearchReplace('{#ID_EVENTO#}', $idEvento);
        $tmpl->addSearchReplace('{#IMAGEN#}', $imagenSubir);

        return $tmpl->getHtml();
    }

    public function htmlimagen($imagen, $idEvento, $campo)
    {
        $tmpl = $this->loadTemplate('imagen');

        $tmpl->addSearchReplace('{#ID_EVENTO#}', $idEvento);
        $tmpl->addSearchReplace('{#IMAGEN#}', $imagen);
        $tmpl->addSearchReplace('{#CAMPO#}', $campo);

        return $tmpl->getHtml();
    }

    public function cargarHtmlEvento($evento, $carpeta)
    {
        $tmpl = $this->loadTemplate('evento');

        /* si viene imagen o no */
        $htmlAccionImagen = $this->htmlSubirImagen($evento->id_evento, 'foto');
        $fecha = $evento->fecha_creacion;

        $dir = Variables::URL.'uploads/'.$carpeta.'/eventos/'.date('Y', strtotime($fecha)).'/'.date('m', strtotime($fecha)).'/';
        if ($evento->foto != '') {
            $imagen1 = $dir.$evento->foto;
            $htmlAccionImagen = $this->htmlimagen($imagen1, $evento->id_evento, 'foto');
        }

        $fechaEvento = '';
        if ($evento->fecha_evento != '') {
            $fechaEvento = date('d-m-Y', strtotime($evento->fecha_evento));
        }

        $tmpl->addSearchReplace('{#ID_EVENTO#}', $evento->id_evento);
        $tmpl->addSearchReplace('{#HTML_ACCION_IMAGEN#}', $htmlAccionImagen);
        $tmpl->addSearchReplace('{#TITULO#}', htmlentities($evento->titulo));
        $tmpl->addSearchReplace('{#ALIAS#}', $evento->alias);
        $tmpl->addSearchReplace('{#DESCRIPCION#}', $evento->descripcion);
        $tmpl->addSearchReplace('{#CONTENIDO#}', $evento->contenido);
        $tmpl->addSearchReplace('{#FECHA#}', $fechaEvento);
        $tmpl->addSearchReplace('{#HORARIO#}', $evento->horario);

        return $tmpl->getHtml();
    }
}
<?php

namespace Neorentas\Administrador\Modules\Comercial\Views;

use Neorentas\Administrador\System\FuncionesGlobales;
use Neorentas\Administrador\System\Variables;
use ZCode\Lighting\View\BaseView;

class ComercialView extends BaseView
{
    public function cargarComercial($idSitio, $datos, $pagina, $servicios, $serviciosSitio)
    {
        $tmpl = $this->loadTemplate('comercial');

        $this->addGlobalCss('upload');
        $this->addGlobalJs('jquery.ui.widget');
        $this->addGlobalJs('load-image.all.min');
        $this->addGlobalJs('canvas-to-blob.min');
        $this->addGlobalJs('jquery.iframe-transport');
        $this->addGlobalJs('jquery.fileupload');
        $this->addGlobalJs('jquery.fileupload-process');
        $this->addGlobalJs('jquery.fileupload-image');
        $this->addGlobalJs('jquery.fileupload-validate');

        $this->addGlobalJs('validacion');

        $this->addGlobalCss('jquery-confirm');
        $this->addGlobalJs('jquery-confirm');

        $this->addCss('comercial');
        $this->addJs('comercial');

        $idPagina = 0;
        $titulo1  = '';
        $titulo2  = '';

        $texto            = '';
        $terreno          = '';
        $contruida        = '';
        $arrendable       = '';
        $estacionamientoz = '';
        $fechaApertura    = '';
        $horario          = '';
        $pieFoto1         = '';
        $pieFoto2         = '';

        /* si viene imagen o no */
        $htmlAccionImagen = $this->htmlSubirImagen('logo');

        /* si viene fondo o no */
        $htmlAccionFondo = $this->htmlSubirImagen('fondos');

        /* si viene render 1 o no */
        $htmlAccionRender1 = $this->htmlSubirImagen('render1');

        /* si viene render 2 o no */
        $htmlAccionRender2 = $this->htmlSubirImagen('render2');

        if ($pagina) {
            $idPagina = $pagina->id_pagina;
            $titulo1  = $pagina->titulo1;
            $titulo2  = $pagina->titulo2;

            $fondo   = $pagina->fondo;

            if ($fondo != '') {
                $dir  = Variables::URL.'uploads/'.$datos->carpeta.'/fondos';
                $imagenFondo = $dir.'/'.$pagina->fondo;

                $htmlAccionFondo = $this->htmlImagen('fondos', $imagenFondo);
            }
        }

        if ($datos) {
            $texto            = $datos->informacion;
            $terreno          = $datos->terreno;
            $contruida        = $datos->construida;
            $arrendable       = $datos->arrendable;
            $estacionamientoz = $datos->estacionamientos;
            $fechaApertura    = $datos->fecha_apertura;
            $horario          = $datos->horario;
            $carpeta          = $datos->carpeta;

            if ($datos->logo != '') {
                $imagen = Variables::URL.'uploads/'.$carpeta.'/imagenes/'.$datos->logo;
                $htmlAccionImagen = $this->htmlImagen('logo', $imagen);
            }

            if ($datos->render1 != '') {
                $imagen = Variables::URL.'uploads/'.$carpeta.'/imagenes/'.$datos->render1;
                $htmlAccionRender1 = $this->htmlImagen('render1', $imagen);
            }

            if ($datos->render2 != '') {
                $imagen = Variables::URL.'uploads/'.$carpeta.'/imagenes/'.$datos->render2;
                $htmlAccionRender2 = $this->htmlImagen('render2', $imagen);
            }

            $pieFoto1 = $datos->pie_foto1;
            $pieFoto2 = $datos->pie_foto2;
        }

        $serviciosHtml = $this->cargarServicios($servicios, $serviciosSitio);

        $tmpl->addSearchReplace('{#ID_SITIO#}', $idSitio);
        $tmpl->addSearchReplace('{#ID_PAGINA#}', $idPagina);
        $tmpl->addSearchReplace('{#TITULO1#}', $titulo1);
        $tmpl->addSearchReplace('{#TITULO2#}', $titulo2);
        $tmpl->addSearchReplace('{#TEXTO#}', $texto);
        $tmpl->addSearchReplace('{#TERRENO#}', $terreno);
        $tmpl->addSearchReplace('{#CONTRUIDA#}', $contruida);
        $tmpl->addSearchReplace('{#ARRENDABLE#}', $arrendable);
        $tmpl->addSearchReplace('{#ESTACIONAMIENTO#}', $estacionamientoz);
        $tmpl->addSearchReplace('{#FECHA_APERTURA#}', $fechaApertura);
        $tmpl->addSearchReplace('{#HORARIO_TIENDAS#}', $horario);

        $tmpl->addSearchReplace('{#HTML_IMAGEN#}', $htmlAccionImagen);
        $tmpl->addSearchReplace('{#HTML_FONDO#}', $htmlAccionFondo);
        $tmpl->addSearchReplace('{#HTML_RENDER_1#}', $htmlAccionRender1);
        $tmpl->addSearchReplace('{#PIE_FOTO1#}', $pieFoto1);
        $tmpl->addSearchReplace('{#HTML_RENDER_2#}', $htmlAccionRender2);
        $tmpl->addSearchReplace('{#PIE_FOTO2#}', $pieFoto2);

        $tmpl->addSearchReplace('{#SERVICIOS#}', $serviciosHtml);

        return $tmpl->getHtml();
    }

    public function htmlImagen($tipo, $imagen)
    {
        $tmpl = $this->loadTemplate('imagen');

        $tmpl->addSearchReplace('{#IMAGEN#}', $imagen);
        $tmpl->addSearchReplace('{#TIPO#}', $tipo);

        return $tmpl->getHtml();
    }

    public function htmlSubirImagen($idTipo)
    {
        $tmpl = $this->loadTemplate('subir_imagen');

        $tmpl->addSearchReplace('{#ID#}', $idTipo);

        return $tmpl->getHtml();
    }

    public function cargarServicios($servicios, $serviciosSitio)
    {
        $html = '';

        if ($serviciosSitio) {
            $serviciosSitio = FuncionesGlobales::objectToArray($serviciosSitio);
        }


        if ($servicios) {
            foreach ($servicios as $servicio) {
                $tmpl = $this->loadTemplate('servicios');

                $check = '';

                if ($serviciosSitio) {
                    if (in_array($servicio->id_servicio, $serviciosSitio)) {
                        $check = 'checked';
                    }
                }


                $tmpl->addSearchReplace('{#ID_SERVICIO#}', $servicio->id_servicio);
                $tmpl->addSearchReplace('{#NOMBRE#}', $servicio->nombre);
                $tmpl->addSearchReplace('{#CHECKED#}', $check);

                $html .= $tmpl->getHtml();
            }
        }

        return $html;
    }
}

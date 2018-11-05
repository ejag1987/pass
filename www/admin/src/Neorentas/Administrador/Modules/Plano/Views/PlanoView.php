<?php

namespace Neorentas\Administrador\Modules\Plano\Views;

use Neorentas\Administrador\System\FuncionesGlobales;
use Neorentas\Administrador\System\Variables;
use ZCode\Lighting\View\BaseView;

class PlanoView extends BaseView
{
    /**
     * @param $sitio
     * @param $planos
     * @return mixed
     */
    public function cargarPlanos($sitio, $planos)
    {
        $tmpl = $this->loadTemplate('planos');

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

        //$this->addCss('planos');
        $this->addJs('planos');

        $htmlPlanos = '';

        if ($planos) {
            foreach ($planos as $plano) {
                $htmlPlanos .= $this->cargarHtmlPlanos($sitio->carpeta, $plano);
            }
        }

        $tmpl->addSearchReplace('{#ID_SITIO#}', $sitio->id_sitio);
        $tmpl->addSearchReplace('{#PLANOS#}', $htmlPlanos);

        return $tmpl->getHtml();
    }

    /**
     * @param $carpeta
     * @param $plano
     * @return mixed
     */
    public function cargarHtmlPlanos($carpeta, $plano)
    {
        $tmpl = $this->loadTemplate('item_plano');

        /* si viene plano o no */
        $htmlPlano = $this->htmlSubirImagen($plano->id_plano);

        if ($plano->imagen != '') {
            $imagenPlano = Variables::URL.'uploads/'.$carpeta.'/mapa/'.$plano->imagen;
            $htmlPlano = $this->htmlImagen($plano->id_plano, $imagenPlano);
        }

        $tmpl->addSearchReplace('{#HTML_PLANO#}', $htmlPlano);
        $tmpl->addSearchReplace('{#NOMBRE#}', $plano->nombre);
        $tmpl->addSearchReplace('{#ID_PLANO#}', $plano->id_plano);
        $tmpl->addSearchReplace('{#PIE_IMAGEN#}', $plano->pie_imagen);
        $tmpl->addSearchReplace('{#FECHA_ACTUALIZACION#}', $plano->fecha_actualizacion);

        return $tmpl->getHtml();
    }

    /**
     * @param $idPlano
     * @param $imagen
     * @return mixed
     */
    public function htmlImagen($idPlano, $imagen)
    {
        $tmpl = $this->loadTemplate('imagen');

        $tmpl->addSearchReplace('{#IMAGEN#}', $imagen);
        $tmpl->addSearchReplace('{#ID_PLANO#}', $idPlano);

        return $tmpl->getHtml();
    }

    /**
     * @param $idPlano
     * @return mixed
     */
    public function htmlSubirImagen($idPlano)
    {
        $tmpl = $this->loadTemplate('subir_imagen');

        $tmpl->addSearchReplace('{#ID_PLANO#}', $idPlano);

        return $tmpl->getHtml();
    }

    /**
     * @param $idPlano
     * @param $imagenPlano
     * @param $locales
     * @param $carpeta
     * @param $terminaciones
     * @return mixed
     */
    public function cargarLocales($idPlano, $imagenPlano, $locales, $carpeta, $terminaciones)
    {
        $tmpl = $this->loadTemplate('locales');

        $this->addGlobalCss('bootstrap-switch');
        $this->addGlobalCss('switch');
        $this->addGlobalCss('checkbox');
        $this->addCss('plano');

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

        $this->addGlobalJs('validacion');

        $this->addGlobalJs('bootstrap-switch.min');
        $this->addJs('locales');

        $htmlLocales = '';

        if ($locales) {
            foreach ( $locales as $local) {
                $htmlLocales .= $this->cargarLocal($local, $carpeta, $terminaciones);
            }

        }

        $tmpl->addSearchReplace('{#ID_PLANO#}', $idPlano);
        $tmpl->addSearchReplace('{#IMAGEN#}', $imagenPlano);
        $tmpl->addSearchReplace('{#LOCAL#}', $htmlLocales);

        return $tmpl->getHtml();
    }

    /**
     * @param $local
     * @param $carpeta
     * @param $terminaciones
     * @return mixed
     */
    public function cargarLocal($local, $carpeta, $terminaciones)
    {
        $tmpl = $this->loadTemplate('local');

        $checkboxDisponible = '';
        $checkboxReservado = '';

        $htmlDatos = $this->localDisponible($local, $carpeta, $terminaciones);

        $claseDisponibilidad = '';

        if ($local->reservado) {
            $checkboxReservado = 'checked="checked"';
        }

        if (!$local->disponible) {
            $checkboxDisponible = 'checked="checked"';

            $htmlDatos = $this->localArrendado($local, $carpeta);

            $claseDisponibilidad = 'hide';
        }

        $tmpl->addSearchReplace('{#ID_LOCAL#}', $local->id_local);
        $tmpl->addSearchReplace('{#NUMERO#}', $local->numero);
        $tmpl->addSearchReplace('{#CHECKED_DISPONIBLE#}', $checkboxDisponible);
        $tmpl->addSearchReplace('{#CHECKED_RESERVADO#}', $checkboxReservado);
        $tmpl->addSearchReplace('{#ESTADO_LOCAL#}', $claseDisponibilidad);

        $tmpl->addSearchReplace('{#DESCRIPCION#}', $htmlDatos);


        return $tmpl->getHtml();
    }

    /**
     * @param $local
     * @param $carpeta
     * @param $terminaciones
     * @return mixed
     */
    public function localDisponible($local, $carpeta, $terminaciones)
    {
        $tmpl = $this->loadTemplate('disponible');

        /* si viene plano o no */
        $htmlLocal = $this->htmlSubirImagen($local->id_local);

        if ($local->imagen_ficha != '') {
            $imagenLocal = Variables::URL.'uploads/'.$carpeta.'/mapa/'.$local->imagen_ficha;
            $htmlLocal = $this->htmlImagen($local->id_local, $imagenLocal);
        }

        $htmlTerminaciones = '';

        //var_dump($local);

        if ($terminaciones) {
            foreach ($terminaciones as $terminacion) {
                $tmplTerminaciones = $this->loadTemplate('terminacion');

                //var_dump($terminacion);
                //var_dump($local->terminaciones);

                $localTerminacion = FuncionesGlobales::buscarValorObjeto($local->terminaciones, $terminacion->id_terminacion, 'id_terminacion');

                $valor = '';
                if ($localTerminacion['existe']) {
                    $valor = $localTerminacion['item']->valor;
                }

                $tmplTerminaciones->addSearchReplace('{#ID_TERMINACION#}', $terminacion->id_terminacion);
                $tmplTerminaciones->addSearchReplace('{#NOMBRE#}', $terminacion->descripcion);
                $tmplTerminaciones->addSearchReplace('{#NUMERO#}', $terminacion->numero);
                $tmplTerminaciones->addSearchReplace('{#VALOR#}', $valor);

                $htmlTerminaciones .= $tmplTerminaciones->getHtml();

                $tmplTerminaciones->resetTemplate();
            }
        }

        $tmpl->addSearchReplace('{#HTML_LOCAL#}', $htmlLocal);

        $tmpl->addSearchReplace('{#ID_LOCAL#}', $local->id_local);
        $tmpl->addSearchReplace('{#METROS#}', $local->metros);
        $tmpl->addSearchReplace('{#INSTALACIONES#}', $local->instalaciones);
        $tmpl->addSearchReplace('{#NOTA#}', $local->nota);
        $tmpl->addSearchReplace('{#TERMINACIONES#}', $htmlTerminaciones);
        $tmpl->addSearchReplace('{#TIPO_IMAGEN#}', 'plano');

        $tmpl->addSearchReplace('{#PIE_IMAGEN#}', $local->pie_ficha);
        $tmpl->addSearchReplace('{#FECHA_ACTUALIZACION#}', $local->fecha_actualizacion);


        return $tmpl->getHtml();
    }

    /**
     * @param $local
     * @param $carpeta
     * @return mixed
     */
    public function localArrendado($local, $carpeta)
    {
        $tmpl = $this->loadTemplate('arrendado');

        /* si viene plano o no */
        $htmlLocal = $this->htmlSubirImagen($local->id_local);

        if ($local->logo != '') {
            $imagenLocal = Variables::URL.'uploads/'.$carpeta.'/mapa/'.$local->logo;
            $htmlLocal = $this->htmlImagen($local->id_local, $imagenLocal);
        }

        $tmpl->addSearchReplace('{#HTML_LOCAL#}', $htmlLocal);

        $tmpl->addSearchReplace('{#ID_LOCAL#}', $local->id_local);
        $tmpl->addSearchReplace('{#DESCRIPCION#}', $local->descripcion);
        $tmpl->addSearchReplace('{#TIPO_IMAGEN#}', 'logo');

        return $tmpl->getHtml();
    }


}
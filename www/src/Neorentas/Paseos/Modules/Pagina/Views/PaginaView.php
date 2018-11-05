<?php

namespace Neorentas\Paseos\Modules\Pagina\Views;

use Neorentas\Paseos\System\FuncionesGlobales;
use Neorentas\Paseos\System\Variables;
use ZCode\Lighting\View\BaseView;

class PaginaView extends BaseView
{
    /**
     * @param $pagina
     * @param $carpeta
     * @param $htmlPagina
     * @return mixed
     */
    public function cargarPagina($pagina, $carpeta, $htmlPagina)
    {
        $tmpl = $this->loadTemplate('pagina');

        $this->addGlobalCss('modal');
        $this->addGlobalJs('util');
        $this->addGlobalJs('modal');

        $this->addGlobalJs('validacion');

        $this->addCss('pagina');
        $this->addJs('pagina');

        $fondoPagina = '';
        $tituloPagina1  = $pagina->titulo1;
        $tituloPagina2  = $pagina->titulo2;

        if ($carpeta != '') {
            $fondo = Variables::URL.'uploads/'.$carpeta.'/fondos/'.$pagina->fondo;
            $fondoPagina = 'url('.$fondo.')';
        }

        $tmpl->addSearchReplace('{#ID_SITIO#}', $pagina->id_sitio);
        $tmpl->addSearchReplace('{#ID_PAGINA#}', $pagina->id_pagina);
        $tmpl->addSearchReplace('{#FONDO#}', $fondoPagina);
        $tmpl->addSearchReplace('{#TITULO1#}', $tituloPagina1);
        $tmpl->addSearchReplace('{#TITULO2#}', $tituloPagina2);
        $tmpl->addSearchReplace('{#HTML_PAGINA#}', $htmlPagina);

        return $tmpl->getHtml();
    }

    /**
     * @param $sitio
     * @param $imagenes
     * @param $texto
     * @return mixed
     */
    public function cargarPaginaGeneral($sitio, $imagenes, $texto)
    {
        $tmpl = $this->loadTemplate('informacion_general');

        $paginaTexto   = $texto;
        $imagenesHtml  = '';

        $carpeta = $sitio->carpeta;
        $nombre  = $sitio->nombre;

        if ($imagenes) {
            foreach ($imagenes as $imagen) {
                if ($carpeta != '' && $imagen->archivo != '') {
                    $imagenPagina  = Variables::URL.'uploads/'.$carpeta.'/imagenes/'.$imagen->archivo;
                    $imagenesHtml .= $this->cargarImagen($imagenPagina, $nombre, '');
                }
            }
        }

        $tmpl->addSearchReplace('{#IMAGENES#}', $imagenesHtml);
        $tmpl->addSearchReplace('{#TEXTO#}', $paginaTexto);

        return $tmpl->getHtml();
    }

    /**
     * @param $imagenPagina
     * @param $nombre
     * @return mixed
     */
    private function cargarImagen($imagenPagina, $nombre, $pieFoto)
    {
        $tmpl = $this->loadTemplate('imagen');

        $tmpl->addSearchReplace('{#IMAGEN#}', $imagenPagina);
        $tmpl->addSearchReplace('{#NOMBRE_SITIO#}', $nombre);
        $tmpl->addSearchReplace('{#PIE_FOTO#}', $pieFoto);

        return $tmpl->getHtml();

    }

    /**
     * @param $pagina
     * @param $contacto
     * @param $token
     * @return mixed
     */
    public function cargarPaginaContacto($pagina, $contacto, $token)
    {
        $tmpl = $this->loadTemplate('contacto');

        $this->addGlobalJs('validacion');
        $this->addGlobalJs('jquery.ba-throttle-debounce.min');

        $this->addCss('pagina');

        $tituloPagina1  = '';
        $tituloPagina2  = '';
        $fondoPagina    = '';

        $direccion      = '';
        $claseDireccion = 'hide';
        $telefono       = '';
        $telefonoMarcar = '';
        $claseTelefono  = 'hide';
        $googleMaps     = '';
        $claseMapa      = 'hide';

        // revisar para limpiar el codigo

        if ($pagina) {
            $tituloPagina1  = $pagina->titulo1;
            $tituloPagina2  = $pagina->titulo2;

            if ($contacto->carpeta != '') {
                $fondo = Variables::URL.'uploads/'.$contacto->carpeta.'/fondos/'.$pagina->fondo;

                $fondoPagina = 'url('.$fondo.')';
            }
        }

        if ($contacto) {
            $direccion      = $contacto->direccion;
            if ($direccion != '') {
                $claseDireccion = '';
            }

            $telefono       = $contacto->telefono;
            if ($telefono != '') {
                $unwanted_array = [' '=>'', '('=>'', ')'=>''];
                $telefonoMarcar = '+'.strtr( $telefono, $unwanted_array );
                $claseTelefono  = '';
            }

            $googleMaps = $contacto->google_map;
            if ($googleMaps != '') {
                $claseMapa = '';
            }
        }

        $tmpl->addSearchReplace('{#FONDO#}', $fondoPagina);
        $tmpl->addSearchReplace('{#TITULO1#}', $tituloPagina1);
        $tmpl->addSearchReplace('{#TITULO2#}', $tituloPagina2);

        $tmpl->addSearchReplace('{#DIRECCION#}', $direccion);
        $tmpl->addSearchReplace('{#CLASE_DIRECCION#}', $claseDireccion);
        $tmpl->addSearchReplace('{#TELEFONO#}', $telefono);
        $tmpl->addSearchReplace('{#TELEFONO_MARCA#}', $telefonoMarcar);
        $tmpl->addSearchReplace('{#CLASE_TELEFONO#}', $claseTelefono);
        $tmpl->addSearchReplace('{#GOOGLE_MAPS#}', $googleMaps);
        $tmpl->addSearchReplace('{#CLASE_MAPA#}', $claseMapa);

        $tmpl->addSearchReplace('{#TOKEN#}', $token);

        return $tmpl->getHtml();
    }

    /**
     * @param $alias
     * @param $comercial
     * @param $menuPaseos
     * @param $usuario
     * @param $servicios
     * @param $existePlano
     * @return mixed
     */
    public function cargarPaginaComercial($alias, $comercial, $menuPaseos, $usuario, $servicios, $existePlano)
    {
        $tmpl = $this->loadTemplate('informacion');

        $this->addGlobalCss('font-awesome');

        //$this->addCss('pagina');
        //$this->addGlobalCss('modal');

        $htmlMenuLateral = '';

        $logo             = '';
        $nombre           = '';
        $informacion      = '';
        $direccion        = '';
        $terreno          = '';
        $contruida        = '';
        $arrendable       = '';
        $estacionamientos = '';
        $fechaApertura    = '';
        $link             = '';
        $url              = '';
        $correo           = '';
        $imagenesHtml     = '';
        $boton            = '';
        $claseServicios   = 'hide';
        $htmlServicios    = '';
        $mapa             = '';

        if ($menuPaseos) {
            foreach ($menuPaseos as $paseo) {
                $htmlMenuLateral .= $this->cargarMenuLateral($paseo, $alias);
            }
        }

        if ($comercial) {
            $nombre = $comercial->nombre;
            $logo = Variables::URL.'uploads/'.$comercial->carpeta.'/imagenes/'.$comercial->logo;
            $informacion = $comercial->informacion;
            $direccion   = $comercial->direccion;
            $mapa        = $comercial->google_map;

            if ($comercial->terreno != '') {
                $terreno = "Superficie del terreno: $comercial->terreno m2<br>";
            }

            if ($comercial->construida != '') {
                $contruida = "Superficie construida: $comercial->construida m2<br>";
            }

            if ($comercial->arrendable != '') {
                $arrendable = "Superficie arrendable: $comercial->arrendable m2";
            }

            $estacionamientos = $comercial->estacionamientos;

            if ($comercial->fecha_apertura != '') {
                $fechaApertura = "Fecha de apertura: ".$comercial->fecha_apertura;
            }
            $link = $comercial->link;

            $url = str_replace('http://', '', $link);
            $lastChar = (substr($url, -1));
            if ($lastChar == '/') {
                $url = substr(trim($url), 0, -1);
            }
            $correo = $comercial->correo;

            if ($comercial->render1 != '') {
                $imagen = Variables::URL.'uploads/'.$comercial->carpeta.'/imagenes/'.$comercial->render1;
                $imagenesHtml .= $this->cargarImagen($imagen, $comercial->nombre, $comercial->pie_foto1);
            }

            if ($comercial->render2 != '') {
                $imagen = Variables::URL.'uploads/'.$comercial->carpeta.'/imagenes/'.$comercial->render2;
                $imagenesHtml .= $this->cargarImagen($imagen, $comercial->nombre, $comercial->pie_foto2);
            }

            $accionBoton = Variables::URL."pagina/$alias/mapa";
            $boton = '<a class="button is-large is-info log-large" href="'.$accionBoton.'">' .
                    '<img src="'.Variables::URL.'resources/img/lupa.svg" class="icono-boton">' .
                    '<span class="btn-log">VER PLANO '.strtoupper($nombre).'</span>' .
                '</a>';
        }

        if (!$usuario) {
            $this->addGlobalJs('inputmask');
            $this->addGlobalJs('jquery.inputmask');

            $this->addGlobalJs('validacion');
            $this->addGlobalJs('jquery.ba-throttle-debounce.min');
            $this->addJs('login');

            $accionBoton = 'javascript: cargarLogin();';
            $boton = '<a class="button is-large is-info" href="'.$accionBoton.'"><img src="'.Variables::URL.'resources/img/lupa.svg" class="icono-boton">VER MÁS INFORMACIÓN</a>';

        }

        if (!$existePlano) {
            $boton = '';
        }

        if ($servicios) {
            $claseServicios = '';
            $htmlServicios = $this->cargarHtmlServicios($servicios);
        }


        $tmpl->addSearchReplace('{#MENU_PASEOS#}', $htmlMenuLateral);

        $tmpl->addSearchReplace('{#LOGO#}', $logo);
        $tmpl->addSearchReplace('{#NOMBRE#}', $nombre);
        $tmpl->addSearchReplace('{#INFORMACION#}', $informacion);
        $tmpl->addSearchReplace('{#DIRECCION#}', $direccion);
        $tmpl->addSearchReplace('{#TERRENO#}', $terreno);
        $tmpl->addSearchReplace('{#CONTRUIDA#}', $contruida);
        $tmpl->addSearchReplace('{#ARRENDABLE#}', $arrendable);
        $tmpl->addSearchReplace('{#ESTACIONAMIENTO#}', $estacionamientos);
        $tmpl->addSearchReplace('{#FECHA_APERTURA#}', $fechaApertura);
        $tmpl->addSearchReplace('{#LINK#}', $link);
        $tmpl->addSearchReplace('{#URL#}', $url);
        $tmpl->addSearchReplace('{#CORREO#}', $correo);
        $tmpl->addSearchReplace('{#IMAGENES#}', $imagenesHtml);
        $tmpl->addSearchReplace('{#GOOGLE_MAPS#}', $mapa);

        $tmpl->addSearchReplace('{#BOTON#}', $boton);
        $tmpl->addSearchReplace('{#CLASE_SERVICIOS#}', $claseServicios);
        $tmpl->addSearchReplace('{#SERVICIOS#}', $htmlServicios);

        return $tmpl->getHtml();
    }

    /**
     * @param $servicios
     * @return string
     */
    public function cargarHtmlServicios($servicios)
    {
        $html = '';

        if ($servicios) {
            foreach ($servicios as $servicio) {
                $tmpl = $this->loadTemplate('servicios');

                $tmpl->addSearchReplace('{#ICONO#}', Variables::URL.'/uploads/servicios/'.$servicio->icono);
                $tmpl->addSearchReplace('{#NOMBRE#}', $servicio->nombre);

                $html .= $tmpl->getHtml();
            }
        }

        return $html;
    }

    /**
     * @param $alias
     * @param $menuPaseos
     * @param $planos
     * @return mixed
     */
    public function cargarPaginaPlano($alias, $menuPaseos, $planos)
    {
        $tmpl = $this->loadTemplate('mapa');

        $this->addGlobalCss('leaflet');
        //$this->addGlobalCss('modal');
        $this->addCss('pagina');

        $this->addGlobalJs('leaflet');
        $this->addJs('mapa');

        $htmlMenuLateral    = '';

        $htmlMenuPlanos     = '';
        $idPlanoDefecto     = '0';
        $piePlano           = '';
        $fechaActualizacion = '';

        if ($menuPaseos) {
            foreach ($menuPaseos as $paseo) {
                $htmlMenuLateral .= $this->cargarMenuLateral($paseo, $alias);
            }
        }

        if ($planos) {
            $tmplMenuPlano = $this->loadTemplate('item_menu_plano');
            $totalPlanos   = sizeof($planos);

            for ($i = 0; $i < $totalPlanos; $i++) {
                $clasePlano    = '';

                if ($i === 0) {
                    $idPlanoDefecto = $planos[$i]->id_plano;
                    $clasePlano = 'is-active';

                    $piePlano           = $planos[$i]->pie_imagen;
                    $fechaActualizacion = $planos[$i]->fecha_actualizacion;
                }

                $tmplMenuPlano->addSearchReplace('{#NOMBRE_PLANO#}', $planos[$i]->nombre);
                $tmplMenuPlano->addSearchReplace('{#ID_PLANO#}', $planos[$i]->id_plano);
                $tmplMenuPlano->addSearchReplace('{#CLASE_PLANO#}', $clasePlano);

                $htmlMenuPlanos .= $tmplMenuPlano->getHtml();
                $tmplMenuPlano->resetTemplate();
            }
        }

        $tmpl->addSearchReplace('{#MENU_PASEOS#}', $htmlMenuLateral);
        $tmpl->addSearchReplace('{#ALIAS#}', $alias);

        $tmpl->addSearchReplace('{#MENU_PLANOS#}', $htmlMenuPlanos);
        $tmpl->addSearchReplace('{#ID_PLANO_DEFECTO#}', $idPlanoDefecto);
        $tmpl->addSearchReplace('{#PIE_PLANO#}', $piePlano);
        $tmpl->addSearchReplace('{#FECHA_ACTUALIZACION#}', $fechaActualizacion);

        return $tmpl->getHtml();
    }

    /**
     * @param $paseo
     * @param $alias
     * @return mixed
     */
    public function cargarMenuLateral($paseo, $alias)
    {
        $tmpl = $this->loadTemplate('menu_paseos');

        $claseActivo = '';

        if ($paseo->alias != '') {
            if ($alias == $paseo->alias) {
                $claseActivo = 'is-active';
            }
        }


        $tmpl->addSearchReplace('{#LINK#}', Variables::URL.'pagina/'.$paseo->alias);
        $tmpl->addSearchReplace('{#CLASE_ACTIVO#}', $claseActivo);
        $tmpl->addSearchReplace('{#NOMBRE#}', $paseo->nombre);



        return $tmpl->getHtml();
    }

    /**
     * @return mixed
     */
    public function cargarPaginaLogin()
    {
        $tmpl = $this->loadTemplate('pagina_login');

        $this->addGlobalJs('inputmask');
        $this->addGlobalJs('jquery.inputmask');
        $this->addCss('pagina');
        $this->addJs('login');

        $formulario = $this->cargarLogin();

        $tmpl->addSearchReplace('{#BASE_URL#}', $this->serverInfo->getBaseUrl());
        $tmpl->addSearchReplace('{#FORMULARIO#}', $formulario);

        return $tmpl->getHtml();
    }

    /**
     * @return mixed
     */
    public function cargarRegistro()
    {
        $tmpl = $this->loadTemplate('registro');

        return $tmpl->getHtml();
    }

    public function cargarRecordar()
    {
        $tmpl = $this->loadTemplate('recordar');

        return $tmpl->getHtml();
    }

    /**
     * @return mixed
     */
    public function cargarLogin()
    {
        $tmpl = $this->loadTemplate('login');

        return $tmpl->getHtml();
    }

    /**
     * @param $datosFicha
     * @return mixed|string
     */
    public function mostrarFichaLocal($datosFicha)
    {
        $html = '';

        if ($datosFicha) {
            if ($datosFicha->disponible === 0) {
                $html = "<div class='logo-local'>" .
                            "<img src='".$this->serverInfo->getBaseUrl()."uploads/".$datosFicha->carpeta."/mapa/".$datosFicha->logo."'>" .
                        "</div>";

                if (strlen($datosFicha->descripcion) > 0) {
                    $html .= "<div class='texto-local'>".$datosFicha->descripcion."</div>";
                }
            } else {
                $tmpl = $this->loadTemplate('ficha_local');

                $htmlTermin = '';

                $totalTermin = sizeof($datosFicha->terminaciones);

                if ($datosFicha->terminaciones) {
                    for ($i = 0; $i < $totalTermin; $i++) {
                        $numero = $datosFicha->terminaciones[$i]->numero;
                        $descripcion = $datosFicha->terminaciones[$i]->descripcion;
                        $valor = $datosFicha->terminaciones[$i]->valor;

                        $htmlTermin .= '<li><p data-count="'.$numero.'" >'.$descripcion.': '. $valor .'</p></li>';
                    }
                }

                $altura = '';
                if ($datosFicha->altura) {
                    $altura = "Alto desde $datosFicha->altura m. libres aproximado";
                }

                $tmpl->addSearchReplace('{#BASE_URL#}', $this->serverInfo->getBaseUrl());
                $tmpl->addSearchReplace('{#CARPETA#}', $datosFicha->carpeta);
                $tmpl->addSearchReplace('{#NUM_LOCAL#}', $datosFicha->numero);
                $tmpl->addSearchReplace('{#METROS#}', $datosFicha->metros);
                $tmpl->addSearchReplace('{#NOMBRE#}', $datosFicha->nombre);
                $tmpl->addSearchReplace('{#ALTURA#}', $altura);
                $tmpl->addSearchReplace('{#IMAGEN#}', $datosFicha->imagen_ficha);
                $tmpl->addSearchReplace('{#TERMINACIONES#}', $htmlTermin);
                $tmpl->addSearchReplace('{#NOTA#}', nl2br($datosFicha->nota));
                $tmpl->addSearchReplace('{#PIE_FICHA#}', $datosFicha->pie_ficha);
                $tmpl->addSearchReplace('{#FECHA_ACTUALIZACION#}', $datosFicha->fecha_actualizacion);

                $html = $tmpl->getHtml();
            }
        }

        return $html;
    }

    /**
     * @param $nombreMail
     * @param $sitio
     * @param $titulo
     * @param $texto
     * @return mixed
     */
    public function cargarHtmlMail($nombreMail, $sitio, $titulo, $texto)
    {
        $tmpl = $this->loadTemplate('mail');

        $logo = Variables::URL.'uploads/'.$sitio->carpeta.'/imagenes/logo.png';

        $tmpl->addSearchReplace('{#NOMBRE_MAIL#}', $nombreMail);
        $tmpl->addSearchReplace('{#IMAGEN_LOGO#}', $logo);
        $tmpl->addSearchReplace('{#NOMBRE_PASEO#}', $sitio->nombre);
        $tmpl->addSearchReplace('{#TITULO#}', $titulo);
        $tmpl->addSearchReplace('{#TEXTO#}', $texto);
        $tmpl->addSearchReplace('{#BOTON#}', '');

        return $tmpl->getHtml();
    }

    public function cargarCambioContraseña($idUsuario)
    {
        $tmpl = $this->loadTemplate('cambiar_pass');

        $tmpl->addSearchReplace('{#ID_USUARIO#}', $idUsuario);

        return $tmpl->getHtml();
    }
}
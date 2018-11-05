<?php

namespace Neorentas\Paseos\Sitio\Modules\Pagina\Views;

use Neorentas\Paseos\Sitio\System\Variables;
use ZCode\Lighting\View\BaseView;

class PaginaView extends BaseView
{
    /**
     * @param $pagina
     * @param $carpeta
     * @param $htmlPagina
     * @param $clase
     * @return mixed
     */
    public function cargarPagina($pagina, $carpeta, $htmlPagina, $clase)
    {
        $tmpl = $this->loadTemplate('pagina');

        $this->addGlobalCss('owl.carousel');
        $this->addGlobalCss('owl.theme.default');

        $this->addGlobalJs('owl.carousel.min');
        $this->addGlobalJs('owl.carousel2.thumbs');

        $this->addGlobalCss('modal');
        $this->addGlobalJs('util');
        $this->addGlobalJs('modal');

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
        $tmpl->addSearchReplace('{#SITIO_PASEOS#}', Variables::URL);

        $tmpl->addSearchReplace('{#CLASE_PAGINA#}', $clase);

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
     * @param $contacto
     * @param $token
     * @return mixed
     */
    public function cargarPaginaContacto($contacto, $token)
    {
        $tmpl = $this->loadTemplate('contacto');

        $this->addGlobalJs('validacion');
        $this->addGlobalJs('jquery.ba-throttle-debounce.min');

        $direccion      = '';
        $claseDireccion = 'hide';
        $comoLlegar     = '';
        $telefono       = '';
        $telefonoMarcar = '';
        $claseTelefono  = 'hide';
        $googleMaps     = '';
        $claseMapa      = 'hide';

        if ($contacto) {
            $direccion      = $contacto->direccion;
            if ($direccion != '') {
                $claseDireccion = '';
            }
            $comoLlegar      = $contacto->como_llegar;

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

        $tmpl->addSearchReplace('{#DIRECCION#}', $direccion);
        $tmpl->addSearchReplace('{#CLASE_DIRECCION#}', $claseDireccion);
        $tmpl->addSearchReplace('{#COMO_LLEGAR#}', $comoLlegar);

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
     * @param $usuario
     * @param $servicios
     * @param $existePlano
     * @return mixed
     */
    public function cargarPaginaComercial($alias, $comercial, $usuario, $servicios, $existePlano)
    {
        $tmpl = $this->loadTemplate('informacion');

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


            $accionBoton = $this->serverInfo->getBaseUrl()."pagina/$alias/mapa";
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

        // cambiar a dinámico cuando exista la adinistración de mapas
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

                $tmpl->addSearchReplace('{#ICONO#}', Variables::URL.'uploads/servicios/'.$servicio->icono);
                $tmpl->addSearchReplace('{#NOMBRE#}', $servicio->nombre);

                $html .= $tmpl->getHtml();
            }
        }

        return $html;
    }

    /**
     * @param $alias
     * @param $comercial
     * @return mixed
     */
    public function cargarPaginaPlano($alias, $comercial, $planos)
    {
        $tmpl = $this->loadTemplate('mapa');

        $this->addGlobalCss('leaflet');
        //$this->addGlobalCss('modal');
        $this->addCss('pagina');

        $this->addGlobalJs('leaflet');
        $this->addJs('mapa');

        $htmlMenuPlanos     = '';
        $idPlanoDefecto     = '0';
        $piePlano           = '';
        $fechaActualizacion = '';

        if ($planos) {
            $tmplMenuPlano = $this->loadTemplate('item_menu_plano');
            $totalPlanos   = sizeof($planos);

            for ($i = 0; $i < $totalPlanos; $i++) {
                $clasePlano    = '';

                if ($i === 0) {
                    $idPlanoDefecto = $planos[$i]->id_plano;
                    $clasePlano = 'is-active';
                }

                $tmplMenuPlano->addSearchReplace('{#NOMBRE_PLANO#}', $planos[$i]->nombre);
                $tmplMenuPlano->addSearchReplace('{#ID_PLANO#}', $planos[$i]->id_plano);
                $tmplMenuPlano->addSearchReplace('{#CLASE_PLANO#}', $clasePlano);

                $htmlMenuPlanos .= $tmplMenuPlano->getHtml();
                $tmplMenuPlano->resetTemplate();
            }
        }

        $tmpl->addSearchReplace('{#ALIAS#}', $alias);

        $tmpl->addSearchReplace('{#MENU_PLANOS#}', $htmlMenuPlanos);
        $tmpl->addSearchReplace('{#ID_PLANO_DEFECTO#}', $idPlanoDefecto);
        $tmpl->addSearchReplace('{#PIE_PLANO#}', $piePlano);
        $tmpl->addSearchReplace('{#FECHA_ACTUALIZACION#}', $fechaActualizacion);

        return $tmpl->getHtml();
    }

    /**
     * @param $tiendas
     * @param $alias
     * @param $comercial
     * @return mixed
     */
    public function cargarTiendas($tiendas, $alias, $comercial)
    {
        $tmpl = $this->loadTemplate('tiendas');

        $this->addJs('tiendas');
        $this->addCss('pagina');

        $htmlTiendas = '';
        if ($tiendas) {
            foreach ($tiendas as $tienda) {
                $htmlTiendas .= $this->cargarTienda($tienda);
            }
        }

        $tmpl->addSearchReplace('{#TIENDAS#}', $htmlTiendas);

        return $tmpl->getHtml();
    }

    public function cargarTienda($tienda) {
        $tmpl = $this->loadTemplate('tiendas_item');

        $abierto = '<span class="color-celeste">(*)</span>';

        if ($tienda->abierto == 1) {
            $abierto = '';
        }

        $url= 'tiendas_paseo_balmaceda/'.$tienda->alias;


        $tmpl->addSearchReplace('{#NOMBRE#}', $tienda->nombre);
        $tmpl->addSearchReplace('{#CATEGORIA#}', $tienda->categoria);
        $tmpl->addSearchReplace('{#LOCAL#}', 'LOCAL '.$tienda->numero);
        $tmpl->addSearchReplace('{#ID_TIENDA#}', $tienda->id_local);
        $tmpl->addSearchReplace('{#PROXIMA#}', $abierto);
        $tmpl->addSearchReplace('{#URL#}', $url);


        return $tmpl->getHtml();
    }

    public function cargarBusquedaTiendas($tiendas)
    {
        $htmlTiendas = '';
        if ($tiendas) {
            foreach ($tiendas as $tienda) {
                $htmlTiendas .= $this->cargarTienda($tienda);
            }
        }

        return $htmlTiendas;
    }

    public function cargarInfoTienda($tienda, $carpeta)
    {
        $tmpl = $this->loadTemplate('tiendas_info');

        $logo = '';
        $descripcion = '';
        $telefono = '';
        $web = '';
        $ubicacion = '';
        $foto = '';
        $apertura = '';

        if ($tienda->logo != '') {
            $logo = '<div class="logo-tienda column is-2"><img src="'.Variables::URL.'uploads/'.$carpeta.'/mapa/'.$tienda->logo.'"></div>';
        }

        if ($tienda->descripcion) {
            $descripcion = $tienda->descripcion;
        }

        if ($tienda->telefono) {
            $telefono = '<div class="icono-servicio"><img src="{#BASE_URL#}resources/img/telefono_celeste.svg"></div>';
            $telefono .= '<div>'.$tienda->telefono.'</div>';
        }

        if ($tienda->pagina_web) {
           $web = '<a href="http://'.$tienda->pagina_web.'" target="_blank">'.$tienda->pagina_web.'</a>';
        }

        if ($tienda->plano_ubicacion != '') {
            $ubicacion = '<div class="column is-half"><img src="'.Variables::URL.'uploads/'.$carpeta.'/mapa/ubicacion/'.$tienda->plano_ubicacion.'"></div>';
        }

        if ($tienda->foto != '') {
            $foto = '<div class="column is-half"><img src="'.Variables::URL.'uploads/'.$carpeta.'/tiendas/'.$tienda->foto.'"></div>';
        }

        if ($tienda->abierto == 0) {
            $apertura = '<div class="proxima-apertura">(*) Próxima apertura</div>';
        }

        $tmpl->addSearchReplace('{#LOGO#}', $logo);
        $tmpl->addSearchReplace('{#NOMBRE#}', $tienda->nombre);
        $tmpl->addSearchReplace('{#CATEGORIA#}', $tienda->categoria);
        $tmpl->addSearchReplace('{#DESCRIPCION#}', $descripcion);
        $tmpl->addSearchReplace('{#TELEFONO#}', $telefono);
        $tmpl->addSearchReplace('{#PAGINA_WEB#}', $web);
        $tmpl->addSearchReplace('{#UBICACION#}', $ubicacion);
        $tmpl->addSearchReplace('{#FOTO#}', $foto);
        $tmpl->addSearchReplace('{#LOCAL#}', 'LOCAL '.$tienda->numero);
        $tmpl->addSearchReplace('{#APERTURA#}', $apertura);

        return $tmpl->getHtml();
    }

    /**
     * @param $sitio
     * @param $promociones
     * @return mixed
     */
    public function cargarPromociones($sitio, $promociones)
    {
        $html = '';

        if ($promociones) {
            foreach ($promociones as $promocion) {
                $tmpl = $this->loadTemplate('promociones');

                $imagen1 = '';
                $imagen2 = '';

                $fecha = $promocion->fecha_creacion;
                $dir = Variables::URL.'uploads/'.$sitio->carpeta.'/promociones/'.date('Y', strtotime($fecha)).'/'.date('m', strtotime($fecha)).'/';

                if ($promocion->foto1 != '') {
                    $imagen1 = '<img src="'.$dir.$promocion->foto1.'">';
                    if ($promocion->link != '') {
                        $imagen1 = '<a href="'.$promocion->link.'">'.$imagen1.'</a>';
                    }
                }

                if ($promocion->foto2 != '') {
                    $imagen2 = '<img src="'.$dir.$promocion->foto2.'">';
                    if ($promocion->link != '') {
                        $imagen2 = '<a href="'.$promocion->link.'">'.$imagen2.'</a>';
                    }
                }

                $tmpl->addSearchReplace('{#IMAGEN_1#}', $imagen1);
                $tmpl->addSearchReplace('{#IMAGEN_2#}', $imagen2);

                $html .= $tmpl->getHtml();
            }
        }

        return $html;
    }

    /**
     * @param $sitio
     * @param $eventos
     * @return mixed
     */
    public function cargarEventos($sitio, $noticias, $num_pagina, $pagina)
    {
        $html = '';
        $paginador = '';  
        $tmplNoticias = $this->loadTemplate('noticias');        
        $total_paginas = ceil($num_pagina / 3); 
        $url =  $this->serverInfo->getBaseUrl().'pagina/noticias_paseo_balmaceda/';
        if ($pagina < 2) {
            $pagina = 1;
        }
        $anterior = '<div class= "boton-previo"><a href= "'.$url.($pagina-1).'"> < Anterior</a></div>';
        $siguiente = '<div class="boton-next"><a href= "'.$url.($pagina+1).'">Siguiente > </a></div>';


        

        for ($x=1; $x <= $total_paginas; $x++) { 

            


            if ($pagina == $x){

                $paginador .= '<div class="boton"><a href="#"><strong>'.$x.'</strong></a></div>';
            }
            else { 
            $paginador .= '<div class="boton"><a href="'.$url.$x.'">'.$x.'</a></div>';

        }

        }


        if ($noticias) {
            $i = 0;

            $serieClases = ['bg-black', 'bg-danger', 'bg-purple'];

            foreach ($noticias as $evento) {
                if ($i > 2) {
                    $i = 0;


                }

                $tmpl = $this->loadTemplate('eventos');

                $fecha = $evento->fecha_creacion;
                $dir = Variables::URL.'uploads/'.$sitio->carpeta.'/eventos/'.date('Y', strtotime($fecha)).'/'.date('m', strtotime($fecha)).'/';

                $diaEvento = '';
                $mesNombre = '';

                if ($evento->fecha_evento) {
                    $diaEvento = date('d', strtotime($evento->fecha_evento));
                    $mes = date('n', strtotime($evento->fecha_evento));

                    $mesNombre = Variables::MESES[($mes-1)];
                }



                $descripcion = preg_replace('/\s+?(\S+)?$/', '', substr($evento->descripcion, 0, 90));

              

                $tmpl->addSearchReplace('{#IMAGEN#}', $dir.$evento->foto);
                $tmpl->addSearchReplace('{#DIA#}', $diaEvento);
                $tmpl->addSearchReplace('{#MES#}', $mesNombre);
                $tmpl->addSearchReplace('{#TITULO#}', $evento->titulo);
                $tmpl->addSearchReplace('{#DESCRIPCION#}', $descripcion);
                $tmpl->addSearchReplace('{#HORARIO#}', $evento->horario);
                $tmpl->addSearchReplace('{#CLASE#}', $serieClases[$i]);
                $tmpl->addSearchReplace('{#VER#}', $url.$evento->alias);

                

                $html .= $tmpl->getHtml();


                $i++;
            }
        }
        if ($pagina == $total_paginas) {
            $siguiente = '<div class= "next">Siguiente > </div>' ;

        }
        if ($pagina >=2) {
            $paginador = $anterior.$paginador.$siguiente;
        }
        
        else {
            $anterior = '<div class= "previo"> < Anterior</div>' ;
            $paginador = $anterior.$paginador.$siguiente;
        }
    

        $tmplNoticias->addSearchReplace('{#NOTICIAS#}', $html);
        $tmplNoticias->addSearchReplace('{#PAGINADOR#}',$paginador);
        $htmlFinal = $tmplNoticias->getHtml();

        return $htmlFinal;
    }

    /**
     * @param $sitio
     * @param $galerias
     * @return mixed
     */
    public function cargarNoticia($sitio, $noticias, $urlRecuperada)
    {
        $noticompleta='';
        $tmplVerMas= $this->loadTemplate('ver_noticia'); 
        $fecha = $noticias->fecha_creacion;
        $dir = Variables::URL.'uploads/'.$sitio->carpeta.'/eventos/'.date('Y', strtotime($fecha)).'/'.date('m', strtotime($fecha)).'/';

        $volver = $this->serverInfo->getBaseUrl().'pagina/noticias_paseo_balmaceda/'.$urlRecuperada;
       

        $noticia = nl2br($noticias->contenido);
        

        $tmplVerMas->addSearchReplace('{#TITULO#}', $noticias->titulo);
        $tmplVerMas->addSearchReplace('{#DESCRIPCION#}', $noticias->descripcion); 
        $tmplVerMas->addSearchReplace('{#IMAGEN#}',$dir.$noticias->foto);
        $tmplVerMas->addSearchReplace('{#NOTICIAS#}', $noticia); 
        $tmplVerMas->addSearchReplace('{#VOLVER#}', $volver); 

        $noticompleta .= $tmplVerMas->getHtml();    

        return $noticompleta;



    }

    public function cargarEventosAnteriores($sitio, $galerias)
    {
        $tmpl = $this->loadTemplate('eventos_anteriores');

        $htmlGalerias = '';

        if ($galerias) {
            $i = 0;

            $serieClases = ['bg-black', 'bg-danger', 'bg-purple'];

            foreach ($galerias as $galeria) {
                if ($i > 2) {
                    $i = 0;
                }

                $tmplGaleria = $this->loadTemplate('item_galeria');

                $dir = Variables::URL.'uploads/'.$sitio->carpeta.'/galerias/galeria-'.$galeria->id_galeria.'/';

                $tmplGaleria->addSearchReplace('{#CLASE#}', $serieClases[$i]);
                $tmplGaleria->addSearchReplace('{#IMAGEN#}', $dir.$galeria->foto);
                $tmplGaleria->addSearchReplace('{#TITULO#}', $galeria->titulo);
                $tmplGaleria->addSearchReplace('{#DESCRIPCION#}', $galeria->descripcion);
                $tmplGaleria->addSearchReplace('{#ID_GALERIA#}', $galeria->id_galeria);

                $htmlGalerias .= $tmplGaleria->getHtml();

                $i++;
            }
        }

        $tmpl->addSearchReplace('{#GALERIAS#}', $htmlGalerias);

        return $tmpl->getHtml();
    }

    /**
     * @param $galeria
     * @return mixed
     */
    public function cargarGaleria($galeria)
    {
        $tmpl = $this->loadTemplate('galeria');

        $imagen    = 'No existen fotos en esta galeria.';
        $miniatura = '';

        if ($galeria) {
            $imagen = '';
            foreach ($galeria as $item) {
                $dir = Variables::URL.'uploads/'.$item->carpeta.'/galerias/galeria-'.$item->id_galeria.'/';

                $tmplImagen = $this->loadTemplate('galeria_item');
                $tmplImagen->addSearchReplace('{#IMAGEN#}', $dir.$item->foto);
                $imagen .= $tmplImagen->getHtml();

                $tmplMiniatura = $this->loadTemplate('galeria_miniatura');
                $tmplMiniatura->addSearchReplace('{#MINIATURA#}', $dir.$item->foto);
                $miniatura .= $tmplMiniatura->getHtml();
            }
        }


        $tmpl->addSearchReplace('{#GALERIA_IMAGEN#}', $imagen);
        $tmpl->addSearchReplace('{#MINIATURA#}', $miniatura);

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
                $html = "<div class='logo-local'>
                            <img src='".Variables::URL."uploads/".$datosFicha->carpeta."/mapa/".$datosFicha->logo."'>
                         </div>";


                if (strlen($datosFicha->descripcion) > 0) {
                    $html .= "<div class='texto-local'>".$datosFicha->descripcion."</div>";
                }
            } else {
                $tmpl = $this->loadTemplate('ficha_local');

                $htmlTermin = '';
                $totalTermin = sizeof($datosFicha->terminaciones);

                for ($i = 0; $i < $totalTermin; $i++) {
                    $numero = $datosFicha->terminaciones[$i]->numero;
                    $descripcion = $datosFicha->terminaciones[$i]->descripcion;
                    $valor = $datosFicha->terminaciones[$i]->valor;

                    $htmlTermin .= '<li><p data-count="'.$numero.'" >'.$descripcion.': '. $valor .'</p></li>';
                }

                $altura = '';
                if ($datosFicha->altura) {
                    $altura = "Alto desde $datosFicha->altura m. libres aproximado";
                }

                $tmpl->addSearchReplace('{#SITIO_PASEOS#}', Variables::URL);
                $tmpl->addSearchReplace('{#CARPETA#}', $datosFicha->carpeta);
                $tmpl->addSearchReplace('{#NUM_LOCAL#}', $datosFicha->numero);
                $tmpl->addSearchReplace('{#METROS#}', $datosFicha->metros);
                $tmpl->addSearchReplace('{#NOMBRE#}', $datosFicha->nombre);
                $tmpl->addSearchReplace('{#ALTURA#}', $altura);
                $tmpl->addSearchReplace('{#IMAGEN#}', $datosFicha->imagen_ficha);
                $tmpl->addSearchReplace('{#TERMINACIONES#}', $htmlTermin);
                $tmpl->addSearchReplace('{#INSTALACIONES#}', $datosFicha->instalaciones);
                $tmpl->addSearchReplace('{#NOTA#}', $datosFicha->nota);
                $tmpl->addSearchReplace('{#PIE_FICHA#}', $datosFicha->pie_ficha);
                $tmpl->addSearchReplace('{#FECHA_ACTUALIZACION#}', $datosFicha->fecha_actualizacion);

                $html = $tmpl->getHtml();
            }
        }

        return $html;
    }

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

    public function obtenerHtmlTienda($datos)
    {
        $html = '';

        if ($datos !== null) {
            $tmpl = $this->loadTemplate('datos_tienda');

            $logo = Variables::URL.'uploads/'.$datos->carpeta.'/mapa/'.$datos->logo;

            $tmpl->addSearchReplace('{#LOGO_TIENDA#}', $logo);
            $tmpl->addSearchReplace('{#NOMBRE_TIENDA#}', $datos->nombre);
            $tmpl->addSearchReplace('{#NUMERO#}', $datos->numero);
            $tmpl->addSearchReplace('{#DESCRIPCION_TIENDA#}', $datos->descripcion);

            $html = $tmpl->getHtml();
        }

        return $html;
    }
}
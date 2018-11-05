<?php

namespace Neorentas\Administrador\Modules\Configuracion\Views;

use Neorentas\Administrador\System\Variables;
use ZCode\Lighting\View\BaseView;

class ConfiguracionView extends BaseView
{
    /**
     * @param $idPagina
     * @param $datos
     * @param $pagina
     * @return mixed
     */
    public function cargarConfiguracion($idPagina, $datos, $pagina, $imagenes)
    {
        $tmpl = $this->loadTemplate('configuracion');

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

        $this->addCss('configuracion');
        $this->addJs('configuracion');

        $nombre  = '';
        $carpeta = '';
        $url     = '';

        $paginaInfo = '';

        if ($datos->id_sitio == 1) {
            $paginaInfo = $this->cargarPagina($idPagina, $datos, $pagina, $imagenes);
        }

        if ($datos) {
            $nombre = $datos->nombre;
            $carpeta = $datos->carpeta;
            $url = $datos->link;
        }

        $tmpl->addSearchReplace('{#ID_SITIO#}', $datos->id_sitio);
        $tmpl->addSearchReplace('{#NOMBRE#}', $nombre);
        $tmpl->addSearchReplace('{#CARPETA#}', $carpeta);
        $tmpl->addSearchReplace('{#URL#}', $url);
        $tmpl->addSearchReplace('{#PAGINA_INFORMACION_GENERAL#}', $paginaInfo);

        return $tmpl->getHtml();
    }

    /**
     * @param $idPagina
     * @param $datos
     * @param $pagina
     * @return mixed
     */
    public function cargarPagina($idPagina, $datos, $pagina, $imagenes)
    {
        $tmpl = $this->loadTemplate('pagina');

        $titulo1 = '';
        $titulo2 = '';
        $texto   = '';

        /* si viene imagen o no */
        $htmlAccionImagen = $this->htmlSubirImagen('imagenes');

        /* si viene fondo o no */
        $htmlAccionFondo = $this->htmlSubirImagen('fondos');

        $carpeta = $datos->carpeta;

        if ($pagina) {
            $titulo1 = $pagina->titulo1;
            $titulo2 = $pagina->titulo2;
            $texto   = $pagina->texto;

            if ($pagina->fondo != '') {
                $dir  = Variables::URL.'uploads/'.$datos->carpeta.'/fondos';
                $imagenFondo = $dir.'/'.$pagina->fondo;

                $htmlAccionFondo = $this->htmlImagen('fondos', $imagenFondo);
            }

            if ($imagenes) {
                $imagen = Variables::URL.'uploads/'.$carpeta.'/imagenes/'.$imagenes->archivo;
                $htmlAccionImagen = $this->htmlImagen('imagenes', $imagen);
            }
        }

        $tmpl->addSearchReplace('{#ID_PAGINA#}', $idPagina);
        $tmpl->addSearchReplace('{#TITULO1#}', $titulo1);
        $tmpl->addSearchReplace('{#TITULO2#}', $titulo2);
        $tmpl->addSearchReplace('{#TEXTO#}', $texto);
        $tmpl->addSearchReplace('{#HTML_IMAGEN#}', $htmlAccionImagen);
        $tmpl->addSearchReplace('{#HTML_FONDO#}', $htmlAccionFondo);

        return $tmpl->getHtml();
    }

    /**
     * @param $tipo
     * @param $imagen
     * @return mixed
     */
    public function htmlImagen($tipo, $imagen)
    {
        $tmpl = $this->loadTemplate('imagen');

        $tmpl->addSearchReplace('{#IMAGEN#}', $imagen);
        $tmpl->addSearchReplace('{#TIPO#}', $tipo);

        return $tmpl->getHtml();
    }

    /**
     * @param $idTipo
     * @return mixed
     */
    public function htmlSubirImagen($idTipo)
    {
        $tmpl = $this->loadTemplate('subir_imagen');

        $tmpl->addSearchReplace('{#ID#}', $idTipo);

        return $tmpl->getHtml();
    }
}
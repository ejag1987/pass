<?php

namespace Neorentas\Administrador\Modules\Contacto\Views;

use Neorentas\Administrador\System\Variables;
use ZCode\Lighting\View\BaseView;

class ContactoView extends BaseView
{
    /**
     * @param $idPagina
     * @param $datos
     * @param $pagina
     * @return mixed
     */
    public function cargarContacto($idPagina, $datos, $pagina)
    {
        $tmpl = $this->loadTemplate('contacto');

        $this->addGlobalCss('upload');
        $this->addGlobalJs('jquery.ui.widget');
        $this->addGlobalJs('load-image.all.min');
        $this->addGlobalJs('canvas-to-blob.min');
        $this->addGlobalJs('jquery.iframe-transport');
        $this->addGlobalJs('jquery.fileupload');
        $this->addGlobalJs('jquery.fileupload-process');
        $this->addGlobalJs('jquery.fileupload-image');
        $this->addGlobalJs('jquery.fileupload-validate');

        $this->addGlobalJs('inputmask');
        $this->addGlobalJs('jquery.inputmask');

        $this->addGlobalCss('jquery-confirm');
        $this->addGlobalJs('jquery-confirm');

        $this->addGlobalJs('validacion');

        $this->addCss('contacto');
        $this->addJs('contacto');

        $titulo1    = '';
        $titulo2    = '';
        $direccion  = '';
        $correo     = '';
        $telefono   = '';
        $googleMaps = '';
        $comoLlegar = '';

        /* si viene fondo o no */
        $htmlAccionFondo = $this->htmlSubirImagen();

        if ($pagina) {
            $titulo1 = $pagina->titulo1;
            $titulo2 = $pagina->titulo2;
            $fondo   = $pagina->fondo;

            $carpeta = $datos->carpeta;

            if ($fondo != '') {
                $dir  = Variables::URL.'uploads/'.$carpeta.'/fondos';
                $imagenFondo = $dir.'/'.$pagina->fondo;

                $htmlAccionFondo = $this->htmlImagen($imagenFondo);
            }
        }

        if ($datos) {
            $direccion  = $datos->direccion;
            $correo     = $datos->correo;
            $telefono   = $datos->telefono;
            $googleMaps = $datos->google_map;
            $comoLlegar = $datos->como_llegar;
        }

        $tmpl->addSearchReplace('{#ID_SITIO#}', $datos->id_sitio);
        $tmpl->addSearchReplace('{#ID_PAGINA#}', $idPagina);
        $tmpl->addSearchReplace('{#TITULO1#}', $titulo1);
        $tmpl->addSearchReplace('{#TITULO2#}', $titulo2);
        $tmpl->addSearchReplace('{#DIRECCION#}', $direccion);
        $tmpl->addSearchReplace('{#CORREO#}', $correo);
        $tmpl->addSearchReplace('{#TELEFONO#}', $telefono);
        $tmpl->addSearchReplace('{#GOOGLE_MAPS#}', $googleMaps);
        $tmpl->addSearchReplace('{#COMO_LLEGAR#}', $comoLlegar);

        $tmpl->addSearchReplace('{#HTML_FONDO#}', $htmlAccionFondo);

        return $tmpl->getHtml();
    }

    /**
     * @param $imagenFondo
     * @return mixed
     */
    public function htmlImagen($imagenFondo)
    {
        $tmpl = $this->loadTemplate('imagen');

        $tmpl->addSearchReplace('{#IMAGEN#}', $imagenFondo);

        return $tmpl->getHtml();
    }

    /**
     * @return mixed
     */
    public function htmlSubirImagen()
    {
        $tmpl = $this->loadTemplate('subir_imagen');

        return $tmpl->getHtml();
    }
}
<?php

namespace Neorentas\Administrador\Modules\Marca\Views;

use Neorentas\Administrador\System\Variables;
use ZCode\Lighting\View\BaseView;

class MarcaView extends BaseView
{
    /**
     * @param $idSitio
     * @param $carpeta
     * @param $marcas
     * @return mixed
     */
    public function cargarMarcas($idSitio, $carpeta, $marcas)
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

        $this->addCss('marca');
        $this->addJs('marca');

        $htmlMarcas = '';

        if ($marcas) {
            foreach ($marcas as $marca) {
                $htmlMarcas .= $this->cargarHtmlMarca($marca, $carpeta);
            }
        }

        $tmpl->addSearchReplace('{#ID_SITIO#}', $idSitio);
        $tmpl->addSearchReplace('{#MARCAS#}', $htmlMarcas);

        return $tmpl->getHtml();
    }

    /**
     * @param $marca
     * @param $carpeta
     * @return mixed
     */
    public function cargarHtmlMarca($marca, $carpeta)
    {
        $tmpl = $this->loadTemplate('marca');

        /* si viene ícono o no */
        $htmlAccionIcono = $this->htmlSubirImagen($marca->id_marca);

        if ($marca->logo != '') {
            $imagenLogo = Variables::URL.'uploads/'.$carpeta.'/marcas/'.$marca->logo;
            $htmlAccionIcono = $this->htmlLogo($imagenLogo, $marca->id_marca);
        }

        $tmpl->addSearchReplace('{#ID_MARCA#}', $marca->id_marca);
        $tmpl->addSearchReplace('{#NOMBRE#}', $marca->nombre);
        $tmpl->addSearchReplace('{#LINK#}', $marca->link);
        $tmpl->addSearchReplace('{#HTML_ACCION_ICONO#}', $htmlAccionIcono);

        return $tmpl->getHtml();
    }

    /**
     * @param $imagenLogo
     * @param $idMarca
     * @return mixed
     */
    public function htmlLogo($imagenLogo, $idMarca)
    {
        $tmpl = $this->loadTemplate('logo');

        $tmpl->addSearchReplace('{#LOGO#}', $imagenLogo);
        $tmpl->addSearchReplace('{#ID_MARCA#}', $idMarca);

        return $tmpl->getHtml();
    }

    /**
     * @param $idMarca
     * @return mixed
     */
    public function htmlSubirImagen($idMarca)
    {
        $tmpl = $this->loadTemplate('subir_imagen');

        $tmpl->addSearchReplace('{#ID_MARCA#}', $idMarca);

        return $tmpl->getHtml();
    }
}
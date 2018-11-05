<?php

namespace Neorentas\Paseos\Modules\Inicio\Views;

use Neorentas\Paseos\System\Variables;
use ZCode\Lighting\View\BaseView;

class InicioView extends BaseView
{
    public function cargarPagina($carpeta, $slides, $marcas, $calugas)
    {
        $tmpl = $this->loadTemplate('portada');

        $this->addCss('owl.carousel');
        $this->addCss('owl.theme.default');
        $this->addCss('inicio');

        $this->addJs('owl.carousel.min');
        $this->addJs('inicio');

        $slideHtml  = $this->cargarSlide($carpeta, $slides);
        $marcasHtml = $this->cargarMarcas($carpeta, $marcas);
        $calugaHtml = $this->cargarCalugas($carpeta, $calugas);

        $tmpl->addSearchReplace('{#SLIDE#}', $slideHtml);
        $tmpl->addSearchReplace('{#MARCAS#}', $marcasHtml);
        $tmpl->addSearchReplace('{#SITIOS#}', $calugaHtml);

        return $tmpl->getHtml();
    }

    private function cargarSlide($carpeta, $slides)
    {
        $html = '';

        if ($slides) {
            foreach ($slides as $slide) {
                if ($slide->imagen != '') {
                    $tmpl = $this->loadTemplate('slide');

                    $claseLink = '';

                    if ($slide->link == '') {
                        $claseLink = 'not-active';
                    }

                    $imagen = Variables::URL.'uploads/'.$carpeta.'/slide/'.$slide->imagen;
                    $imagenMovil = '';

                    if ($slide->imagen_movil != '') {
                        $imagenMovil = Variables::URL.'uploads/'.$carpeta.'/slide/'.$slide->imagen_movil;
                    }

                    $tmpl->addSearchReplace('{#NO_ACTIVO#}', $claseLink);
                    $tmpl->addSearchReplace('{#LINK#}', $slide->link);
                    $tmpl->addSearchReplace('{#SLIDE#}', $imagen);
                    $tmpl->addSearchReplace('{#IMAGEN_MOVIL#}', $imagenMovil);

                    $html .= $tmpl->getHtml();
                }
            }
        }
        return $html;
    }

    /**
     * @param $carpeta
     * @param $marcas
     * @return string
     */
    private function cargarMarcas($carpeta, $marcas)
    {
        $html = '';
        if ($marcas) {
            foreach ($marcas as $marca) {
                if ($marca->logo != '') {
                    $tmpl = $this->loadTemplate('marcas');

                    $logo = Variables::URL.'uploads/'.$carpeta.'/marcas/'.$marca->logo;
                    $marcaLink = '<img src="'.$logo.'" alt="'.$marca->nombre.'" title="'.$marca->nombre.'">';

                    if ($marca->link != '') {
                        $activoLink = '';
                        $marcaLink = ' <a href="'.$marca->link.'" class="'.$activoLink.'" target="_blank"><img src="'.$logo.'" alt="'.$marca->nombre.'" title="'.$marca->nombre.'"></a>';
                    }

                    $tmpl->addSearchReplace('{#MARCA#}', $marcaLink);

                    $html .= $tmpl->getHtml();
                }
            }
        }

        return $html;
    }

    private function cargarCalugas($carpeta, $calugas)
    {
        $html = '';

        if ($calugas) {
            $totalCalugas = sizeof($calugas);
            $porFila = $totalCalugas%3;

            $clase = 'is-4';
            $multiple = false;

            switch ($porFila) {
                case 0:
                    $clase = 'is-4-desktop';
                    break;
                case 2:
                    $clase = 'is-3';
					$clase = 'is-3-desktop';

                    break;
                case 1:
                    $multiple = true;
                    break;
            }


            for ($i=0; $i<$totalCalugas; $i++) {
                if ($calugas[$i]->imagen != '') {
                    $tmpl = $this->loadTemplate('sitio');

                    $imagen = Variables::URL.'uploads/'.$carpeta.'/caluga/'.$calugas[$i]->imagen;

                    $imagenCaluga = '<img src="'.$imagen.'">';

                    if ($calugas[$i]->link != '') {
                        $imagenCaluga = '<a href="'.$calugas[$i]->link.'"><img src="'.$imagen.'"></a>';
                    }

                    if ($multiple) {
                        if ($i >= ($totalCalugas-4)) {
                            $clase = 'is-3';
                        }
                    }

                    $tmpl->addSearchReplace('{#CALUGA#}', $imagenCaluga);
                    $tmpl->addSearchReplace('{#CLASE#}', $clase);

                    $html .= $tmpl->getHtml();
                }
            }


        }
        return $html;
    }
}
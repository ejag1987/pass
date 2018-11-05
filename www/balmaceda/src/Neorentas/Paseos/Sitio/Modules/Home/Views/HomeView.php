<?php

namespace Neorentas\Paseos\Sitio\Modules\Home\Views;

use Neorentas\Paseos\Sitio\System\Variables;
use ZCode\Lighting\View\BaseView;

class HomeView extends BaseView
{
    public function cargarPagina($carpeta, $slides, $marcas, $calugas, $calugasite)
    {
        $tmpl = $this->loadTemplate('portada');

        $this->addGlobalCss('owl.carousel');
        $this->addGlobalCss('owl.theme.default');
        $this->addCss('inicio');

        $this->addGlobalJs('owl.carousel.min');
        $this->addJs('inicio');

        $slide   = $this->cargarSlide($carpeta, $slides);
        $marcas  = $this->cargarMarcas($carpeta, $marcas);
        $banners = $this->cargarBanners($carpeta, $calugas);
        $bannersite = $this->cargarBannersSite($carpeta, $calugasite);

        $tmpl->addSearchReplace('{#SLIDE#}', $slide);
        $tmpl->addSearchReplace('{#MARCAS#}', $marcas);
        $tmpl->addSearchReplace('{#BANNERS#}', $banners);
        $tmpl->addSearchReplace('{#BANNERSITE#}', $bannersite);

        return $tmpl->getHtml();
    }

    /**
     * @param $carpeta
     * @param $slides
     * @return string
     */
    private function cargarSlide($carpeta, $slides)
    {
        $html = '';

        if ($slides) {
            foreach ($slides as $slide) {
                if ($slide->imagen != '') {
                    $tmpl = $this->loadTemplate('slide');
                    $comentario = $slide->comentario;
                    $comentario = str_replace("\n", "</span><br><span>", $comentario);

                    $claseLink = '';

                    if ($slide->link == '') {
                        $claseLink = 'not-active';
                    }

                    $imagen = Variables::URL.'uploads/'.$carpeta.'/slide/'.$slide->imagen;
                  //  $imagenMovil = '';

                 /*   if ($slide->imagen_movil != '') {
                        $imagenMovil = Variables::URL.'uploads/'.$carpeta.'/slide/'.$slide->imagen_movil;
                    }
                    $imagenMovil= ''; */
				

                    $tmpl->addSearchReplace('{#NO_ACTIVO#}', $claseLink);
                    $tmpl->addSearchReplace('{#LINK#}', $slide->link);
                    $tmpl->addSearchReplace('{#SLIDE#}', $imagen);
                //  $tmpl->addSearchReplace('{#IMAGEN_MOVIL#}', $imagenMovil);
                    $tmpl->addSearchReplace('{#COMENTARIO#}', $comentario);

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


    private function cargarBanners($carpeta, $calugas)
  {
        $html = '';

        if ($calugas) {
            $totalCalugas = sizeof($calugas);
            $porFila = $totalCalugas%2;

            $clase = 'is-half';
            $multiple = false;

            for ($i=0; $i<2; $i++) {
                if ($calugas[$i]->imagen != '') {
                    $tmpl = $this->loadTemplate('banner');
                    $imagen = Variables::URL.'uploads/'.$carpeta.'/caluga/'.$calugas[$i]->imagen;
                    if ($calugas[$i]->orden ==1) {
                        $imagenCaluga = '<img src="'.$imagen.'" id="izquierda">';
                    }
                    if ($calugas[$i]->orden ==2){
                        $imagenCaluga = '<img src="'.$imagen.'" id="derecha">';
                    }

                    if ($calugas[$i]->link != '') {
                        if ($calugas[$i]->orden ==1) {
                        $imagenCaluga = '<a href="'.$calugas[$i]->link.'" target="_blank"><img src="'.$imagen.'" id="izquierda"></a>';
                        }
                        if ($calugas[$i]->orden ==2) {
                        $imagenCaluga = '<a href="'.$calugas[$i]->link.'" target="_blank"><img src="'.$imagen.'" id="derecha"></a>';
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

     private function cargarBannersSite($carpeta, $calugasite)
    {
        $html = '';

        if ($calugasite) {
            $totalCalugas = sizeof($calugasite);
            $porFila = $totalCalugas%2;

            $clase = 'is-half';
            $multiple = false;

            switch ($porFila) {
                case 0:
                    $clase = 'is-half';
                    break;
                case 1:
                    $multiple = true;
                    break;
            }


            for ($i=0; $i<$totalCalugas; $i++) {
                if ($calugasite[$i]->imagen != '') {
                    $tmpl = $this->loadTemplate('banner');

                    $imagen = Variables::URL.'uploads/'.$carpeta.'/caluga/'.$calugasite[$i]->imagen;

                    $imagenCaluga = '<img src="'.$imagen.'">';

                    if ($calugasite[$i]->link != '') {
                        $imagenCaluga = '<a href="'.$calugasite[$i]->link.'" target="_blank"><img src="'.$imagen.'"></a>';
                    }

                    if ($multiple) {
                        if ($i >= ($totalCalugas-3)) {
                            $clase = 'is-one-third';
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
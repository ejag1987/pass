<?php

namespace Neorentas\Paseos\Modules\Ficha\Views;

use Neorentas\Paseos\System\Variables;
use Svg\Document;
use SVG\SVGImage;
use ZCode\Lighting\View\BaseView;

class FichaView extends BaseView
{
    public function obtenerHtmlFicha($docRoot, $datosFicha)
    {
        $tmpl = $this->loadTemplate('ficha');

        $htmlTermin = '';
        $totalTermin = sizeof($datosFicha->terminaciones);

        if ($datosFicha->terminaciones) {
            for ($i = 0; $i < $totalTermin; $i++) {
                $numero = $datosFicha->terminaciones[$i]->numero;
                $descripcion = $datosFicha->terminaciones[$i]->descripcion;
                $valor = $datosFicha->terminaciones[$i]->valor;

                $htmlTermin .= '<li><p data-count="'.$numero.'." >'.$descripcion.': '. $valor .'</p></li>';
            }
        }


        $altura = '';
        if ($datosFicha->altura) {
            $altura = "Alto desde $datosFicha->altura m. libres aproximado";
        }

        $imagen = $docRoot.'/uploads/'.$datosFicha->carpeta.'/mapa/'.$datosFicha->imagen_ficha;

        $image = SVGImage::fromFile($imagen);
        //var_dump($image);

        $ancho = $image->getDocument()->getAttribute('width');
        $alto  = $image->getDocument()->getAttribute('height');

        if ($ancho > $alto) {
            $estilo = 'width: 17cm;';
        } else {
            $estilo = 'height: 17cm;';
        }

        //$tmpl->addSearchReplace('{#DOC_ROOT#}', Variables::URL);
        $tmpl->addSearchReplace('{#ESTILO#}', $estilo);
        $tmpl->addSearchReplace('{#DOC_ROOT#}', $docRoot);
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

        return $tmpl->getHtml();
    }
}

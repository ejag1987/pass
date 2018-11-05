<?php
    namespace Neorentas\Administrador\Modules\Papelera\Views;

    use ZCode\Lighting\Model\BaseModel;
    use ZCode\Lighting\View\BaseView;

    class PapeleraView extends BaseView
    {

        public function cargarPortada($idSitio, $carpeta, $slides, $calugas)
        {
            $tmpl = $this->loadTemplate('portada');

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

            $this->addGlobalJs('jquery-ui.min');

            $this->addGlobalJs('validacion');

            $this->addCss('portada');
            $this->addJs('portada');

            $htmlSlide  = '';
            $htmlCaluga = '';

            if ($slides) {
                foreach ($slides as $slide) {
                    $htmlSlide .= $this->htmlSlide($carpeta, $slide);
                }
            }

            if ($calugas) {
                foreach ($calugas as $caluga) {
                    $htmlCaluga .= $this->htmlCaluga($carpeta, $caluga);
                }
            }

            $tmpl->addSearchReplace('{#ID_SITIO#}', $idSitio);
            $tmpl->addSearchReplace('{#SLIDE#}', $htmlSlide);
            $tmpl->addSearchReplace('{#CALUGAS#}', $htmlCaluga);

            return $tmpl->getHtml();
        }
    }



    ?>
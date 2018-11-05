<?php

namespace Neorentas\Paseos\Modules\Ficha;

use Dompdf\Dompdf;
use Neorentas\Paseos\Modules\Ficha\Models\FichaModel;
use Neorentas\Paseos\Modules\Ficha\Views\FichaView;
use ZCode\Lighting\Controller\BaseController;
use ZCode\Lighting\Http\ServerInfo;

class FichaController extends BaseController
{
    public function run()
    {
        $idLocal = intval($this->request->getUrlVar(1));

        if ($idLocal > 0) {
            $this->mostrarPdf($idLocal);
        }
    }

    public function runAjax()
    {
        // TODO: Implement runAjax() method.
    }

    private function mostrarPdf($idLocal)
    {
        $docRoot   = $this->serverInfo->getData(ServerInfo::DOC_ROOT);
        $this->raw = true;

        /** @var FichaModel $model */
        $model = $this->createModel('FichaModel');

        $datosFicha = $model->obtenerDatosFicha($idLocal);

        /** @var FichaView $view */
        $view = $this->createView('FichaView');

        $html = $view->obtenerHtmlFicha($docRoot, $datosFicha);

        $pdf  = new Dompdf();

        $pdf->loadHtml($html);
        $pdf->setPaper('letter', 'portrait');
        $pdf->render();

        // $this->response = $html;

        $pdf->stream('ficha_local_'.$datosFicha->numero, ['Attachment' => 0]);
    }
}

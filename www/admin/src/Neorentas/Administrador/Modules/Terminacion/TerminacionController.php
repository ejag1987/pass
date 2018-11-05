<?php

namespace Neorentas\Administrador\Modules\Terminacion;

use Neorentas\Administrador\Modules\Terminacion\Models\TerminacionModel;
use Neorentas\Administrador\Modules\Terminacion\Views\TerminacionView;
use ZCode\Lighting\Controller\BaseController;
use ZCode\Lighting\Http\Request;

class TerminacionController extends BaseController
{
    public function run()
    {
        $idPerfil = $this->session->getString('idPerfil');
        $html     = '';

        if ($idPerfil != 1) {
            $this->response = $html;
            return;
        }

        $idSitio = $this->request->getUrlVar(1);
        $this->session->setInt('idSitio', intval($idSitio));

        /** @var TerminacionModel $model */
        $model = $this->createModel('TerminacionModel');
        $terminaciones = $model->cargarTerminaciones($idSitio);

        /** @var TerminacionView $view */
        $view = $this->createView('TerminacionView');
        $html = $view->cargarTerminaciones($idSitio, $terminaciones);

        $this->response = $html;
    }

    public function runAjax()
    {
        $accion = $this->request->getPostVar('accion', Request::STRING);

        if (strlen($accion) > 0) {
            switch ($accion) {
                case 'eliminarTerminacion':
                    $this->eliminarTerminacion();
                    break;
                case 'guardarTerminacion':
                    $this->guardarTerminacion();
                    break;
                case 'nuevaTerminacion':
                    $this->nuevaTerminacion();
                    break;
            }
        }
    }

    private function eliminarTerminacion()
    {
        $idTerminacion = $this->request->getPostVar('idTerminacion', Request::INTEGER);
        $idSitio = $this->request->getPostVar('idSitio', Request::INTEGER);

        /** @var TerminacionModel $model */
        $model = $this->createModel('TerminacionModel');
        $datos = $model->buscarTerminacionLocal($idTerminacion);

        if ($datos->total > 0) {
            $mensaje = "Esta terminación no se puede borrar por estar asociada a los siguientes locales: $datos->locales";

            $this->generateJsonResponse(false, $mensaje);
            return;
        }

        $eliminado = $model->eliminarTerminacion($idTerminacion, $idSitio);

        if (!$eliminado) {
            $this->generateJsonResponse(false, 'No se pudo eliminar la terminación.');
            return;
        }

        $this->generateJsonResponse(true, 'La terminación se eliminó');
    }

    private function guardarTerminacion()
    {
        $idSitio = $this->request->getPostVar('idSitio', Request::INTEGER);
        $terminaciones = $this->request->getPostVar('valores', Request::ARRAY_VAR);

        if (!$terminaciones || !$idSitio) {
            $this->generateJsonResponse(false, 'No se pudieron guardar las terminaciones.');
            return;
        }

        /** @var TerminacionModel $model */
        $model = $this->createModel('TerminacionModel');
        $guardado = $model->guardarTerminaciones($idSitio, $terminaciones);

        if (!$guardado) {
            $this->generateJsonResponse(false, 'No se pudieron guardar las terminaciones.');
            return;
        }

        $this->generateJsonResponse(true, 'Las terminaciones fueron guardadas.');
    }

    private function nuevaTerminacion()
    {
        /** @var TerminacionView $view */
        $view = $this->createView('TerminacionView');
        $html = $view->cargarTerminacion();

        $datos['html'] = $html;

        $this->generateJsonResponse(true, '', $datos);
    }


}
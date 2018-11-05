<?php

namespace Neorentas\Administrador\Modules\Marca;

use Neorentas\Administrador\Modules\Marca\Models\MarcaModel;
use Neorentas\Administrador\Modules\Marca\Views\MarcaView;
use Neorentas\Administrador\System\ProcesadorArchivo;
use Neorentas\Administrador\System\Variables;
use ZCode\Lighting\Controller\BaseController;
use ZCode\Lighting\Http\Request;
use ZCode\Lighting\Http\ServerInfo;

class MarcaController extends BaseController
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

        /** @var MarcaModel $model */
        $model = $this->createModel('MarcaModel');
        $marcas  = $model->cargarMarcas($idSitio);
        $carpeta = $model->cargarCarpeta($idSitio);

        /** @var MarcaView $view */
        $view = $this->createView('MarcaView');
        $html = $view->cargarMarcas($idSitio, $carpeta, $marcas);

        $this->response = $html;
    }

    public function runAjax()
    {
        $accion = $this->request->getPostVar('accion', Request::STRING);

        if (strlen($accion) > 0) {
            switch ($accion) {
                case 'nuevaMarca':
                    $this->nuevaMarca();
                    break;
                case 'subirLogo':
                    $this->subirLogo();
                    break;
                case 'borrarLogo':
                    $this->borrarLogo();
                    break;
                case 'guardarMarca':
                    $this->guardarMarca();
                    break;
                case 'borrarMarca':
                    $this->borrarMarca();
                    break;
                case 'guardarOrdenMarca':
                    $this->guardarOrdenMarca();
                    break;
            }
        }
    }

    private function nuevaMarca()
    {
        $idSitio = $this->request->getPostVar('idSitio', Request::INTEGER);

        /** @var MarcaModel $model */
        $model = $this->createModel('MarcaModel');
        $carpeta = $model->cargarCarpeta($idSitio);
        $marca   = $model->crearMarca($idSitio);

        /** @var MarcaView $view */
        $view = $this->createView('MarcaView');
        $html = $view->cargarHtmlMarca($marca, $carpeta);

        $datos['html']  = $html;
        $datos['idMarca'] = $marca->id_marca;
        $this->generateJsonResponse(true, '', $datos);
    }

    private function subirLogo()
    {
        $idMarca = $this->request->getPostVar('idMarca', Request::INTEGER);
        $idSitio = $this->request->getPostVar('idSitio', Request::INTEGER);

        /** @var MarcaModel $model */
        $model = $this->createModel('MarcaModel');
        $carpeta = $model->cargarCarpeta($idSitio);
        $dir     = Variables::DIR_UPLOAD.'/uploads/'.$carpeta.'/marcas';

        $archivo = ProcesadorArchivo::guardarArchivo('files-'.$idMarca, $dir, false, false);

        $guardar = $model->guardarLogo($idMarca, $archivo->nombre.'.'.$archivo->extension);

        if (!$guardar) {
            $archivoBorrar = $dir.'/'.$archivo->nombre.'.'.$archivo->extension;
            if (is_file($archivoBorrar)) {
                unlink($archivoBorrar);
            }

            $this->generateJsonResponse(false, 'No se ha podido guardar el logo.');
            return;
        }

        $imagenIcono = Variables::URL.'uploads/'.$carpeta.'/marcas/'.$guardar->logo;

        /** @var MarcaView $view */
        $view = $this->createView('MarcaView');
        $html = $view->htmlLogo($imagenIcono, $idMarca);

        $datos['html'] = $html;
        $datos['idMarca'] = $idMarca;

        $this->generateJsonResponse(true, '', $datos);
    }

    private function borrarLogo()
    {
        $idMarca = $this->request->getPostVar('idMarca', Request::INTEGER);
        $idSitio = $this->request->getPostVar('idSitio', Request::INTEGER);

        /** @var MarcaModel $model */
        $model = $this->createModel('MarcaModel');
        $borrar = $model->borrarLogo($idMarca);

        if (!$borrar) {
            $this->generateJsonResponse(false, 'No se ha podido borrar el logo.');
            return;
        }

        $carpeta = $model->cargarCarpeta($idSitio);
        $dir  = Variables::DIR_UPLOAD.'/uploads/'.$carpeta.'/marcas';

        $archivoBorrar = $dir.'/'.$borrar->logo;
        if (is_file($archivoBorrar)) {
            unlink($archivoBorrar);
        }

        /** @var MarcaView $view */
        $view = $this->createView('MarcaView');
        $html = $view->htmlSubirImagen($idMarca);

        $datos['html'] = $html;
        $datos['idMarca'] = $idMarca;

        $this->generateJsonResponse(true, '', $datos);
    }

    private function guardarMarca()
    {
        $idMarca = $this->request->getPostVar('idMarca', Request::INTEGER);
        $nombre  = $this->request->getPostVar('nombre-'.$idMarca, Request::STRING);
        $link    = $this->request->getPostVar('link-'.$idMarca, Request::STRING);

        /** @var MarcaModel $model */
        $model = $this->createModel('MarcaModel');
        $guardar = $model->guardarMarca($idMarca, $nombre, $link);

        if (!$guardar) {
            $this->generateJsonResponse(false, 'No se ha podido actualizar la marca.');
            return;
        }

        $this->generateJsonResponse(true, 'La marca ha sido actualizada.');
    }

    private function borrarMarca()
    {
        $idMarca = $this->request->getPostVar('idMarca', Request::INTEGER);

        /** @var MarcaModel $model */
        $model = $this->createModel('MarcaModel');
        $borrar = $model->cambiarEstadoMarca($idMarca);

        if (!$borrar) {
            $this->generateJsonResponse(false, 'No se ha podido borrar la marca.');
            return;
        }

        $this->generateJsonResponse(true, 'La marca ha sido borrada.');
    }

    private function guardarOrdenMarca()
    {
        $marcas = $this->request->getPostVar('marcas', Request::ARRAY_VAR);

        if (!$marcas) {
            $this->generateJsonResponse(false, 'Hubo un error en el sistema, no se recibieron las redes sociales.');
            return;
        }

        $totalMarcas = sizeof($marcas);
        $error      = false;

        /** @var MarcaModel $model */
        $model = $this->createModel('MarcaModel');

        for ($i = 0; $i < $totalMarcas; $i++) {
            $idMarca = intval(str_replace(('marca-'), '', $marcas[$i]));

            if (!$model->actualizarOrdenMarcas($idMarca, ($i + 1))) {

                $error = true;
                break;
            }
        }

        if ($error) {
            $this->generateJsonResponse(false, 'Hubo un error al guardar el orden de las marcas.');
            return;
        }

    }
}
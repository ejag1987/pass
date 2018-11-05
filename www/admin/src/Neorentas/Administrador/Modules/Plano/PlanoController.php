<?php

namespace Neorentas\Administrador\Modules\Plano;

use Neorentas\Administrador\Modules\Plano\Models\PlanoModel;
use Neorentas\Administrador\Modules\Plano\Views\PlanoView;
use Neorentas\Administrador\System\ProcesadorArchivo;
use Neorentas\Administrador\System\Variables;
use ZCode\Lighting\Controller\BaseController;
use ZCode\Lighting\Http\Request;

class PlanoController extends BaseController
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

        $idPlano = $this->request->getGetVar('id', Request::INTEGER);

        if ($idPlano) {
            $this->response = $this->cargarLocalesPlano($idPlano);
            return;
        }

        /** @var PlanoModel $model */
        $model = $this->createModel('PlanoModel');
        $sitio = $model->cargarSitio($idSitio);
        $planos = $model->cargarPlanos($idSitio);

        /** @var PlanoView $view */
        $view = $this->createView('PlanoView');
        $html = $view->cargarPlanos($sitio, $planos);

        $this->response = $html;
    }

    public function runAjax()
    {
        // Implement runAjax() method.
        $accion = $this->request->getPostVar('accion', Request::STRING);

        if (strlen($accion) > 0) {
            switch ($accion) {
                case 'nuevoPlano':
                    $this->nuevoPlano();
                    break;
                case 'subirPlano':
                    $this->subirPlano();
                    break;
                case 'borrarImagenPlano':
                    $this->borrarImagenPlano();
                    break;
                case 'guardarPlano':
                    $this->guardarPlano();
                    break;
                case 'borrarPlano':
                    $this->borrarPlano();
                    break;
                case 'cambiarEstadoLocal':
                    $this->cambiarEstadoLocal();
                    break;
                case 'guardarDatos':
                    $this->guardarDatos();
                    break;
                case 'subirLocal':
                    $this->subirImagenLocal();
                    break;
                case 'borrarPlanoLocal':
                    $this->borrarPlanoLocal();
                    break;
                case 'nuevoLocal':
                    $this->nuevoLocal();
                    break;
                case 'cargarContenido':
                    $this->cargarContenido();
                    break;
                case 'cambiarReservaLocal':
                    $this->cambiarReservaLocal();
                    break;
                case 'guardarOrdenPlano':
                    $this->guardarOrdenPlano();
                    break;
            }
        }
    }

    private function nuevoPlano()
    {
        $idSitio = $this->request->getPostVar('idSitio', Request::INTEGER);

        /** @var PlanoModel $model */
        $model = $this->createModel('PlanoModel');
        $carpeta = $model->cargarCarpeta($idSitio);
        $plano   = $model->crearPlano($idSitio);

        /** @var PlanoView $view */
        $view = $this->createView('PlanoView');
        $html = $view->cargarHtmlPlanos($carpeta, $plano);

        $datos['html']  = $html;
        $datos['idPlano'] = $plano->id_plano;
        $this->generateJsonResponse(true, '', $datos);
    }

    private function borrarImagenPlano()
    {
        $idPlano = $this->request->getPostVar('idPlano', Request::INTEGER);
        $idSitio = $this->request->getPostVar('idSitio', Request::INTEGER);

        /** @var PlanoModel $model */
        $model = $this->createModel('PlanoModel');
        $borrar = $model->borrarPlano($idPlano);

        if (!$borrar) {
            $this->generateJsonResponse(false, 'No se ha podido borrar el plano.');
            return;
        }

        $carpeta = $model->cargarCarpeta($idSitio);
        $dir  = Variables::DIR_UPLOAD.'/uploads/'.$carpeta.'/mapa';

        $archivoBorrar = $dir.'/'.$borrar->imagen;
        if (is_file($archivoBorrar)) {
            unlink($archivoBorrar);
        }

        /** @var PlanoView $view */
        $view = $this->createView('PlanoView');
        $html = $view->htmlSubirImagen($idPlano);

        $datos['html'] = $html;
        $datos['idPlano'] = $idPlano;

        $this->generateJsonResponse(true, '', $datos);
    }

    private function subirPlano()
    {
        $idPlano = $this->request->getPostVar('idPlano', Request::INTEGER);
        $idSitio = $this->request->getPostVar('idSitio', Request::INTEGER);

        /** @var PlanoModel $model */
        $model = $this->createModel('PlanoModel');
        $carpeta = $model->cargarCarpeta($idSitio);
        $dir     = Variables::DIR_UPLOAD.'/uploads/'.$carpeta.'/mapa';

        $archivo = ProcesadorArchivo::guardarArchivo('files-'.$idPlano, $dir, false, false);

        $guardar = $model->guardarImagenPlano($idPlano, $archivo->nombre.'.'.$archivo->extension);

        if (!$guardar) {
            $archivoBorrar = $dir.'/'.$archivo->nombre.'.'.$archivo->extension;
            if (is_file($archivoBorrar)) {
                unlink($archivoBorrar);
            }

            $this->generateJsonResponse(false, 'No se ha podido guardar el plano.');
            return;
        }

        $imagenIcono = Variables::URL.'uploads/'.$carpeta.'/mapa/'.$guardar->imagen;

        /** @var PlanoView $view */
        $view = $this->createView('PlanoView');
        $html = $view->htmlImagen($idPlano, $imagenIcono);

        $datos['html'] = $html;
        $datos['idPlano'] = $idPlano;

        $this->generateJsonResponse(true, '', $datos);
    }

    private function guardarPlano()
    {
        $idPlano            = $this->request->getPostVar('idPlano', Request::INTEGER);
        $nombre             = $this->request->getPostVar('nombre-'.$idPlano, Request::STRING);
        $pieImagen          = $this->request->getPostVar('pie-imagen-'.$idPlano, Request::STRING);
        $fechaActualizacion = $this->request->getPostVar('fecha-actualizacion-'.$idPlano, Request::STRING);

        /** @var PlanoModel $model */
        $model = $this->createModel('PlanoModel');
        $guardar = $model->guardarPlano($idPlano, $nombre, $pieImagen, $fechaActualizacion);

        if (!$guardar) {
            $this->generateJsonResponse(false, 'No se ha podido actualizar el plano.');
            return;
        }

        $this->generateJsonResponse(true, 'El plano ha sido actualizado.');
    }

    private function borrarPlano()
    {
        $idPlano = $this->request->getPostVar('idPlano', Request::INTEGER);

        /** @var PlanoModel $model */
        $model = $this->createModel('PlanoModel');
        $borrar = $model->cambiarEstadoPlano($idPlano);

        if (!$borrar) {
            $this->generateJsonResponse(false, 'No se ha podido borrar el plano.');
            return;
        }

        $this->generateJsonResponse(true, 'El plano se ha borrado.');
    }

    private function cargarLocalesPlano($idPlano)
    {
        /** @var PlanoModel $model */
        $model = $this->createModel('PlanoModel');
        $plano = $model->cargarDatosPlano($idPlano);
        $imagenPlano = Variables::URL.'/uploads/'.$plano->carpeta.'/mapa/'.$plano->imagen;
        $locales = $model->cargarLocales($idPlano);
        $terminaciones = $model->cargarTerminaciones($plano->id_sitio);

        /** @var PlanoView $view */
        $view = $this->createView('PlanoView');
        $html = $view->cargarLocales($idPlano, $imagenPlano, $locales, $plano->carpeta, $terminaciones);

        return $html;
    }

    private function cambiarEstadoLocal()
    {
        $idLocal    = $this->request->getPostVar('idLocal', Request::INTEGER);
        $disponible = $this->request->getPostVar('disponible', Request::BOOLEAN);

        if ($disponible) {
            $disponible = 0;
        } else {
            $disponible = 1;
        }

        /** @var PlanoModel $model */
        $model = $this->createModel('PlanoModel');
        $cambiar = $model->cambiarEstado($idLocal, $disponible);

        if (!$cambiar) {
            $this->generateJsonResponse(false, 'No se ha podido cambiar la disponibilidad del local.');
            return;
        }

        $this->generateJsonResponse(true, 'Disponibilidad cambiada.');
    }

    private function guardarDatos()
    {
        $idLocal            = $this->request->getPostVar('idLocal', Request::INTEGER);
        $numero             = $this->request->getPostVar('numero-'.$idLocal, Request::STRING);
        $metros             = $this->request->getPostVar('metros-'.$idLocal, Request::STRING);
        $instalaciones      = $this->request->getPostVar('instalaciones-'.$idLocal, Request::STRING);
        $nota               = $this->request->getPostVar('nota-'.$idLocal, Request::STRING);
        $pieImagen          = $this->request->getPostVar('pie-imagen-'.$idLocal, Request::STRING);
        $fechaActualizacion = $this->request->getPostVar('fecha-actualizacion-'.$idLocal, Request::STRING);
        $terminaciones      = $this->request->getPostVar('terminaciones', Request::ARRAY_VAR);

        if (strlen($numero) == 0 || strlen($metros) == 0 || strlen($instalaciones) == 0 || strlen($nota) == 0) {
            $this->generateJsonResponse(false, 'Existen datos no válidos.');
        }

        // envio datos a guardar
        /** @var PlanoModel $model */
        $model = $this->createModel('PlanoModel');
        $guardar = $model->guardarDatosLocal($idLocal, $numero, $metros, $instalaciones, $nota, $terminaciones, $pieImagen, $fechaActualizacion);

        if (!$guardar) {
            $this->generateJsonResponse(false, 'No se han podido guardar los detalles del local.');
            return;
        }

        $this->generateJsonResponse(true, 'Local guardado.');
    }

    private function subirImagenLocal()
    {
        $tipo    = $this->request->getPostVar('tipo', Request::STRING);
        $idLocal = $this->request->getPostVar('idLocal', Request::INTEGER);
        $idPlano = $this->request->getPostVar('idPlano', Request::INTEGER);

        /** @var PlanoModel $model */
        $model = $this->createModel('PlanoModel');
        $plano = $model->cargarDatosPlano($idPlano);
        $carpeta = $plano->carpeta;
        $dir     = Variables::DIR_UPLOAD.'uploads/'.$carpeta.'/mapa';

        $archivo = ProcesadorArchivo::guardarArchivo('files-'.$idLocal, $dir, false, false);

        $guardar = $model->guardarImagenLocal($idLocal, $archivo->nombre.'.'.$archivo->extension, $tipo);

        if (!$guardar) {
            $archivoBorrar = $dir.'/'.$archivo->nombre.'.'.$archivo->extension;
            if (is_file($archivoBorrar)) {
                unlink($archivoBorrar);
            }

            $this->generateJsonResponse(false, 'No se ha podido guardar el plano del local.');
            return;
        }

        $imagen = $archivo->nombre.'.'.$archivo->extension;

        switch ($tipo) {
            case "plano":
                $imagen = $guardar->imagen_ficha;
                break;
            case "logo":
                $imagen = $guardar->logo;
                break;
        }

        $imagenIcono = Variables::URL.'uploads/'.$carpeta.'/mapa/'.$imagen;

        /** @var PlanoView $view */
        $view = $this->createView('PlanoView');
        $html = $view->htmlImagen($idLocal, $imagenIcono);

        $datos['html'] = $html;
        $datos['idLocal'] = $idLocal;
        $datos['tipo'] = $tipo;

        $this->generateJsonResponse(true, '', $datos);
    }

    private function borrarPlanoLocal()
    {
        $idPlano = $this->request->getPostVar('idPlano', Request::INTEGER);
        $idLocal = $this->request->getPostVar('idLocal', Request::INTEGER);

        /** @var PlanoModel $model */
        $model = $this->createModel('PlanoModel');
        $borrar = $model->borrarPlanoLocal($idLocal);

        if (!$borrar) {
            $this->generateJsonResponse(false, 'No se ha podido borrar el plano.');
            return;
        }

        $plano = $model->cargarDatosPlano($idPlano);
        $carpeta = $plano->carpeta;
        $dir  = Variables::DIR_UPLOAD.'/uploads/'.$carpeta.'/mapa';

        $archivoBorrar = $dir.'/'.$borrar->imagen_ficha;
        if (is_file($archivoBorrar)) {
            unlink($archivoBorrar);
        }

        /** @var PlanoView $view */
        $view = $this->createView('PlanoView');
        $html = $view->htmlSubirImagen($idLocal);

        $datos['html'] = $html;
        $datos['idLocal'] = $idLocal;

        $this->generateJsonResponse(true, '', $datos);
    }

    private function nuevoLocal()
    {
        $idPlano = $this->request->getPostVar('idPlano', Request::INTEGER);

        /** @var PlanoModel $model */
        $model = $this->createModel('PlanoModel');
        $plano         = $model->cargarDatosPlano($idPlano);
        $carpeta       = $plano->carpeta;
        $local         = $model->crearLocal($idPlano);
        $terminaciones = $model->cargarTerminaciones($plano->id_sitio);

        /** @var PlanoView $view */
        $view = $this->createView('PlanoView');
        $html = $view->cargarLocal($local, $carpeta, $terminaciones);

        $datos['html']  = $html;
        $datos['idLocal'] = $local->id_local;
        $this->generateJsonResponse(true, '', $datos);
    }

    private function cargarContenido()
    {
        $idLocal = $this->request->getPostVar('idLocal', Request::INTEGER);
        $disponible = $this->request->getPostVar('disponible', Request::BOOLEAN);

        /** @var PlanoModel $model */
        $model = $this->createModel('PlanoModel');
        $local = $model->cargarLocal($idLocal);
        $terminaciones = $model->cargarTerminaciones($local->id_sitio);

        /** @var PlanoView $view */
        $view = $this->createView('PlanoView');

        if ($disponible) {
            $html = $view->localArrendado($local, $local->carpeta);
        } else {
            $html = $view->localDisponible($local, $local->carpeta, $terminaciones);
        }

        $datos['html']  = $html;
        $datos['idLocal'] = $local->id_local;
        $this->generateJsonResponse(true, '', $datos);
    }

    private function cambiarReservaLocal()
    {
        $idLocal    = $this->request->getPostVar('idLocal', Request::INTEGER);
        $reservado = $this->request->getPostVar('reservado', Request::BOOLEAN);

        if (!$reservado) {
            $reservado = 0;
        } else {
            $reservado = 1;
        }

        /** @var PlanoModel $model */
        $model = $this->createModel('PlanoModel');
        $cambiar = $model->cambiarReserva($idLocal, $reservado);

        if (!$cambiar) {
            $this->generateJsonResponse(false, 'No se ha podido cambiar la reserva del local.');
            return;
        }

        $this->generateJsonResponse(true, 'Reserva cambiada.');
    }

    private function guardarOrdenPlano()
    {
        $planos = $this->request->getPostVar('planos', Request::ARRAY_VAR);

        if (!$planos) {
            $this->generateJsonResponse(false, 'Hubo un error en el sistema, no se recibieron los planos.');
            return;
        }

        $totalPlanos = sizeof($planos);
        $error      = false;

        /** @var PlanoModel $model */
        $model = $this->createModel('PlanoModel');

        for ($i = 0; $i < $totalPlanos; $i++) {
            $idPlano = intval(str_replace(('plano-'), '', $planos[$i]));

            if (!$model->actualizarOrdenPlanos($idPlano, ($i + 1))) {

                $error = true;
                break;
            }
        }

        if ($error) {
            $this->generateJsonResponse(false, 'Hubo un error al guardar el orden de los planos.');
            return;
        }
    }
}
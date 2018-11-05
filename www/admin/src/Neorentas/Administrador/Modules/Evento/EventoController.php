<?php

namespace Neorentas\Administrador\Modules\Evento;

use Neorentas\Administrador\Modules\Evento\Models\EventoModel;
use Neorentas\Administrador\Modules\Evento\Views\EventoView;
use Neorentas\Administrador\System\ProcesadorArchivo;
use Neorentas\Administrador\System\Variables;
use ZCode\Lighting\Controller\BaseController;
use ZCode\Lighting\Http\Request;

class EventoController extends BaseController
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

        /** @var EventoModel $model */
        $model = $this->createModel('EventoModel');
        $carpeta = $model->cargarCarpeta($idSitio);
        $pagina  = $model->buscarPagina($idSitio);
        $eventos = $model->buscarEventos($idSitio);

        /** @var EventoView $view */
        $view = $this->createView('EventoView');
        $html = $view->cargarEventos($idSitio, $pagina, $carpeta, $eventos);

        $this->response = $html;
    }

    public function runAjax()
    {
        $accion = $this->request->getPostVar('accion', Request::STRING);

        if (strlen($accion) > 0) {
            switch ($accion) {
                case 'guardarPagina':
                    $this->guardarPagina();
                    break;
                case 'subirImagen':
                    $this->subirImagen();
                    break;
                case 'borrarImagen':
                    $this->borrarImagen();
                    break;
                case 'nuevoEvento':
                    $this->nuevoEvento();
                    break;
                case 'guardarEvento':
                    $this->guardarEvento();
                    break;
                case 'borrarEvento':
                    $this->borrarEvento();
                    break;
                case 'guardarOrden':
                    $this->guardarOrden();
                    break;
            }
        }
    }

    private function guardarPagina()
    {
        $idSitio  = $this->request->getPostVar('idSitio', Request::INTEGER);
        $idPagina = $this->request->getPostVar('idPagina', Request::INTEGER);
        $titulo1  = $this->request->getPostVar('titulo1', Request::STRING);
        $titulo2  = $this->request->getPostVar('titulo2', Request::STRING);

        /** @var EventoModel $model */
        $model = $this->createModel('EventoModel');
        $guardar = $model->guardarPagina($idSitio, $idPagina, $titulo1, $titulo2);

        if (!$guardar) {
            $this->generateJsonResponse(false, 'No se ha podido actualizar los datos de la página.');
            return;
        }

        $datos['idPagina'] = $guardar;

        $this->generateJsonResponse(true, 'Datos guardados.', $datos);
    }

    private function subirImagen()
    {
        $idSitio = $this->request->getPostVar('idSitio', Request::INTEGER);
        $campo   = $this->request->getPostVar('imagenSubir', Request::STRING);
        $idItem  = $this->request->getPostVar('idItem', Request::INTEGER); // pagina o evento

        /** @var EventoModel $model */
        $model = $this->createModel('EventoModel');
        $dir = 'uploads/'.$model->cargarCarpeta($idSitio);

        if ($campo == 'fondo') {
            $dir = $dir.'/fondos/';
        } else {
            $evento = $model->buscarEvento($idItem);
            $fecha  = $evento->fecha_creacion;
            $dir    = $dir.'/eventos/'.date('Y', strtotime($fecha)).'/'.date('m', strtotime($fecha)).'/';
        }

        $indice = 'files-'.$campo.'-'.$idItem;
        $directorio = Variables::DIR_UPLOAD.$dir;

        $archivo = ProcesadorArchivo::guardarArchivo($indice, $directorio, true, false);
        $nombreArchivo = $archivo->nombre.'.'.$archivo->extension;
        $guardar = $model->guardarImagen($idSitio, $idItem, $nombreArchivo, $campo);

        if (!$guardar) {
            $archivoBorrar = $directorio.$nombreArchivo;
            if (is_file($archivoBorrar)) {
                unlink($archivoBorrar);
            }

            $this->generateJsonResponse(false, 'No se ha podido guardar la imagen.');
            return;
        }

        if ($campo == 'fondo') {
            $idItem = $guardar->id_pagina;
        }

        $imagen = Variables::URL.$dir.$guardar->{$campo};

        /** @var EventoView $view */
        $view = $this->createView('EventoView');

        $html = $view->htmlimagen($imagen, $idItem, $campo);

        $datos['html']   = $html;
        $datos['idItem'] = $idItem;
        $datos['campo']  = $campo;

        $this->generateJsonResponse(true, '', $datos);
    }

    private function borrarImagen()
    {
        $idSitio = $this->request->getPostVar('idSitio', Request::INTEGER);
        $idItem  = $this->request->getPostVar('idItem', Request::INTEGER);
        $campo   = $this->request->getPostVar('campo', Request::STRING);

        /** @var EventoModel $model */
        $model = $this->createModel('EventoModel');
        $carpeta = $model->cargarCarpeta($idSitio);

        $entradaBorrada = $model->borrarImagen($idItem, $campo);

        if (!$entradaBorrada) {
            $this->generateJsonResponse(false, 'No se ha podido borrar la imagen.');
            return;
        }

        $carpetaUpload = 'uploads/'.$carpeta;

        if ($campo == 'fondo') {
            $carpetaUpload = $carpetaUpload.'/fondos/';
        } else {
            $fecha         = $entradaBorrada->fecha_creacion;
            $carpetaUpload = $carpetaUpload.'/eventos/'.date('Y', strtotime($fecha)).'/'.date('m', strtotime($fecha)).'/';
        }

        $dir = Variables::DIR_UPLOAD.$carpetaUpload;

        $archivoBorrar = $dir.'/'.$entradaBorrada->{$campo};
        if (is_file($archivoBorrar)) {
            unlink($archivoBorrar);
        }

        /** @var EventoView $view */
        $view = $this->createView('EventoView');
        $html = $view->htmlSubirImagen($idItem, $campo);

        $datos['html'] = $html;

        $this->generateJsonResponse(true, '', $datos);
    }

    private function nuevoEvento()
    {
        $idSitio = $this->request->getPostVar('idSitio', Request::INTEGER);

        /** @var EventoModel $model */
        $model = $this->createModel('EventoModel');
        $carpeta = $model->cargarCarpeta($idSitio);
        $evento  = $model->crearEvento($idSitio);

        /** @var EventoView $view */
        $view = $this->createView('EventoView');
        $html = $view->cargarHtmlEvento($evento, $carpeta);

        $datos['html']  = $html;
        $this->generateJsonResponse(true, '', $datos);
    }

    private function guardarEvento()
    {
        $idEvento    = $this->request->getPostVar('idEvento', Request::INTEGER);
        $titulo      = $this->request->getPostVar('titulo-'.$idEvento, Request::STRING);
       // $alias      = $this->request->getPostVar('alias-'.$idEvento, Request::STRING);
        $descripcion = $this->request->getPostVar('descripcion-'.$idEvento, Request::STRING);
        $contenido = $this->request->getPostVar('contenido-'.$idEvento, Request::STRING);
        $fecha       = $this->request->getPostVar('fecha-'.$idEvento, Request::STRING);
        $horario     = $this->request->getPostVar('horario-'.$idEvento, Request::STRING);

        if (strlen($titulo) > 50){

            $alias = substr($titulo, 0, 50);
            $ultimoNumero = strripos($alias, " ");
            $alias = substr($titulo, 0, $ultimoNumero);
        }
        else {
            $alias = $titulo;
        }

        $noPermitida= array("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","Ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹");
        $permitida= array("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E");
        $alias = str_replace($noPermitida, $permitida, $alias);
        $alias = str_replace(" ", "-", $alias);
        $alias = strtolower($alias);
        $noSignos= array("|","!","#","$","&","/","(",")","=","?","¡","'","¿",",",".",";",":","\"");
        $alias = str_replace($noSignos, "", $alias);
     

        /** @var EventoModel $model */
        $model = $this->createModel('EventoModel');
        $guardar = $model->guardarEvento($idEvento, $titulo, $alias, $descripcion, $contenido, $fecha, $horario);

        if (!$guardar) {
            $this->generateJsonResponse(false, 'No se ha podido actualizar el evento.');
            return;
        }

        $this->generateJsonResponse(true, 'Evento guardado.');
    }

    private function borrarEvento()
    {
        $idSitio  = $this->request->getPostVar('idSitio', Request::INTEGER);
        $idEvento = $this->request->getPostVar('idEvento', Request::INTEGER);

        /** @var EventoModel $model */
        $model  = $this->createModel('EventoModel');
        $evento = $model->borrarEvento($idEvento);

        if (!$evento) {
            $this->generateJsonResponse(false, 'No se ha podido borrar el evento.');
            return;
        }

        $carpeta = $model->cargarCarpeta($idSitio);

        $fecha         = $evento->fecha_creacion;
        $carpetaUpload = 'uploads/'.$carpeta.'/eventos/'.date('Y', strtotime($fecha)).'/'.date('m', strtotime($fecha)).'/';
        $dir           = Variables::DIR_UPLOAD.$carpetaUpload;

        $archivoBorrar = $dir.$evento->foto;
        if (is_file($archivoBorrar)) {
            unlink($archivoBorrar);
        }

        $this->generateJsonResponse(true, 'El evento ha sido borrado.');
    }

    private function guardarOrden()
    {
        $eventos = $this->request->getPostVar('eventos', Request::ARRAY_VAR);

        if (!$eventos) {
            $this->generateJsonResponse(false, 'Hubo un error en el sistema, no se recibieron los eventos.');
            return;
        }

        $totalEventos = sizeof($eventos);
        $error        = false;

        /** @var EventoModel $model */
        $model  = $this->createModel('EventoModel');

        for ($i = 0; $i < $totalEventos; $i++) {
            $idEvento = intval(str_replace(('evento-'), '', $eventos[$i]));

            if (!$model->guardarOrden($idEvento, ($i + 1))) {
                $error = true;
                break;
            }
        }

        if ($error) {
            $this->generateJsonResponse(false, 'Hubo un error al guardar el orden de los eventos.');
            return;
        }

        $this->generateJsonResponse(true, 'Eventos reordenados.');
    }
}
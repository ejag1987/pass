<?php

namespace Neorentas\Administrador\Modules\Usuario;

use Neorentas\Administrador\Modules\Usuario\Models\UsuarioModel;
use Neorentas\Administrador\Modules\Usuario\Views\UsuarioView;
use ZCode\Lighting\Controller\BaseController;
use ZCode\Lighting\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class UsuarioController extends BaseController
{
    public function run()
    {
        $idPerfil = $this->session->getString('idPerfil');
        $alias    = $this->request->getUrlVar(1);
        $html     = '';

        /** @var UsuarioModel $model */
        $model = $this->createModel('UsuarioModel');

        if ($alias == 'excel') {
            $this->descargaExcel();
            return;
        }

        /** @var UsuarioView $view */
        $view = $this->createView('UsuarioView');

        switch ($idPerfil) {
            case  1:
                $usuarios = $model->buscarUsuarios();
                $html = $view->cargarListado($usuarios);
                break;
            case 3:
                // cargar los usuarios arrendatarios
                $usuarios = $model->buscarUsuariosArrendatarios();
                $html = $view->cargarListadoArendatarios($usuarios);
                break;
        }

        $this->response = $html;
    }

    public function runAjax()
    {
        $accion = $this->request->getPostVar('accion', Request::STRING);

        if (strlen($accion) > 0) {
            switch ($accion) {
                case 'editarUsuario':
                    $this->editarUsuario();
                    break;
                case 'guardarDatos':
                    $this->guardarDatos();
                    break;
                case 'borrarUsuario':
                    $this->borrarUsuario();
                    break;
                case 'verificarUsuario':
                    $this->verificarUsuario();
                    break;
            }
        }
    }

    private function editarUsuario()
    {
        $idUsuario = $this->request->getPostVar('idUsuario', Request::INTEGER);

        /** @var UsuarioModel $model */
        $model = $this->createModel('UsuarioModel');
        $perfiles = $model->buscarPerfiles();
        $usuario = $model->buscarUsuario($idUsuario);

        /** @var UsuarioView $view */
        $view = $this->createView('UsuarioView');
        $html = $view->cargarUsuario($perfiles, $usuario);

        $datos['html']  = $html;

        $this->generateJsonResponse(true, '', $datos);
    }

    private function guardarDatos()
    {
        $idUsuario  = $this->request->getPostVar('idUsuario', Request::INTEGER);
        $idPerfil   = $this->request->getPostVar('perfil', Request::INTEGER);
        $nombre     = $this->request->getPostVar('nombre', Request::STRING);
        $apellido   = $this->request->getPostVar('apellido', Request::STRING);
        $rut        = $this->request->getPostVar('rut', Request::STRING);
        $mail       = $this->request->getPostVar('mail', Request::STRING);
        $telefono   = $this->request->getPostVar('telefono', Request::STRING);
        $contrasena = $this->request->getPostVar('contrasena', Request::STRING);

        /** @var UsuarioModel $model */
        $model = $this->createModel('UsuarioModel');

        // validar usuarios
        if ($idPerfil == 0 && strlen(trim($nombre)) == 0 && strlen(trim($apellido)) == 0 && strlen(trim($mail)) == 0 ) {
            $this->generateJsonResponse(false, 'Existen campos no validos y/o vacíos.');
            return;
        }

        $usuarioBD = $model->buscarUsuarioRegistrado($mail);

        if ($idUsuario == 0) {
            if ($usuarioBD) {
                $this->generateJsonResponse(false, 'El usuario ya está registrado.');
                return;
            }

            if ($idUsuario == 0) {
                if ($contrasena == '') {
                    $this->generateJsonResponse(false, 'Debe registrar una contraseña para el usuario.');
                    return;
                }
            }
        }

        $pass = '';

        if ($contrasena != '') {
            $pass = password_hash($contrasena, PASSWORD_BCRYPT);
        }

        $usuario = $model->guardarUsuario($idUsuario, $idPerfil, $nombre, $apellido, $rut, $mail, $telefono, $pass);

        if (!$usuario) {
            $this->generateJsonResponse(false, 'No se ha podido guardar el usuario.');
            return;
        }

        /** @var UsuarioView $view */
        $view = $this->createView('UsuarioView');
        $html = $view->listadoUsuario($usuario);

        $datos['html'] = $html;
        $datos['idUsuario'] = $usuario->id_usuario;

        $this->generateJsonResponse(true, 'Usuario guardado.', $datos);
    }

    private function borrarUsuario()
    {
        $idUsuario = $this->request->getPostVar('idUsuario', Request::INTEGER);

        if (!($idUsuario > 0)) {
            $this->generateJsonResponse(false, 'No se ha podido borrar el usuario.');
            return;
        }

        /** @var UsuarioModel $model */
        $model = $this->createModel('UsuarioModel');
        $usuario = $model->borrarUsuario($idUsuario);

        if (!$usuario) {
            $this->generateJsonResponse(false, 'No se ha podido borrar el usuario.');
            return;
        }

        $datos['idUsuario'] = $idUsuario;

        $this->generateJsonResponse(true, 'Usuario borrado.', $datos);
    }

    private function verificarUsuario()
    {
        $idUsuario = $this->request->getPostVar('idUsuario', Request::INTEGER);

        /** @var UsuarioModel $model */
        $model = $this->createModel('UsuarioModel');
        $usuario = $model->validarUsuario($idUsuario);

        if (!$usuario) {
            $this->generateJsonResponse(false, 'No se ha podido validar el usuario.');
            return;
        }

        $perfiles = $model->buscarPerfiles();

        /** @var UsuarioView $view */
        $view = $this->createView('UsuarioView');
        $htmlEditar = $view->cargarUsuario($perfiles, $usuario);
        $htmlLista = $view->listadoUsuario($usuario);

        $datos['htmlEditar'] = $htmlEditar;
        $datos['htmlLista']  = $htmlLista;
        $datos['idUsuario']  = $idUsuario;

        $this->generateJsonResponse(true, 'Usuario validado.', $datos);
    }

    private function descargaExcel()
    {
        $this->raw = true;

        /** @var UsuarioModel $model */
        $model    = $this->createModel('UsuarioModel');
        $usuarios = $model->buscarUsuariosArrendatarios();

        $spreadsheet = new Spreadsheet();
        //var_dump($spreadsheet);

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'UID')->getColumnDimension('A')->setAutoSize(true);
        $sheet->setCellValue('B1', 'Nombre');
        $sheet->setCellValue('C1', 'Apellido');
        $sheet->setCellValue('D1', 'E-mail');
        $sheet->setCellValue('E1', 'Sitio de registro');
        $sheet->setCellValue('F1', 'Verificado')->getColumnDimension('F')->setAutoSize(true);
        $sheet->setCellValue('G1', 'Fecha de registro');
        $sheet->setCellValue('H1', 'Fecha de verificación');

        foreach ($usuarios as $index => $usuario) {
            //var_dump($index);
            //var_dump($usuario);
            $fechaCreacion = date('d-m-Y H:i:s', strtotime($usuario->fecha_creacion));

            // verificacion
            switch ($usuario->verificado) {
                case 0:
                default:
                    $verificacion = 'NO';
                    $fechaVerificacion = '';
                    break;
                case 1:
                    $verificacion = 'SI';
                    $fechaVerificacion = date('d-m-Y H:i:s', strtotime($usuario->fecha_verificacion));
                    break;
            }
			
			$sitio = 'Administrador';
            if ($usuario->sitio) {
                $sitio = $usuario->sitio;
            }

            $sheet->setCellValue('A'.($index+2), $usuario->id_usuario);
            $sheet->setCellValue('B'.($index+2), $usuario->nombre)->getColumnDimension('D')->setAutoSize(true);
            $sheet->setCellValue('C'.($index+2), $usuario->apellido)->getColumnDimension('D')->setAutoSize(true);
            $sheet->setCellValue('D'.($index+2), $usuario->email)->getColumnDimension('D')->setAutoSize(true);
            $sheet->setCellValue('E'.($index+2), $sitio)->getColumnDimension('E')->setAutoSize(true);
            $sheet->setCellValue('F'.($index+2), $verificacion);
            $sheet->setCellValue('G'.($index+2), $fechaCreacion)->getColumnDimension('G')->setAutoSize(true);
            $sheet->setCellValue('H'.($index+2), $fechaVerificacion)->getColumnDimension('H')->setAutoSize(true);


        }

        $sheet->getStyle("A1:H1")->getFont()->setBold(true);

        $archivo = date('Y-m-d H:i:s').'-arrendatarios.xls';

        // redirect output to client browser
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$archivo.'"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
        $writer->save('php://output');
    }
}
<?php

namespace Neorentas\Paseos\Modules\Pagina\Controllers;

use Neorentas\Paseos\Modules\Pagina\Models\PaginaModel;
use Neorentas\Paseos\Modules\Pagina\Views\PaginaView;
use Neorentas\Paseos\System\Variables;
use ZCode\Lighting\Controller\BaseController;
use ZCode\Lighting\Http\Request;

class LoginController extends BaseController
{
    public function run()
    {
        // Implement run() method.
    }

    public function runAjax()
    {
        $accion = $this->request->getPostVar('accion', Request::STRING);

        if ($accion !== null && strlen($accion) > 0) {
            switch ($accion) {
                case 'validarUsuario':
                    $this->comprobarUsuario();
                    break;
                case 'cargarRegistro':
                    $this->cargarRegistro();
                    break;
                case 'cargarLogin':
                    $this->cargarLogin();
                    break;
                case 'registrarUsuario':
                    $this->registrarUsuario();
                    break;
                case 'cargarPaginaLogin':
                    $this->cargarPaginaLogin();
                    break;
                case 'validarCorreo':
                    $this->validarCorreo();
                    break;
                case 'reenvioCorreo':
                    $this->reenvioCorreo();
                    break;
                case 'cargarRecordar':
                    $this->cargarRecordar();
                    break;
                case 'recordarUsuario':
                    $this->recordarUsuario();
                    break;
                case 'cambiarPass':
                    $this->cambiarPass();
                    break;
                case 'cambiarContrasena':
                    $this->cambiarContrasena();
                    break;
            }

            return $this->response;
        }

        return false;
    }

    private function cargarRegistro()
    {
        /** @var PaginaView $view */
        $view = $this->createView('PaginaView');
        $html = $view->cargarRegistro();

        $datos['html'] = $html;

        $datos['link1Text'] = 'Recordar contraseña';
        $datos['link1']     = 'Recordar';

        $datos['link2Text'] = 'Ingresar';
        $datos['link2']     = 'Login';

        $datos['titulo'] = 'Registro';

        $this->generateJsonResponse(true, '', $datos);
    }

    private function comprobarUsuario()
    {
        $usuario  = $this->request->getPostVar('email', Request::STRING);
        $password = $this->request->getPostVar('contrasena', Request::STRING);

        if (strlen($usuario) === 0 || strlen($password) === 0) {
            $this->generateJsonResponse(false, 'Debes ingresar el email y/o contraseña.');
            return;
        }

        /** @var PaginaModel $model */
        $model   = $this->createModel('PaginaModel');
        $usuario = $model->obtenerUsuario($usuario);

        if ($usuario === null || !$usuario) {
            $this->generateJsonResponse(false, 'Email y/o contraseña incorrectos.', null);
            return;
        }

        if ($usuario->verificado == 0) {
            $this->generateJsonResponse(false, '<div id="mensaje">El usuario debe ser verificado.</div><div class="reenvio">Reenviar correo</div>', null);
            return;
        }

        $idUsuario = intval($usuario->id_usuario);

        if (!password_verify($password, $usuario->contrasena)) {
            $this->generateJsonResponse(false, 'Email y/o contraseña incorrectos.', null);
            return;
        }

        // guardar el registro de exito
        $this->session->setBoolean('auth', true);
        $this->session->setInt('idLogin', $idUsuario);
        $this->session->setString('usuario', $usuario->nombre);

        $this->generateJsonResponse(true, '');
    }

    private function cargarLogin()
    {
        /** @var PaginaView $view */
        $view = $this->createView('PaginaView');
        $html = $view->cargarLogin();

        $datos['html'] = $html;

        $datos['link1Text'] = 'Recordar contraseña';
        $datos['link1']     = 'Recordar';

        $datos['link2Text'] = 'Registrar';
        $datos['link2']     = 'Registro';

        $datos['titulo'] = 'Iniciar sesión';

        $this->generateJsonResponse(true, '', $datos);
    }

    private function cargarPaginaLogin()
    {
        /** @var PaginaView $view */
        $view = $this->createView('PaginaView');
        $html = $view->cargarPaginaLogin();

        $datos['html'] = $html;

        $datos['link1Text'] = 'Recordar contraseña';
        $datos['link1']     = 'Recordar';

        $datos['link2Text'] = 'Ingresar';
        $datos['link2']     = 'Login';

        $datos['titulo'] = 'Iniciar sesión';

        $this->generateJsonResponse(true, '', $datos);
    }

    private function registrarUsuario()
    {
        $nombre     = $this->request->getPostVar('nombre', Request::STRING);
        $apellido   = $this->request->getPostVar('apellido', Request::STRING);
        $email      = $this->request->getPostVar('email', Request::STRING);
        $rut        = $this->request->getPostVar('rut', Request::STRING);
        $telefono   = $this->request->getPostVar('telefono', Request::STRING);
        $contrasena = $this->request->getPostVar('contrasena', Request::STRING);

        /** @var PaginaModel $model */
        $model = $this->createModel('PaginaModel');
        $usuario = $model->verificarUsuario($email);

        if ($usuario) {
            if ($usuario->eliminado == 1) {
                $this->generateJsonResponse(false, 'Tenemos un problema. Por favor comunicate con nosotros por medio del formulario de contacto.', null);
                return;
            }

            if ($usuario->verificado == 0) {
                $this->generateJsonResponse(false, '<div id="mensaje">El usuario debe ser verificado.</div><div class="reenvio">Reenviar correo de verificacion</div>', null);
                return;
            }

            $this->generateJsonResponse(false, 'El usuario ya se encuentra registrado.', null);
            return;
        }

        $pass = password_hash($contrasena, PASSWORD_BCRYPT);

        $guardarDatos = $model->guardarDatos($nombre, $apellido, $email, $rut, $telefono, $pass);

        if (!$guardarDatos) {
            $this->generateJsonResponse(false, 'No se ha podido registrar el usuario.', null);
            return;
        }

        $envioCorreo = $this->envioCorreoRegistro($guardarDatos);

        if (!$envioCorreo) {
            $this->generateJsonResponse(false, 'No se ha podido enviar el correo.', null);
            return;
        }

        $datos['html'] = "<h4 class=\"subtitle modal-descripcion\">¡Bienvenido! Su usuario ha sido creado con éxito.<br>" .
            "Revisa tu correo electrónico para finalizar.</h4>" .
            "<p>(Si no ha recibido un correo de confirmación revise la carpeta de correo spam).</p>";

        $this->generateJsonResponse(true, 'Muchas gracias por registrarse.', $datos);
    }

    private function envioCorreoRegistro($idUsuario)
    {
        /** @var PaginaModel $model */
        $model = $this->createModel('PaginaModel');
        $sitio = $model->buscarSitio(Variables::ID_SITIO);
        $usuario = $model->buscarUsuario($idUsuario);

        $codigo = $this->generarCodigoUnico($idUsuario, $sitio->id_sitio, 1);

        $actualizarCodigo = $model->actualizarCodigo($idUsuario, $codigo);

        if (!$actualizarCodigo) {
            return false;
        }

        $asunto = 'Registro de usuario '.$sitio->nombre.'.';
        $nombreMail = 'Resgistro de usuario';

        $url = explode('?',$_SERVER['HTTP_REFERER']);
        $link = $url[0].'?c='.$codigo;

        $titulo = "Bienvenido/a $usuario->nombre $usuario->apellido";
        $texto  = "<p>Para completar tu registro haz click en el siguiente enlace:</p>";
        $texto .= "<a href=\"".$link."\">".$link."</a>";
        $texto .= "<p>O cópialo y pégalo en tu navegador para validar tu cuenta.</p>";
        $texto .= "<p style=\"font-size: 20px; line-height: 24px;\">Ven a ser parte activa de nuestra comunidad y acceder a todos nuestros beneficios.</p>";

        $sendMail = $this->enviarCorreo($sitio, $usuario, $asunto, $titulo, $texto, $nombreMail);

        return $sendMail;
    }

    private function generarCodigoUnico($idUsuario, $idSitio, $validacion)
    {
        /** @var PaginaModel $model */
        $model = $this->createModel('PaginaModel');

        do {
            $validador   = strtoupper(md5($idUsuario.$idSitio.$validacion));
            $validador   = substr($validador, 0, 10);
            $codigo      = strtoupper(md5(uniqid(rand(), true)));
            $codigoFinal = $validador.'-'.$codigo;

            // Verificar que el código no exista en la base de datos
            $usuario = $model->buscarCodigo($codigoFinal);
        } while ($usuario !== null);

        return $codigoFinal;
    }

    private function validarCorreo()
    {
        $codigo  = $this->request->getPostVar('codigo', Request::STRING);
		
        /** @var PaginaModel $model */
        $model   = $this->createModel('PaginaModel');
        $usuario = $model->buscarCodigo($codigo);

        if (!$usuario) {
            $datos['titulo'] = 'Validación de usuario';
            $datos['html']   = '<h4 class="subtitle modal-descripcion">El código no corresponde a un usuario registrado.</h4>';

            $this->generateJsonResponse(false, '', $datos);
            return;
        }

        if ($usuario->verificado == 1) {
            $datos['titulo'] = 'Hola '.$usuario->nombre.' '.$usuario->apellido;
            $datos['html']   = '<h4 class="subtitle modal-descripcion">¡Bienvenido! <br>El registro ha culminado con éxito.</h4>';

            $this->generateJsonResponse(false, '', $datos);
            return;
        }
		
        // validar fecha
        $actual = new \DateTime(date('Y-m-d H:i:s'));
        $fechaEnvio = new \DateTime($usuario->fecha_envio_correo);
        $interval = $actual->diff($fechaEnvio);

        if ($interval->days > 30) {
            $envioCorreo = $this->envioCorreoRegistro($usuario->id_usuario);

            $datos['titulo'] = 'Hola '.$usuario->nombre.' '.$usuario->apellido;

            if (!$envioCorreo) {
                $datos['html'] = '<h4 class="subtitle modal-descripcion">No se ha podido enviar el correo.</h4>';
                $this->generateJsonResponse(false, '', $datos);
                return;
            }

            $datos['html'] = '<h4 class="subtitle modal-descripcion">El código excede el tiempo de validación, se ha reenviado el código.</h4>';

            $this->generateJsonResponse(true, '', $datos);
            return;
        }

        $validacion = $model->validarUsuario($usuario->id_usuario);

        if (!$validacion) {
            $datos['titulo'] = 'Hola '.$usuario->nombre.' '.$usuario->apellido;
            $datos['html']   = '<h4 class="subtitle modal-descripcion">No se ha podido verificar el usuario</h4>';

            $this->generateJsonResponse(false, '', $datos);
            return;
        }

        $datos['titulo'] = 'Hola '.$usuario->nombre.' '.$usuario->apellido;
        $datos['html']   = '<h4 class="subtitle modal-descripcion">¡Bienvenido! <br>El registro ha culminado con éxito.</h4>';

        $this->generateJsonResponse(false, '', $datos);
        return;
    }

    private function reenvioCorreo()
    {
        $email = $this->request->getPostVar('email', Request::STRING);

        /** @var PaginaModel $model */
        $model = $this->createModel('PaginaModel');
        $usuario = $model->buscarUsuarioCorreo($email);

        $envioCorreo = $this->envioCorreoRegistro($usuario->id_usuario);

        if (!$envioCorreo) {
            $this->generateJsonResponse(false, 'No se ha podido enviar el correo.', null);
            return;
        }

        $this->generateJsonResponse(true, 'El correo ha sido reenviado.');
    }

    private function cargarRecordar()
    {
        /** @var PaginaView $view */
        $view = $this->createView('PaginaView');
        $html = $view->cargarRecordar();

        $datos['html']     = $html;

        $datos['link1Text'] = 'Ingresar';
        $datos['link1']     = 'Login';

        $datos['link2Text'] = 'Registrar';
        $datos['link2']     = 'Registro';

        $datos['titulo']   = 'Recordar contraseña';

        $this->generateJsonResponse(true, '', $datos);
    }

    private function recordarUsuario()
    {
        $email = $this->request->getPostVar('email', Request::STRING);

        /** @var PaginaModel $model */
        $model = $this->createModel('PaginaModel');
        $usuario = $model->buscarUsuarioCorreo($email);

        if (!$usuario) {
            $this->generateJsonResponse(false, 'Error: el e-mail no se ha encontrado en nuestros registros.');
            return;
        }

        if ($usuario->verificado == 0) {
            $this->generateJsonResponse(false, '<div id="mensaje">El usuario debe ser verificado.</div> <div class="reenvio">Reenviar correo</div>', null);
            return;
        }

        $envioCorreo = $this->envioCorreoRecordar($usuario->id_usuario);

        if (!$envioCorreo) {
            $this->generateJsonResponse(false, 'No se ha podido enviar el correo.', null);
            return;
        }

        $html  = "<h4 class=\"subtitle modal-descripcion\">Tu nueva contraseña ha sido enviada al correo $usuario->email.</h4>";
        $html .= "<p class=''>(Si no ha recibido un correo de confirmación revise la carpeta de correo spam).</p>";
        $datos['html'] = $html;

        $this->generateJsonResponse(true, '', $datos);
    }

    private function envioCorreoRecordar($idUsuario)
    {
        /** @var PaginaModel $model */
        $model = $this->createModel('PaginaModel');
        $sitio = $model->buscarSitio(Variables::ID_SITIO);
        $usuario = $model->buscarUsuario($idUsuario);

        $codigo = $this->generarCodigoUnico($idUsuario, $sitio->id_sitio, 1);

        $actualizarCodigo = $model->actualizarCodigo($idUsuario, $codigo);

        if (!$actualizarCodigo) {
            return false;
        }

        $asunto = 'Recordar contraseña';
        $url = explode('?',$_SERVER['HTTP_REFERER']);
        $link = $url[0].'?p='.$codigo;

        $nombreMail = 'Recuperar contraseña';
        $titulo = "Estimado $usuario->nombre $usuario->apellido";

        $texto  = "<p>Para crear tu nueva clave haz click en el siguiente enlace: </p>";
        $texto .= "<a href=\"".$link."\">".$link."</a>";
        $texto .= "<p>O cópialo y pégalo en tu navegador.</p>";
        $texto .= "<p style=\"font-size: 20px; line-height: 24px;\">¡Te esperamos!</p>";

        $sendMail = $this->enviarCorreo($sitio, $usuario, $asunto, $titulo, $texto, $nombreMail);

        if (!$sendMail) {
            $this->generateJsonResponse(false, 'No se ha podido enviar el correo..');
            return false;
        }

        return $sendMail;
    }

    private function enviarCorreo($sitio, $usuario, $asunto, $titulo, $texto, $nombreMail)
    {
        $mail = new \PHPMailer();

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->SMTPDebug = 0;
        $mail->Host = 'localhost';                            // Specify main and backup SMTP servers
        $mail->SMTPAuth = false;
        $mail->SMTPSecure = false;
        $mail->CharSet = 'UTF-8';

        //$mail->setFrom($sitio->correo, $sitio->nombre);
        $mail->setFrom('registro@paseoschile.cl', $sitio->nombre);

        $mail->addAddress($usuario->email, $usuario->nombre.' '.$usuario->apellido);
        $mail->isHTML(true);

        $mail->Subject = $asunto;

        /** @var PaginaView $view */
        $view = $this->createView('PaginaView');
        $html = $view->cargarHtmlMail($nombreMail, $sitio, $titulo, $texto);

        $mail->Body = $html;

        if(!$mail->send()) {
            //echo 'Mailer Error: ' . $mail->ErrorInfo;
            return false;
        }

        /** @var PaginaModel $model */
        $model = $this->createModel('PaginaModel');
        $actualizacion = $model->actualizarEnvioCorreo($usuario->id_usuario);

        if (!$actualizacion) {
            return false;
        }

        return true;
    }

    private function cambiarPass()
    {
        $token = $this->request->getPostVar('token', Request::STRING);

        /** @var PaginaModel $model */
        $model   = $this->createModel('PaginaModel');
        $usuario = $model->buscarCodigo($token);

        if (!$usuario) {
            $datos['titulo'] = 'Cambio de contraseña';
            $datos['html'] = '<h4 class="subtitle modal-descripcion">Error: no es posible realizar el cambio de contraseña.</h4>';

            $this->generateJsonResponse(false, '', $datos);
            return;
        }

        if ($usuario->verificado == 0) {
            $datos['titulo'] = 'Hola '.$usuario->nombre.' '.$usuario->apellido;
            $datos['html'] = '<h4 class="subtitle modal-descripcion">El usuario debe ser verificado. <div class="reenvio">Reenviar correo</div></h4>';

            $this->generateJsonResponse(false, '', $datos);
            return;
        }

        // validar fecha
        $actual = new \DateTime(date('Y-m-d H:i:s'));
        $fechaEnvio = new \DateTime($usuario->fecha_envio_correo);
        $interval = $actual->diff($fechaEnvio);

        if ($interval->h > 24) {
            $reenvio = $this->envioCorreoRecordar($usuario->id_usuario);
            $datos['titulo'] = 'Hola '.$usuario->nombre.' '.$usuario->apellido;

            if (!$reenvio) {
                $datos['html'] = '<h4 class="subtitle modal-descripcion">No se ha podido enviar el correo.</h4>';
                $this->generateJsonResponse(false, '', $datos);
                return;
            }

            $datos['html'] = '<h4 class="subtitle modal-descripcion">El código excede el tiempo de cambio de contraseña, se ha reenviado el correo.</h4>';

            $this->generateJsonResponse(true, '', $datos);
            return;
        }

        /** @var PaginaView $view */
        $view = $this->createView('PaginaView');
        $html = $view->cargarCambioContraseña($usuario->id_usuario);

        $datos['titulo'] = 'Cambiar contraseña';
        $datos['html']   = $html;

        $this->generateJsonResponse(true, '', $datos);
        return;
    }

    private function cambiarContrasena()
    {
        $contrasena = $this->request->getPostVar('contrasena', Request::STRING);
        $token      = $this->request->getPostVar('token', Request::STRING);
        $idUsuario  = $this->request->getPostVar('idUsuario', Request::INTEGER);

        if (!$contrasena || !$idUsuario || $token == '') {
            $this->generateJsonResponse(false, 'Error: no es posible realizar el cambio de contraseña.');
            return;
        }

        /** @var PaginaModel $model */
        $model   = $this->createModel('PaginaModel');
        $usuario = $model->buscarCodigo($token);

        if ($usuario->id_usuario != $idUsuario) {
            $this->generateJsonResponse(false, 'Error: no es posible realizar el cambio de contraseña.');
            return;
        }

        $pass = password_hash($contrasena, PASSWORD_BCRYPT);

        $cambioPass = $model->cambiarPass($idUsuario, $pass);

        if (!$cambioPass) {
            $this->generateJsonResponse(false, 'No se ha podido cambiar la contraseña.', null);
            return;
        }

        $datos['html'] = "<h4 class=\"subtitle modal-descripcion\">Su contraseña ha sido actualizada con éxito.</h4>";

        $this->generateJsonResponse(true, 'Su contraseña ha sido actualizada con éxito.', $datos);
    }
}
<?php

namespace Neorentas\Administrador\Modules\Login;

use Neorentas\Administrador\Modules\Login\Models\LoginModel;
use Neorentas\Administrador\Modules\Login\Views\LoginView;
use ZCode\Lighting\Controller\BaseController;
use ZCode\Lighting\Http\Request;

class LoginController extends BaseController
{
    public function run()
    {
        //var_dump(password_hash('CR;j?h,V;(,~', PASSWORD_BCRYPT));
        $this->mostrarLogin();
    }

    public function runAjax()
    {
        $accion = $this->request->getPostVar('accion', Request::STRING);

        if (strlen($accion) > 0) {
            switch ($accion) {
                case 'validarUsuario':
                    $this->comprobarUsuario();
                    break;
            }
        }
    }

    private function mostrarLogin()
    {
        /** @var LoginView $view */
        $view = $this->createView('LoginView');

        $html = $view->mostrarLogin();
        $this->response = $html;
    }

    private function comprobarUsuario()
    {
        $usuario  = $this->request->getPostVar('usuario', Request::STRING);
        $password = $this->request->getPostVar('password', Request::STRING);

        if (strlen($usuario) === 0 || strlen($password) === 0) {
            $this->generateJsonResponse(false, 'Debes ingresar el usuario y/o contraseña.');
            return;
        }

        /** @var LoginModel $model */
        $model   = $this->createModel('LoginModel');
        $usuario = $model->obtenerUsuario($usuario);

        if ($usuario === null || !$usuario) {
            $this->generateJsonResponse(false, 'Usuario y/o contraseña incorrectos', null);
            return;
        }

        $idUsuario = intval($usuario->id_usuario);

        if (!password_verify($password, $usuario->contrasena)) {
            $this->generateJsonResponse(false, 'Usuario y/o contraseña incorrectos', null);
            return;
        }

        // guardar el registro de exito
        $this->session->setBoolean('auth', true);
        $this->session->setInt('idUsuario', $idUsuario);
		$this->session->setInt('idPerfil', intval($usuario->id_perfil));
        $this->session->setString('usuario', $usuario->email);
        $this->session->setString('numbre', $usuario->nombre.' '.$usuario->apellido);

        $this->generateJsonResponse(true, '');
    }
}
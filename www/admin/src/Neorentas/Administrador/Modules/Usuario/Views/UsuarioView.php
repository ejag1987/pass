<?php

namespace Neorentas\Administrador\Modules\Usuario\Views;

use Neorentas\Administrador\System\Variables;
use ZCode\Lighting\View\BaseView;

class UsuarioView extends BaseView
{
    public function cargarListado($usuarios)
    {
        $tmpl = $this->loadTemplate('listado');

        $this->addGlobalJs('inputmask');
        $this->addGlobalJs('jquery.inputmask');

        $this->addGlobalCss('select2');
        $this->addGlobalJs('select2.full.min');
        $this->addGlobalJs('es');

        $this->addGlobalCss('jquery-confirm');
        $this->addGlobalJs('jquery-confirm');

        $this->addGlobalJs('validacion');

        $this->addCss('usuarios');
        $this->addJs('usuario');

        $htmlItem = '';
        if($usuarios) {
            foreach ($usuarios as $usuario) {
                $htmlItem .= $this->listadoUsuario($usuario);
            }
        }

        $tmpl->addSearchReplace('{#USUARIOS#}', $htmlItem);

        return $tmpl->getHtml();
    }

    public function cargarListadoArendatarios($usuarios)
    {
        $tmpl = $this->loadTemplate('listado_arrendatarios');

        $this->addCss('usuarios');

        $this->addJs('arrendatarios');
        $this->addGlobalJs('jquery.ba-throttle-debounce.min');

        $htmlItem = '';
        if($usuarios) {
            foreach ($usuarios as $usuario) {
                $htmlItem .= $this->listadoItem($usuario);
            }
        }

        $tmpl->addSearchReplace('{#USUARIOS#}', $htmlItem);

        return $tmpl->getHtml();
    }

    public function listadoUsuario($usuario)
    {
        $tmpl = $this->loadTemplate('usuario_item');

        $verificado = 'fa-times text-danger';

        if ($usuario->verificado == 1) {
            $verificado = 'fa-check text-success';
        }

        $tmpl->addSearchReplace('{#ID_USUARIO#}', $usuario->id_usuario);
        $tmpl->addSearchReplace('{#NOMBRE#}', $usuario->nombre);
        $tmpl->addSearchReplace('{#APELLIDO#}', $usuario->apellido);
        $tmpl->addSearchReplace('{#PERFIL#}', $usuario->perfil);
        $tmpl->addSearchReplace('{#VERIFICADO#}', $verificado);

        return $tmpl->getHtml();
    }

    public function listadoItem($usuario)
    {
        $tmpl = $this->loadTemplate('arrendatario_item');

        $verificado = 'fa-times text-danger';

        if ($usuario->verificado == 1) {
            $verificado = 'fa-check text-success';
        }

        $tmpl->addSearchReplace('{#ID_USUARIO#}', $usuario->id_usuario);
        $tmpl->addSearchReplace('{#NOMBRE#}', $usuario->nombre);
        $tmpl->addSearchReplace('{#APELLIDO#}', $usuario->apellido);
        $tmpl->addSearchReplace('{#PERFIL#}', $usuario->perfil);
        $tmpl->addSearchReplace('{#VERIFICADO#}', $verificado);

        return $tmpl->getHtml();
    }

    public function cargarUsuario($perfiles, $usuario)
    {
        $tmpl = $this->loadTemplate('edicion');

        $idUsuario         = 0;
        $accion            = 'Nuevo usuario';
        $idPerfil          = 0;
        $nombre            = '';
        $apellido          = '';
        $rut               = '';
        $mail              = '';
        $telefono          = '';
        $verificacion      = '';
        $claseVerificacion = '';
        $registro          = '';
        $claseBotonVerificacion = '';

        if ($usuario) {
            $idUsuario = $usuario->id_usuario;
            $accion    = 'Editar usuario';
            $idPerfil  = $usuario->id_perfil;
            $nombre    = $usuario->nombre;
            $apellido  = $usuario->apellido;
            $rut       = $usuario->rut;
            $mail      = $usuario->email;
            $telefono  = $usuario->telefono;

            $fechaRegistro = date('d-m-Y', strtotime($usuario->fecha_creacion));
            $registro  = "Usuario registrado el $fechaRegistro";

            $verificacion      = 'Usuario no ha sido verificado.';
            $claseVerificacion = 'text-danger';

            if ($usuario->verificado == 1) {
                $claseVerificacion = 'text-success';

                $fechaVerificacion = date('d-m-Y', strtotime($usuario->fecha_verificacion));
                $verificacion = "Usuario verificado el $fechaVerificacion";
            }

            if ($usuario->verificado == 1) {
                $claseBotonVerificacion = 'hide';
            }
        }

        $optionPerfiles = '';
        if ($perfiles) {
            $optionPerfiles = $this->generateSelectOptions($perfiles, $idPerfil);
        }

        $tmpl->addSearchReplace('{#ID_USUARIO#}', $idUsuario);
        $tmpl->addSearchReplace('{#ACCION#}', $accion);
        $tmpl->addSearchReplace('{#PERFIL#}', $optionPerfiles);
        $tmpl->addSearchReplace('{#NOMBRE#}', $nombre);
        $tmpl->addSearchReplace('{#APELLIDO#}', $apellido);
        $tmpl->addSearchReplace('{#RUT#}', $rut);
        $tmpl->addSearchReplace('{#MAIL#}', $mail);
        $tmpl->addSearchReplace('{#TELEFONO#}', $telefono);
        $tmpl->addSearchReplace('{#CLASE_VERIFICADO#}', $claseVerificacion);
        $tmpl->addSearchReplace('{#VERIFICACION#}', $verificacion);
        $tmpl->addSearchReplace('{#REGISTRO#}', $registro);
        $tmpl->addSearchReplace('{#CLASE_BOTON_VERIFICACION#}', $claseBotonVerificacion);

        return $tmpl->getHtml();
    }

    public function cargarHtmlMail($nombreMail, $sitio, $titulo, $texto)
    {
        $tmpl = $this->loadTemplate('mail');

        $logo = Variables::URL.$sitio->carpeta.'/imagenes/'.$sitio->logo;

        $tmpl->addSearchReplace('{#NOMBRE_MAIL#}', $nombreMail);
        $tmpl->addSearchReplace('{#IMAGEN_LOGO#}', $logo);
        $tmpl->addSearchReplace('{#NOMBRE_PASEO#}', $sitio->nombre);
        $tmpl->addSearchReplace('{#TITULO#}', $titulo);
        $tmpl->addSearchReplace('{#TEXTO#}', $texto);
        $tmpl->addSearchReplace('{#BOTON#}', '');

        return $tmpl->getHtml();
    }
}
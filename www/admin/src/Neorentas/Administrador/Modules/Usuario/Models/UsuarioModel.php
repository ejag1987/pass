<?php

namespace Neorentas\Administrador\Modules\Usuario\Models;

use ZCode\Lighting\Database\Mysql\MysqlProvider;
use ZCode\Lighting\Model\BaseModel;

class UsuarioModel extends BaseModel
{
    public function buscarUsuarios()
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT U.id_usuario, P.nombre AS perfil, U.nombre, U.apellido, U.verificado
            FROM usuario AS U
            LEFT OUTER JOIN perfil AS P ON U.id_perfil = P.id_perfil
            WHERE U.eliminado = 0
            ORDER BY U.nombre ASC, U.apellido ASC;
        ";

        $database->setQuery($query);
        $usuarios = $database->loadObjectList();

        return $usuarios;
    }

    public function buscarUsuariosArrendatarios()
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT U.id_usuario, P.nombre AS perfil, U.nombre, U.apellido, U.verificado, U.email, 
                U.fecha_creacion, U.fecha_verificacion, S.nombre AS sitio
            FROM usuario AS U
            LEFT OUTER JOIN perfil AS P ON U.id_perfil = P.id_perfil
            LEFT OUTER JOIN sitio AS S ON U.id_sitio = S.id_sitio
            WHERE U.eliminado = 0 AND U.id_perfil = 2
            ORDER BY U.nombre ASC, U.apellido ASC;
        ";

        $database->setQuery($query);
        $usuarios = $database->loadObjectList();

        return $usuarios;
    }

    public function buscarPerfiles()
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT id_perfil as item_id, nombre as name
            FROM perfil 
            ORDER BY nombre ASC
        ";

        $database->setQuery($query);
        $perfiles = $database->loadObjectList();

        return $perfiles;
    }

    public function buscarUsuario($idUsuario)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT U.id_usuario, U.id_perfil, P.nombre AS perfil, U.email, U.nombre, U.apellido, U.rut, U.telefono, U.verificado, 
                U.fecha_creacion, U.fecha_verificacion, U.eliminado
            FROM usuario AS U
            LEFT OUTER JOIN perfil AS P ON U.id_perfil = P.id_perfil
            WHERE U.id_usuario = $idUsuario;
        ";

        $database->setQuery($query);
        $usuarios = $database->loadObject();

        return $usuarios;
    }

    public function guardarUsuario($idUsuario, $idPerfil, $nombre, $apellido, $rut, $mail, $telefono, $pass)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $datos = [
            'email' => $mail,
            'nombre' => $nombre,
            'apellido' => $apellido,
            'rut' => $rut,
            'telefono' => $telefono,
            'id_perfil' => $idPerfil
        ];

        $tipos = 'sssssi';

        if ($pass != '') {
            $datos['contrasena'] = $pass;
            $tipos .= 's';
        }

        if ($idUsuario > 0) {
            $key   = [
                'field' => 'id_usuario',
                'value' => $idUsuario,
                'type' => 'i'
            ];

            if (!$database->updateRow('usuario', $datos, $tipos, $key)) {
                return false;
            }

            return $this->buscarUsuario($idUsuario);
        }

        $fecha = date('Y-m-d H:i:s');

        $datos['id_sitio']   = 0;
        $datos['verificado'] = 0;
        $datos['eliminado']  = 0;
        $datos['fecha_creacion'] = $fecha;

        $tipos .= 'iiis';

        if (!$database->insertRow('usuario', $datos, $tipos)) {
            return false;
        }

        return $this->buscarUsuario($database->lastId);
    }

    public function buscarUsuarioRegistrado($mail)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT id_usuario 
            FROM usuario
            WHERE email = '$mail';
        ";

        $database->setQuery($query);
        $usuario = $database->loadField('id_usuario');

        return $usuario;
    }

    public function borrarUsuario($idUsuario)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $datos = ['eliminado' => 1];
        $tipos = 'i';
        $key   = ['field' => 'id_usuario','value' => $idUsuario,'type' => 'i'];

        if (!$database->updateRow('usuario', $datos, $tipos, $key)) {
            return false;
        }

        return $idUsuario;
    }

    public function validarUsuario($idUsuario)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $fecha = date('Y-m-d H:i:s');

        $datos = ['verificado' => 1, 'fecha_verificacion' => $fecha];
        $tipos = 'is';
        $key   = ['field' => 'id_usuario','value' => $idUsuario,'type' => 'i'];

        if (!$database->updateRow('usuario', $datos, $tipos, $key)) {
            return false;
        }

        return $this->buscarUsuario($idUsuario);
    }
}
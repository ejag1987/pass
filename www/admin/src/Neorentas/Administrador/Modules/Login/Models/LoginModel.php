<?php

namespace Neorentas\Administrador\Modules\Login\Models;

use ZCode\Lighting\Database\Mysql\MysqlProvider;
use ZCode\Lighting\Model\BaseModel;

class LoginModel extends BaseModel
{
    /**
     * @param $usuario
     * @return mixed|null|object|\stdClass
     */
    public function obtenerUsuario($usuario)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT id_usuario, email, contrasena, nombre, apellido, id_perfil
            FROM usuario 
            WHERE email = '$usuario' AND eliminado = 0 AND (id_perfil = 1 OR id_perfil = 3);
        ";

        //echo $query;
        $database->setQuery($query);
        $usuario = $database->loadObject();

        return $usuario;
    }
}
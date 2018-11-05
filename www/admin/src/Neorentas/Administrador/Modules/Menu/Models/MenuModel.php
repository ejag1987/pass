<?php

namespace Neorentas\Administrador\Modules\Menu\Models;

use ZCode\Lighting\Database\Mysql\MysqlProvider;
use ZCode\Lighting\Model\BaseModel;

class MenuModel extends BaseModel
{
    public function cargarPaseos()
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT id_sitio AS item_id, nombre AS name
            FROM sitio
            WHERE eliminado = 0 AND id_sitio != 1;
        ";

        $database->setQuery($query);
        $sitios = $database->loadObjectList();

        return $sitios;
    }
}
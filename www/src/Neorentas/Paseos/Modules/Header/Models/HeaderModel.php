<?php

namespace Neorentas\Paseos\Modules\Header\Models;

use ZCode\Lighting\Database\Mysql\MysqlProvider;
use ZCode\Lighting\Model\BaseModel;

class HeaderModel extends BaseModel
{
    public function cargarRedes()
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('sitio');

        $query = "
            SELECT id_red_social, nombre, link, icono, orden
            FROM red_social
            WHERE eliminado = 0 AND id_sitio = 1
            ORDER BY orden ASC;
        ";

        $database->setQuery($query);
        $redSocial = $database->loadObjectList();

        return $redSocial;
    }

}

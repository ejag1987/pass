<?php

namespace Neorentas\Paseos\Sitio\Modules\Menu\Models;

use ZCode\Lighting\Database\Mysql\MysqlProvider;
use ZCode\Lighting\Model\BaseModel;

class MenuModel extends BaseModel
{
    /**
     * @param $idSitio
     * @return bool|null
     */
    public function buscarCarpeta($idSitio)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('sitio');

        $query = "
            SELECT carpeta
            FROM sitio
            WHERE id_sitio = $idSitio;
        ";

        $database->setQuery($query);
        $url = $database->loadField('carpeta');

        return $url;
    }

    /**
     * @param $idSitio
     * @return array|bool
     */
    public function cargarMenus($idSitio)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('sitio');

        $query = "
            SELECT id_menu, id_padre, nombre, alias, link
            FROM menu
            WHERE id_sitio = $idSitio AND eliminado = 0
            ORDER BY orden ASC, id_padre ASC;
        ";

        //echo $query;
        $database->setQuery($query);
        $menus = $database->loadObjectList();

        return $menus;
    }
}
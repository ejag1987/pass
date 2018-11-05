<?php

namespace Neorentas\Paseos\Sitio\Modules\Header\Models;

use ZCode\Lighting\Database\Mysql\MysqlProvider;
use ZCode\Lighting\Model\BaseModel;

class HeaderModel extends BaseModel
{
    public function cargarRedes($idSitio)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('sitio');

        $query = "
            SELECT id_red_social, nombre, link, icono, orden
            FROM red_social
            WHERE eliminado = 0 AND id_sitio = $idSitio
            ORDER BY orden ASC;
        ";

        $database->setQuery($query);
        $redSocial = $database->loadObjectList();

        return $redSocial;
    }

    /**
     * @param $idSitio
     * @return bool|null
     */
    public function cargarCarpeta($idSitio)
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
}
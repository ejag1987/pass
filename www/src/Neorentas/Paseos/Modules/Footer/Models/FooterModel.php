<?php

namespace Neorentas\Paseos\Modules\Footer\Models;

use ZCode\Lighting\Database\Mysql\MysqlProvider;
use ZCode\Lighting\Model\BaseModel;

class FooterModel extends BaseModel
{
    /**
     * @return array|bool
     */
    public function cargarConfiguracion()
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('sitio');

        $query = "
            SELECT id_sitio, direccion, correo, telefono
            FROM sitio
            WHERE id_sitio = 1;
        ";

        $database->setQuery($query);
        $pagina = $database->loadObject();

        return $pagina;
    }
}
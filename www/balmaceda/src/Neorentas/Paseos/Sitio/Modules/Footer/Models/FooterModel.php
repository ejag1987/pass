<?php

namespace Neorentas\Paseos\Sitio\Modules\Footer\Models;

use ZCode\Lighting\Database\Mysql\MysqlProvider;
use ZCode\Lighting\Model\BaseModel;

class FooterModel extends BaseModel
{
    /**
     * @param $idSitio
     * @return array|bool
     */
    public function cargarConfiguracion($idSitio)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('sitio');

        $query = "
            SELECT id_sitio, direccion, correo, telefono, como_llegar, horario
            FROM sitio
            WHERE id_sitio = $idSitio;
        ";

        $database->setQuery($query);
        $pagina = $database->loadObject();

        return $pagina;
    }
}
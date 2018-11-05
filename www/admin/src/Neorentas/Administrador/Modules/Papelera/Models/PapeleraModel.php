<?php

namespace Neorentas\Administrador\Modules\Papelera\Models;

use ZCode\Lighting\Model\BaseModel;

class PapeleraModel extends BaseModel
{
	public function cargarSlide($idSitio)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT id_portada_slide, imagen, link
            FROM portada_slide
            WHERE id_sitio = $idSitio
            AND eliminado = 0
            ORDER BY orden ASC;
        ";

        $database->setQuery($query);
        $slides = $database->loadObjectList();

        return $slides;
    }

    public function cargarCarpeta($idSitio)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT carpeta
            FROM sitio
            WHERE id_sitio = $idSitio;
        ";

        $database->setQuery($query);
        $carpeta = $database->loadField('carpeta');

        return $carpeta;
    }

    public function cargarCaluga($idSitio)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT id_portada_caluga, imagen, link
            FROM portada_caluga
            WHERE id_sitio = $idSitio
            ORDER BY orden ASC;
        ";

        $database->setQuery($query);
        $calugas = $database->loadObjectList();

        return $calugas;
    }
}
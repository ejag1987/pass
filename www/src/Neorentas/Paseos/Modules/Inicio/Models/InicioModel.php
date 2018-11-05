<?php

namespace Neorentas\Paseos\Modules\Inicio\Models;

use ZCode\Lighting\Database\Mysql\MysqlProvider;
use ZCode\Lighting\Model\BaseModel;

class InicioModel extends BaseModel
{
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

    public function cargarSlide($idSitio)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('sitio');

        $query = "
            SELECT id_portada_slide, imagen, link, imagen_movil
            FROM portada_slide
            WHERE eliminado = 0 AND id_sitio = $idSitio
            ORDER BY orden ASC;
        ";

        $database->setQuery($query);
        $slide = $database->loadObjectList();

        return $slide;
    }

    /**
     * @param $idSitio
     * @return array|bool
     */
    public function cargarMarcas($idSitio)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('sitio');

        $query = "
            SELECT id_marca, nombre, logo, link
            FROM marca
            WHERE eliminado = 0 AND id_sitio = $idSitio
            ORDER BY orden ASC;
        ";

        $database->setQuery($query);
        $marcas = $database->loadObjectList();

        return $marcas;
    }


    /**
     * @param $idSitio
     * @return array|bool
     */
    public function cargarCalugas($idSitio)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('sitio');

        $query = "
            SELECT id_portada_caluga, imagen, link
            FROM portada_caluga
            WHERE eliminado = 0 AND id_sitio = $idSitio
            ORDER BY orden ASC;
        ";

        //echo $query;
        $database->setQuery($query);
        $calugas = $database->loadObjectList();

        return $calugas;
    }


}
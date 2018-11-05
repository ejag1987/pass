<?php

namespace Neorentas\Paseos\Sitio\Modules\Home\Models;

use ZCode\Lighting\Database\Mysql\MysqlProvider;
use ZCode\Lighting\Model\BaseModel;

class HomeModel extends BaseModel
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

    /**
     * @param $idSitio
     * @return array|bool
     */
    public function cargarSlide($idSitio)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('sitio');

        $query = "
            SELECT id_portada_slide, imagen, link, comentario
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
            SELECT imagen, link, orden
            FROM portada_caluga
            WHERE eliminado = 0 AND id_sitio = $idSitio AND panel = 1
            ORDER BY orden ASC;
        ";

        //echo $query;
        $database->setQuery($query);
        $calugas = $database->loadObjectList();


        return $calugas;
    }
    public function cargarCalugasSite($idSitio)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('sitio');

        $query = "
            SELECT id_portada_caluga, imagen, link
            FROM portada_caluga
            WHERE eliminado = 0 AND id_sitio = $idSitio AND panel != 1
            ORDER BY orden ASC;
        ";

        //echo $query;
        $database->setQuery($query);
        $calugasite = $database->loadObjectList();

        return $calugasite;
    }
}
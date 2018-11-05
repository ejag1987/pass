<?php

namespace Neorentas\Administrador\Modules\Rrss\Models;

use ZCode\Lighting\Database\Mysql\MysqlProvider;
use ZCode\Lighting\Model\BaseModel;

class RrssModel extends BaseModel
{
    /**
     * @param $idSitio
     * @return array|bool
     */
    public function cargarRedesSociales($idSitio)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

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
        $database = $this->getDatabase('administrador');

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
     * @return bool|mixed|null|object|\stdClass
     */
    public function crearRed($idSitio)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "SELECT MAX(orden) AS total FROM red_social WHERE eliminado = 0 AND id_sitio = $idSitio;";

        $database->setQuery($query);
        $indice = $database->loadField('total') + 1;

        $datos = [
            'id_sitio' => $idSitio,
            'eliminado' => 0,
            'orden' => $indice
        ];

        $tipos = 'iii';

        if (!$database->insertRow('red_social', $datos, $tipos)) {
            return false;
        }

        $query = "
            SELECT id_red_social, nombre, link, icono, orden
            FROM red_social
            WHERE id_red_social = $database->lastId;
        ";

        $database->setQuery($query);
        $redSocial = $database->loadObject();

        return $redSocial;
    }

    /**
     * @param $idRed
     * @param $archivo
     * @return bool|mixed|null|object|\stdClass
     */
    public function guardarIcono($idRed, $archivo)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $datos = ['icono' => $archivo];
        $tipos = 's';
        $key   = ['field' => 'id_red_social', 'value' => $idRed, 'type' => 'i'];

        if (!$database->updateRow('red_social', $datos, $tipos, $key)) {
            return false;
        }

        $query = "
            SELECT id_red_social, nombre, link, icono, orden
            FROM red_social
            WHERE id_red_social = $idRed;
        ";

        $database->setQuery($query);
        $redSocial = $database->loadObject();

        return $redSocial;
    }

    /**
     * @param $idRed
     * @return bool|mixed|null|object|\stdClass
     */
    public function borrarIcono($idRed)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT id_red_social, nombre, link, icono, orden
            FROM red_social
            WHERE id_red_social = $idRed;
        ";

        $database->setQuery($query);
        $redSocial = $database->loadObject();

        $datos = ['icono' => ''];
        $tipos = 's';
        $key   = ['field' => 'id_red_social', 'value' => $idRed, 'type' => 'i'];

        if (!$database->updateRow('red_social', $datos, $tipos, $key)) {
            return false;
        }

        return $redSocial;
    }

    /**
     * @param $idRed
     * @param $nombre
     * @param $link
     * @return bool
     */
    public function guardarRed($idRed, $nombre, $link)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $datos = ['nombre' => $nombre, 'link' => $link];
        $tipos = 'ss';
        $key   = ['field' => 'id_red_social', 'value' => $idRed, 'type' => 'i'];

        if (!$database->updateRow('red_social', $datos, $tipos, $key)) {
            return false;
        }

        return $idRed;
    }

    /**
     * @param $idRed
     * @return bool
     */
    public function cambiarEstadoRed($idRed)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $datos = ['eliminado' => 1];
        $tipos = 'i';
        $key   = ['field' => 'id_red_social', 'value' => $idRed, 'type' => 'i'];

        if (!$database->updateRow('red_social', $datos, $tipos, $key)) {
            return false;
        }

        return $idRed;
    }

    /**
     * @param $idRed
     * @param $orden
     * @return bool
     */
    public function actualizarOrdenRedes($idRed, $orden)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $datos = [
            'orden' => $orden
        ];

        $tipos = 'i';

        $key = ['field' => 'id_red_social', 'value' => $idRed, 'type' => 'i'];

        if (!$database->updateRow('red_social', $datos, $tipos, $key)) {
            return false;
        }

        return $idRed;
    }
}
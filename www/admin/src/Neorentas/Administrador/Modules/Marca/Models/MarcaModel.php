<?php

namespace Neorentas\Administrador\Modules\Marca\Models;

use ZCode\Lighting\Database\Mysql\MysqlProvider;
use ZCode\Lighting\Model\BaseModel;

class MarcaModel extends BaseModel
{
    /**
     * @param $idSitio
     * @return array|bool
     */
    public function cargarMarcas($idSitio)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT id_marca, nombre, logo, link
            FROM marca
            WHERE eliminado = 0 AND id_sitio = $idSitio;
        ";

        $database->setQuery($query);
        $marcas = $database->loadObjectList();

        return $marcas;
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
    public function crearMarca($idSitio)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "SELECT MAX(orden) AS total FROM marca WHERE eliminado = 0 AND id_sitio = $idSitio;";

        $database->setQuery($query);
        $indice = $database->loadField('total') + 1;

        $datos = [
            'id_sitio' => $idSitio,
            'eliminado' => 0,
            'orden' => $indice
        ];

        $tipos = 'iii';

        if (!$database->insertRow('marca', $datos, $tipos)) {
            return false;
        }

        $query = "
            SELECT id_marca, nombre, link, logo, orden
            FROM marca
            WHERE id_marca = $database->lastId;
        ";

        $database->setQuery($query);
        $marca = $database->loadObject();

        return $marca;
    }

    /**
     * @param $idMarca
     * @param $archivo
     * @return bool|mixed|null|object|\stdClass
     */
    public function guardarLogo($idMarca, $archivo)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $datos = ['logo' => $archivo];
        $tipos = 's';
        $key   = ['field' => 'id_marca', 'value' => $idMarca, 'type' => 'i'];

        if (!$database->updateRow('marca', $datos, $tipos, $key)) {
            return false;
        }

        $query = "
            SELECT id_marca, nombre, link, logo, orden
            FROM marca
            WHERE id_marca = $idMarca;
        ";

        $database->setQuery($query);
        $marca = $database->loadObject();

        return $marca;
    }

    /**
     * @param $idMarca
     * @return bool|mixed|null|object|\stdClass
     */
    public function borrarLogo($idMarca)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT id_marca, nombre, logo, link
            FROM marca
            WHERE id_marca = $idMarca;
        ";

        $database->setQuery($query);
        $marca = $database->loadObject();

        $datos = ['logo' => ''];
        $tipos = 's';
        $key   = ['field' => 'id_marca', 'value' => $idMarca, 'type' => 'i'];

        if (!$database->updateRow('marca', $datos, $tipos, $key)) {
            return false;
        }

        return $marca;
    }

    /**
     * @param $idMarca
     * @param $nombre
     * @param $link
     * @return bool
     */
    public function guardarMarca($idMarca, $nombre, $link)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $datos = ['nombre' => $nombre, 'link' => $link];
        $tipos = 'ss';
        $key   = ['field' => 'id_marca', 'value' => $idMarca, 'type' => 'i'];

        if (!$database->updateRow('marca', $datos, $tipos, $key)) {
            return false;
        }

        return $idMarca;
    }

    /**
     * @param $idMarca
     * @return bool
     */
    public function cambiarEstadoMarca($idMarca)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $datos = ['eliminado' => 1];
        $tipos = 'i';
        $key   = ['field' => 'id_marca', 'value' => $idMarca, 'type' => 'i'];

        if (!$database->updateRow('marca', $datos, $tipos, $key)) {
            return false;
        }

        return $idMarca;
    }

    /**
     * @param $idMarca
     * @param $orden
     * @return bool
     */
    public function actualizarOrdenMarcas($idMarca, $orden)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $datos = [
            'orden' => $orden
        ];

        $tipos = 'i';

        $key = ['field' => 'id_marca', 'value' => $idMarca, 'type' => 'i'];

        if (!$database->updateRow('marca', $datos, $tipos, $key)) {
            return false;
        }

        return $idMarca;
    }
}
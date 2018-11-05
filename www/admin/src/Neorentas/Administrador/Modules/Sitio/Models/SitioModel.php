<?php

namespace Neorentas\Administrador\Modules\Sitio\Models;

use Neorentas\Administrador\System\FuncionesGlobales;
use ZCode\Lighting\Database\Mysql\MysqlProvider;
use ZCode\Lighting\Model\BaseModel;

class SitioModel extends BaseModel
{
    public function cargarSitio($idSitio)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT id_sitio, nombre, link, carpeta, logo, informacion, direccion, terreno, contruida, arrendable, 
              estacionamientos, correo, telefono, google_map 
            FROM sitio
            WHERE id_sitio = $idSitio
        ";

        $database->setQuery($query);
        $sitio = $database->loadObject();

        return $sitio;
    }

    public function guardarSitio($idSitio, $nombre, $url, $correo)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $datos = [
            'nombre' => $nombre,
            'link' => $url,
            'correo' => $correo
        ];

        $tipos = 'sss';

        $carpetaBd = $this->buscarCarpeta($idSitio);

        if (!$carpetaBd) {
            $carpeta = FuncionesGlobales::sanitizar($nombre);
            $carpeta = substr($carpeta, 0, 50);

            $datos['carpeta'] = $carpeta;
            $tipos .= 's';
        }

        if ($idSitio > 0) {
            $key = [
                'field' => 'id_sitio',
                'value' => $idSitio,
                'type' => 'i'
            ];

            if (!$database->updateRow('sitio', $datos, $tipos, $key)) {
                return false;
            }

            return $idSitio;
        }

        if (!$database->insertRow('sitio', $datos, $tipos)) {
            return false;
        }

        return $database->lastId;
    }

    public function buscarCarpeta($idSitio)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT carpeta
            FROM sitio
            WHERE id_sitio = $idSitio;
        ";

        $database->setQuery($query);
        $datos = $database->loadField('carpeta');

        return $datos;
    }
}

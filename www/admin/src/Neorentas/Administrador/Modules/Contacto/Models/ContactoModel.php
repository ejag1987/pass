<?php

namespace Neorentas\Administrador\Modules\Contacto\Models;

use ZCode\Lighting\Database\Mysql\MysqlProvider;
use ZCode\Lighting\Model\BaseModel;

class ContactoModel extends BaseModel
{
    /**
     * @param $idSitio
     * @return bool|int|null
     */
    public function buscarPagina($idSitio)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT id_pagina
            FROM pagina
            WHERE id_sitio = $idSitio AND id_pagina_tipo = 2 AND eliminada = 0;
        ";

        $database->setQuery($query);
        $idPagina = $database->loadField('id_pagina');

        if (!$idPagina) {
            $idPagina = 0;
        }

        return $idPagina;
    }

    /**
     * @param $idSitio
     * @return array|bool
     */
    public function cargarDatos($idSitio)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT id_sitio, link, carpeta, direccion, telefono, correo, google_map, como_llegar
            FROM sitio
            WHERE id_sitio = $idSitio
        ";

        $database->setQuery($query);
        $datos = $database->loadObject();

        return $datos;
    }

    /**
     * @param $idPagina
     * @return mixed|null|object|\stdClass
     */
    public function cargarPagina($idPagina)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT id_pagina, titulo1, titulo2, fondo
            FROM pagina
            WHERE id_pagina = $idPagina AND eliminada = 0;
        ";

        $database->setQuery($query);
        $pagina = $database->loadObject();

        return $pagina;
    }

    /**
     * @param $idSitio
     * @return bool|null
     */
    public function buscarCarpeta($idSitio)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT carpeta
            FROM sitio
            WHERE id_sitio = $idSitio
        ";

        $database->setQuery($query);
        $carpeta = $database->loadField('carpeta');

        return $carpeta;
    }

    /**
     * @param $idPagina
     * @return bool|null
     */
    public function buscarImagen($idPagina)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT fondo
            FROM pagina
            WHERE id_pagina = $idPagina
        ";

        $database->setQuery($query);
        $fondo = $database->loadField('fondo');

        return $fondo;
    }

    /**
     * @param $idSitio
     * @param $idPagina
     * @param $sitio
     * @param $titulo1
     * @param $titulo2
     * @param $direccion
     * @param $correo
     * @param $telefono
     * @param $googleMaps
     * @param $comoLlegar
     * @return bool
     */
    public function guardarContacto($idSitio, $idPagina, $sitio, $titulo1, $titulo2, $direccion, $correo, $telefono,
                                    $googleMaps, $comoLlegar)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        // guardar contacto en sitio
        $datos = [
            'direccion' => $direccion,
            'telefono' => $telefono,
            'correo' => $correo,
            'google_map' => $googleMaps,
            'como_llegar' => $comoLlegar
        ];
        $tipos = 'sssss';

        $key   = [
            'field' => 'id_sitio',
            'value' => $idSitio,
            'type' => 'i'
        ];

        if (!$database->updateRow('sitio', $datos, $tipos, $key)) {
            return false;
        }

        // guardar titulos pagina
        $datosPagina = [
            'titulo1' => $titulo1,
            'titulo2' => $titulo2
        ];

        $tiposPagina = 'ss';

        if ($idPagina > 0) { // update
            $keyPagina = [
                'field' => 'id_pagina',
                'value' => $idPagina,
                'type' => 'i'
            ];

            if (!$database->updateRow('pagina', $datosPagina, $tiposPagina, $keyPagina)) {
                return false;
            }

            return $idPagina;
        }

        $datosPagina['id_sitio'] = $idSitio;
        $datosPagina['alias'] = 'contacto_'.$sitio;
        $datosPagina['id_pagina_tipo'] = 2;
        $datosPagina['eliminada'] = 0;
        $datosPagina['fecha_creacion'] = date('Y-m-d H:i:s');

        $tiposPagina .= 'isiis';

        if (!$database->insertRow('pagina', $datosPagina, $tiposPagina)) {
            return false;
        }

        return $database->lastId;
    }

    /**
     * @param $idSitio
     * @param $idPagina
     * @param $sitio
     * @param $archivo
     * @return bool
     */
    public function guardarImagen($idSitio, $idPagina, $sitio, $archivo)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $datos = ['fondo' => $archivo];
        $tipos = 's';

        if ($idPagina > 0) { // update
            $key   = [
                'field' => 'id_pagina',
                'value' => $idPagina,
                'type' => 'i'
            ];

            if (!$database->updateRow('pagina', $datos, $tipos, $key)) {
                return false;
            }

            return $idPagina;
        }

        $datos['id_sitio'] = $idSitio;
        $datos['alias'] = 'contacto_'.$sitio;
        $datos['id_pagina_tipo'] = 2;
        $datos['eliminada'] = 0;
        $datos['fecha_creacion'] = date('Y-m-d H:i:s');

        $tipos .= 'isiis';

        if (!$database->insertRow('pagina', $datos, $tipos)) {
            return false;
        }

        return $database->lastId;
    }

    /**
     * @param $idPagina
     * @return bool
     */
    public function borrarImagen($idPagina)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $datos = ['fondo' => null];

        $tipos = 's';

        $key   = [
            'field' => 'id_pagina',
            'value' => $idPagina,
            'type' => 'i'
        ];

        if (!$database->updateRow('pagina', $datos, $tipos, $key)) {
            return false;
        }

        return true;
    }
}
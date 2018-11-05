<?php

namespace Neorentas\Administrador\Modules\Evento\Models;

use ZCode\Lighting\Database\Mysql\MysqlProvider;
use ZCode\Lighting\Model\BaseModel;

class EventoModel extends BaseModel
{
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
     * @return mixed|null|object|\stdClass
     */
    public function buscarPagina($idSitio)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT id_pagina, titulo1, titulo2, fondo
            FROM pagina
            WHERE id_sitio = $idSitio AND id_pagina_tipo = 6 AND eliminada = 0;
        ";

        $database->setQuery($query);
        $pagina = $database->loadObject();

        return $pagina;
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
     * @param $idPagina
     * @param $titulo1
     * @param $titulo2
     * @return bool|int
     */
    public function guardarPagina($idSitio, $idPagina, $titulo1, $titulo2)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

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

        $sitio = $this->cargarCarpeta($idSitio);

        $datosPagina['id_sitio']       = $idSitio;
        $datosPagina['alias']          = 'eventos_'.$sitio;
        $datosPagina['id_pagina_tipo'] = 6;
        $datosPagina['eliminada']      = 0;
        $datosPagina['fecha_creacion'] = date('Y-m-d H:i:s');

        $tiposPagina .= 'isiis';

        if (!$database->insertRow('pagina', $datosPagina, $tiposPagina)) {
            return false;
        }

        return $database->lastId;
    }

    /**
     * @param $idSitio
     * @param $idItem
     * @param $archivo
     * @param $campo
     * @return bool|mixed|null|object|\stdClass
     */
    public function guardarImagen($idSitio, $idItem, $archivo, $campo)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        if ($campo == 'fondo') {
            $datos = ['fondo' => $archivo];
            $tipos = 's';

            $pagina = $this->buscarPagina($idSitio);

            if ($pagina) {
                if ($pagina->id_pagina > 0) { // update
                    $key   = [
                        'field' => 'id_pagina',
                        'value' => $pagina->id_pagina,
                        'type' => 'i'
                    ];

                    if (!$database->updateRow('pagina', $datos, $tipos, $key)) {
                        return false;
                    }

                    $pagina = $this->cargarPagina($pagina->id_pagina);

                    return $pagina;
                }
            }

            $sitio = $this->cargarCarpeta($idSitio);

            $datos['id_sitio']       = $idSitio;
            $datos['alias']          = 'eventos_'.$sitio;
            $datos['id_pagina_tipo'] = 6;
            $datos['eliminada']      = 0;
            $datos['fecha_creacion'] = date('Y-m-d H:i:s');

            $tipos .= 'isiis';

            if (!$database->insertRow('pagina', $datos, $tipos)) {
                return false;
            }

            $pagina = $this->cargarPagina($database->lastId);

            return $pagina;
        }

        $datos = [$campo => $archivo];
        $tipos = 's';
        $key   = ['field' => 'id_evento', 'value' => $idItem, 'type' => 'i'];

        if (!$database->updateRow('evento', $datos, $tipos, $key)) {
            return false;
        }

        $item = $this->buscarEvento($idItem);

        return $item;
    }

    /**
     * @param $idItem
     * @param $campo
     * @return bool|mixed|null|object|\stdClass
     */
    public function borrarImagen($idItem, $campo)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        if ($campo == 'fondo') {
            $pagina = $this->cargarPagina($idItem);

            $datos = ['fondo' => null];
            $tipos = 's';
            $key   = ['field' => 'id_pagina', 'value' => $idItem, 'type' => 'i'];

            if (!$database->updateRow('pagina', $datos, $tipos, $key)) {
                return false;
            }

            return $pagina;
        }

        $evento = $this->buscarEvento($idItem);

        $datos = [$campo => null];
        $tipos = 's';
        $key   = ['field' => 'id_evento', 'value' => $idItem, 'type' => 'i'];

        if (!$database->updateRow('evento', $datos, $tipos, $key)) {
            return false;
        }

        return $evento;
    }

    /**
     * @param $idSitio
     * @return array|bool
     */
    public function buscarEventos($idSitio)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT id_evento, foto, titulo,alias, descripcion, contenido, fecha_evento, horario, fecha_creacion
            FROM evento
            WHERE id_sitio = $idSitio AND eliminado = 0
            ORDER BY fecha_evento ASC, orden ASC;
        ";

        $database->setQuery($query);
        $eventos = $database->loadObjectList();

        return $eventos;
    }

    /**
     * @param $idEvento
     * @return mixed|null|object|\stdClass
     */
    public function buscarEvento($idEvento)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT id_evento, foto, titulo, descripcion, fecha_evento, horario, fecha_creacion
            FROM evento
            WHERE id_evento = $idEvento;
        ";

        $database->setQuery($query);
        $evento = $database->loadObject();

        return $evento;
    }

    /**
     * @param $idSitio
     * @return bool|mixed|null|object|\stdClass
     */
    public function crearEvento($idSitio)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT MAX(orden) AS total 
            FROM evento 
            WHERE eliminado = 0 AND id_sitio = $idSitio;
        ";

        $database->setQuery($query);
        $indice = $database->loadField('total') + 1;

        $fecha = date('Y-m-d H:i:s');

        $datos = [
            'id_sitio' => $idSitio,
            'eliminado' => 0,
            'orden' => $indice,
            'fecha_creacion' => $fecha
        ];

        $tipos = 'iiis';

        if (!$database->insertRow('evento', $datos, $tipos)) {
            return false;
        }

        $evento = $this->buscarEvento($database->lastId);

        return $evento;
    }

    /**
     * @param $idEvento
     * @param $titulo
     * @param $descripcion
     * @param $fecha
     * @param $horario
     * @return bool
     */
    public function guardarEvento($idEvento, $titulo, $alias, $descripcion, $contenido, $fecha, $horario)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $date = null;

        if ($fecha != '') {
            $date = date('Y-m-d', strtotime($fecha));
        }

        $datos = [
            'titulo' => $titulo,
            'alias' => $alias,
            'descripcion' => $descripcion,
            'contenido' => $contenido,
            'fecha_evento' => $date,
            'horario' => $horario
        ];
        $tipos = 'ssssss';
        $key   = ['field' => 'id_evento', 'value' => $idEvento, 'type' => 'i'];

        if (!$database->updateRow('evento', $datos, $tipos, $key)) {
            return false;
        }

        return $idEvento;
    }

    /**
     * @param $idEvento
     * @return bool|mixed|null|object|\stdClass
     */
    public function borrarEvento($idEvento)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $datos = ['eliminado' => 1];
        $tipos = 'i';
        $key   = ['field' => 'id_evento', 'value' => $idEvento, 'type' => 'i'];

        if (!$database->updateRow('evento', $datos, $tipos, $key)) {
            return false;
        }

        $evento = $this->buscarEvento($idEvento);

        return $evento;
    }

    /**
     * @param $idEvento
     * @param $orden
     * @return bool
     */
    public function guardarOrden($idEvento, $orden)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $datos = ['orden' => $orden];
        $tipos = 'i';
        $key   = ['field' => 'id_evento', 'value' => $idEvento, 'type' => 'i'];

        if (!$database->updateRow('evento', $datos, $tipos, $key)) {
            return false;
        }

        return $idEvento;
    }
}
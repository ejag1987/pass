<?php

namespace Neorentas\Administrador\Modules\Galeria\Models;

use ZCode\Lighting\Database\Mysql\MysqlProvider;
use ZCode\Lighting\Model\BaseModel;

class GaleriaModel extends BaseModel
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
            WHERE id_sitio = $idSitio AND id_pagina_tipo = 7 AND eliminada = 0;
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

    public function buscarGalerias($idSitio)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT
                G.id_galeria,
                G.titulo,
                G.descripcion,
                (SELECT GI.foto 
                FROM galeria_item AS GI 
                WHERE GI.id_galeria = G.id_galeria AND eliminado = 0 
                ORDER BY orden ASC  LIMIT 1) AS foto
            FROM
                galeria AS G
            WHERE
                G.id_sitio = $idSitio
            AND G.eliminado = 0
            ORDER BY
                G.orden ASC;
        ";

        $database->setQuery($query);
        $galerias = $database->loadObjectList();

        return $galerias;
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
        $datosPagina['alias']          = 'eventos_anteriores_'.$sitio;
        $datosPagina['id_pagina_tipo'] = 7;
        $datosPagina['eliminada']      = 0;
        $datosPagina['fecha_creacion'] = date('Y-m-d H:i:s');

        $tiposPagina .= 'isiis';

        if (!$database->insertRow('pagina', $datosPagina, $tiposPagina)) {
            return false;
        }

        return $database->lastId;
    }

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
            $datos['alias']          = 'eventos_anteriores_'.$sitio;
            $datos['id_pagina_tipo'] = 7;
            $datos['eliminada']      = 0;
            $datos['fecha_creacion'] = date('Y-m-d H:i:s');

            $tipos .= 'isiis';

            if (!$database->insertRow('pagina', $datos, $tipos)) {
                return false;
            }

            $pagina = $this->cargarPagina($database->lastId);

            return $pagina;
        }

        $query = "
            SELECT MAX(orden) AS total 
            FROM galeria_item 
            WHERE eliminado = 0 AND id_galeria = $idItem;
        ";

        $database->setQuery($query);
        $indice = $database->loadField('total') + 1;

        $datos = [
            'id_galeria' => $idItem,
            'foto' => $archivo,
            'orden' => $indice,
            'eliminado' => 0
        ];

        $tipos = 'isii';

        if (!$database->insertRow('galeria_item', $datos, $tipos)) {
            return false;
        }

        $item = $this->buscarImagen($database->lastId);

        return $item;
    }

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

        $imagen = $this->buscarImagen($idItem);

        $datos = ['eliminado' => 1];
        $tipos = 'i';
        $key   = ['field' => 'id_galeria_item', 'value' => $idItem, 'type' => 'i'];

        if (!$database->updateRow('galeria_item', $datos, $tipos, $key)) {
            return false;
        }

        return $imagen;
    }

    public function buscarGaleria($idGaleria)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT id_galeria, titulo, descripcion
            FROM galeria
            WHERE id_galeria = $idGaleria AND eliminado = 0;
        ";

        $database->setQuery($query);

        $galeria = $database->loadObject();
        return $galeria;
    }

    public function guardarGaleria($idSitio, $idGaleria, $titulo, $descripcion)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $datos = [
            'titulo' => $titulo,
            'descripcion' => $descripcion
        ];

        $tipos = 'ss';

        if ($idGaleria > 0) {
            $key = [
                'field' => 'id_galeria',
                'value' => $idGaleria,
                'type' => 'i'
            ];

            if (!$database->updateRow('galeria', $datos, $tipos, $key)) {
                return false;
            }

            return $this->buscarGaleria($idGaleria);
        }


        $query = "
            SELECT MAX(orden) AS total 
            FROM galeria 
            WHERE eliminado = 0 AND id_sitio = $idSitio;
        ";

        $database->setQuery($query);
        $indice = $database->loadField('total') + 1;

        $datos['id_sitio']  = $idSitio;
        $datos['orden']     = $indice;
        $datos['eliminado'] = 0;

        $tipos .= 'iii';

        if (!$database->insertRow('galeria', $datos, $tipos)) {
            return false;
        }

        return $this->buscarGaleria($database->lastId);
    }

    public function borrarGaleria($idGaleria)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $datos = ['eliminado' => 1];
        $tipos = 'i';

        $key = ['field' => 'id_galeria', 'value' => $idGaleria, 'type' => 'i'];

        if (!$database->updateRow('galeria', $datos, $tipos, $key)) {
            return false;
        }

        return $idGaleria;
    }

    public function guardarOrden($idGaleria, $orden)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $datos = ['orden' => $orden];
        $tipos = 'i';
        $key = ['field' => 'id_galeria', 'value' => $idGaleria, 'type' => 'i'];

        if (!$database->updateRow('galeria', $datos, $tipos, $key)) {
            return false;
        }

        return $idGaleria;
    }

    public function buscarImagen($idImagen)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT id_galeria_item, id_galeria, foto
            FROM galeria_item
            WHERE id_galeria_item = $idImagen;
        ";

        $database->setQuery($query);
        $imagen = $database->loadObject();

        return $imagen;
    }

    public function buscarImagenes($idGaleria)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT id_galeria_item, id_galeria, foto
            FROM galeria_item
            WHERE id_galeria = $idGaleria AND eliminado = 0
            ORDER BY orden ASC;
        ";

        $database->setQuery($query);
        $imagenes = $database->loadObjectList();

        return $imagenes;
    }

    public function guardarOrdenImagen($idImagen, $orden)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $datos = ['orden' => $orden];
        $tipos = 'i';
        $key = ['field' => 'id_galeria_item', 'value' => $idImagen, 'type' => 'i'];

        if (!$database->updateRow('galeria_item', $datos, $tipos, $key)) {
            return false;
        }

        return $idImagen;
    }


}
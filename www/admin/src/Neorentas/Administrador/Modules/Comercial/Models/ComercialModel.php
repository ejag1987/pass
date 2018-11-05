<?php

namespace Neorentas\Administrador\Modules\Comercial\Models;

use ZCode\Lighting\Database\Mysql\MysqlProvider;
use ZCode\Lighting\Model\BaseModel;

class ComercialModel extends BaseModel
{
    /**
     * @param $idSitio
     * @return mixed|null|object|\stdClass
     */
    public function cargarDatos($idSitio)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT id_sitio, carpeta, logo, informacion, terreno, construida, arrendable, estacionamientos, 
                fecha_apertura, horario, render1, render2, pie_foto1, pie_foto2
            FROM sitio
            WHERE id_sitio = $idSitio
        ";

        $database->setQuery($query);
        $datos = $database->loadObject();

        return $datos;
    }

    /**
     * @param $idSitio
     * @return mixed|null|object|\stdClass
     */
    public function cargarPagina($idSitio)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT id_pagina, titulo1, titulo2, fondo
            FROM pagina
            WHERE id_sitio = $idSitio AND id_pagina_tipo = 3 AND eliminada = 0;
        ";

        $database->setQuery($query);
        $pagina = $database->loadObject();

        return $pagina;
    }

    /**
     * @param $idSitio
     * @param $idPagina
     * @param $sitio
     * @param $titulo1
     * @param $titulo2
     * @param $texto
     * @param $terreno
     * @param $contruida
     * @param $arrendable
     * @param $estacionemiento
     * @param $fechaApertura
     * @param $horario
     * @return bool|int
     */
    public function guardarSitio($idSitio, $idPagina, $sitio, $titulo1, $titulo2, $texto, $terreno, $contruida, $arrendable,
                                 $estacionemiento, $fechaApertura, $horario)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        // guardar datos en sitio
        $datos = [
            'informacion' => $texto,
            'terreno' => $terreno,
            'construida' => $contruida,
            'arrendable' => $arrendable,
            'estacionamientos' => $estacionemiento,
            'fecha_apertura' => $fechaApertura,
            'horario' => $horario
        ];

        $tipos = 'ssssiss';

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

        $datosPagina['id_sitio']       = $idSitio;
        $datosPagina['alias']          = 'informacion_'.$sitio;
        $datosPagina['id_pagina_tipo'] = 3;
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
     * @return bool|null
     */
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

    /**
     * @param $idSitio
     * @param $nombreArchivo
     * @return bool
     */
    public function guardarImagen($idSitio, $tipo, $nombreArchivo)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $datos = [
            $tipo => $nombreArchivo
        ];

        $tipos = 's';

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

    /**
     * @param $idSitio
     * @param $idPagina
     * @param $sitio
     * @param $nombreArchivo
     * @return bool|int
     */
    public function guardarFondo($idSitio, $idPagina, $sitio, $nombreArchivo)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $datos = ['fondo' => $nombreArchivo];
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

        $datos['id_sitio']       = $idSitio;
        $datos['alias']          = 'informacion_'.$sitio;
        $datos['id_pagina_tipo'] = 3;
        $datos['eliminada']      = 0;
        $datos['fecha_creacion'] = date('Y-m-d H:i:s');

        $tipos .= 'isiis';

        if (!$database->insertRow('pagina', $datos, $tipos)) {
            return false;
        }

        return $database->lastId;
    }


    /**
     * @param $idSitio
     * @return bool|null
     */
    public function borrarImagen($idSitio, $tipo)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT $tipo
            FROM sitio
            WHERE id_sitio = $idSitio;
        ";

        $database->setQuery($query);
        $imagen = $database->loadField($tipo);

        $datos = [$tipo => null];
        $tipos = 's';
        $key   = [
            'field' => 'id_sitio',
            'value' => $idSitio,
            'type' => 'i'
        ];

        if (!$database->updateRow('sitio', $datos, $tipos, $key)) {
            return false;
        }

        return $imagen;
    }

    /**
     * @param $idPagina
     * @return bool|null
     */
    public function borrarFondo($idPagina)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT fondo
            FROM pagina
            WHERE id_pagina = $idPagina;
        ";

        $database->setQuery($query);
        $imagen = $database->loadField('fondo');

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

        return $imagen;
    }

    /**
     * @return array|bool
     */
    public function cargarServicios()
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT
                S.id_servicio,
                S.nombre,
                S.icono
            FROM
                servicio AS S
            ORDER BY S.orden
        ";

        $database->setQuery($query);
        $servicios = $database->loadObjectList();

        return $servicios;
    }

    public function cargarServiciosSitio($idSitio)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT id_servicio
            FROM sitio_servicio
            WHERE id_sitio = $idSitio
            
        ";

        $database->setQuery($query);
        $servicios = $database->loadObjectList();

        return $servicios;
    }

    /**
     * @param $idSitio
     * @return array|bool
     */
    public function cargarServiciosAnteriores($idSitio)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT id_servicio
            FROM sitio_servicio
            WHERE id_sitio = $idSitio;
        ";

        $database->setQuery($query);
        $servicios = $database->loadObjectList();

        return $servicios;
    }

    /**
     * @param $idSitio
     * @param $serviciosNuevos
     * @param $serviciosBorrar
     * @return bool
     */
    public function guardarServicios($idSitio, $serviciosNuevos, $serviciosBorrar)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        if (sizeof($serviciosNuevos) > 0) {
            // insertar
            foreach ($serviciosNuevos as $key => $valor) {
                $datos = [
                    'id_sitio' => $idSitio,
                    'id_servicio' => $valor
                ];

                $tipos = 'ii';

                if (!$database->insertRow('sitio_servicio', $datos, $tipos)) {
                    return false;
                }
            }
        }

        if (sizeof($serviciosBorrar) > 0) {
            // borrar
            foreach ($serviciosBorrar as $key => $value) {
                $query = "
                    DELETE FROM sitio_servicio WHERE (id_sitio = $idSitio AND id_servicio = $value);
                ";

                $database->setQuery($query);
                $database->executeQuery();
            }
        }

        return true;
    }

    public function cargarImagenes($idPagina)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT PI.id_pagina_imagen, PI.id_imagen, I.archivo
            FROM pagina_imagen AS PI
            LEFT OUTER JOIN imagen AS I ON PI.id_imagen = I.id_imagen
            WHERE PI.id_pagina = $idPagina AND PI.eliminada = 0;
        ";

        $database->setQuery($query);
        $imagenes = $database->loadObjectList();

        return $imagenes;
    }

    public function guardarPieRender($idSitio, $pieImagen1, $pieImagen2)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $datos = [
            'pie_foto1' => $pieImagen1,
            'pie_foto2' => $pieImagen2
        ];

        $tipos = 'ss';

        $key   = [
            'field' => 'id_sitio',
            'value' => $idSitio,
            'type' => 'i'
        ];

        if (!$database->updateRow('sitio', $datos, $tipos, $key)) {
            return false;
        }

        return $idSitio;
    }


}

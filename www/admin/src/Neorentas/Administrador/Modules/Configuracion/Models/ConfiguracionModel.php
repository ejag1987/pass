<?php

namespace Neorentas\Administrador\Modules\Configuracion\Models;

use Neorentas\Administrador\System\FuncionesGlobales;
use ZCode\Lighting\Database\Mysql\MysqlProvider;
use ZCode\Lighting\Model\BaseModel;

class ConfiguracionModel extends BaseModel
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
            WHERE id_sitio = $idSitio AND id_pagina_tipo = 1 AND eliminada = 0;
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
     * @return mixed|null|object|\stdClass
     */
    public function cargarDatos($idSitio)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT id_sitio, nombre, link, carpeta, logo, informacion
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
            SELECT id_pagina, titulo1, titulo2, fondo, texto
            FROM pagina
            WHERE id_pagina = $idPagina AND eliminada = 0;
        ";

        $database->setQuery($query);
        $pagina = $database->loadObject();

        return $pagina;
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
        $imagenes = $database->loadObject();

        return $imagenes;
    }

    /**
     * @param $idSitio
     * @return mixed|null|object|\stdClass
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
     * @param $nombre
     * @param $url
     * @return bool|int
     */
    public function guardarSitio($idSitio, $nombre, $url)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $datos = [
            'nombre' => $nombre,
            'link' => $url
        ];

        $tipos = 'ss';

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

    /**
     * @param $idSitio
     * @param $idPagina
     * @param $archivo
     * @return bool
     */
    public function guardarImagen($idSitio, $idPagina, $archivo)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');
        $database->setAutocommit(0);

        $fechaCreacion = date('Y-m-d H:i:s');

        $datos = [
            'archivo' => $archivo,
            'eliminada' => 0,
            'fecha_creacion' => $fechaCreacion
        ];

        $tipos = 'sis';

        if (!$database->insertRow('imagen', $datos, $tipos)) {
            $database->rollback();
             return false;
        }

        $idImagen = $database->lastId;

        if ($idPagina > 0) { // update imagen
            $query = "
                SELECT id_pagina_imagen
                FROM pagina_imagen
                WHERE id_pagina = $idPagina;
            ";

            $database->setQuery($query);
            $idPaginaImagen = $database->loadField('id_pagina_imagen');

            if ($idPaginaImagen > 0) {
                $datosImagenPagina = [
                    'id_imagen' => $idImagen,
                    'eliminada' => 0
                ];

                $tiposImagenPagina = 'ii';

                $key = array(
                    'field' => 'id_pagina_imagen',
                    'value' => $idPaginaImagen,
                    'type' => 'i'
                );

                if (!$database->updateRow('pagina_imagen', $datosImagenPagina, $tiposImagenPagina, $key)) {
                    $database->rollback();
                    return false;
                }

                $database->commit();
                $database->setAutocommit(1);

                return true;
            }

            $datosImagenPagina = [
                'id_pagina' => $idPagina,
                'id_imagen' => $idImagen,
                'orden' => 1,
                'eliminada' => 0
            ];

            $tiposImagenPagina = 'iiii';

            if (!$database->insertRow('pagina_imagen', $datosImagenPagina, $tiposImagenPagina)) {
                $database->rollback();
                return false;
            }

            $database->commit();
            $database->setAutocommit(1);

            return $idPagina;
        }

        // 1. crear pagina
        $datosPagina = [
            'id_sitio' => $idSitio,
            'id_pagina_tipo' => 1,
            'alias' => 'acerca_de_la_empresa',
            'eliminada' => 0,
            'fecha_creacion' => $fechaCreacion
        ];

        $tipoPagina = 'iisis';

        if (!$database->insertRow('pagina', $datosPagina, $tipoPagina)) {
            $database->rollback();
            return false;
        }

        $idPagina = $database->lastId;


        // 2. crear asociación
        $datosImagenPagina = [
            'id_pagina' => $idPagina,
            'id_imagen' => $idImagen,
            'orden' => 1,
            'eliminada' => 0
        ];

        $tiposImagenPagina = 'iiii';

        if (!$database->insertRow('pagina_imagen', $datosImagenPagina, $tiposImagenPagina)) {
            $database->rollback();
            return false;
        }

        $database->commit();
        $database->setAutocommit(1);

        return $idPagina;
    }

    /**
     * @param $idSitio
     * @param $idPagina
     * @param $archivo
     * @return bool|int
     */
    public function guardarFondo($idSitio, $idPagina, $archivo)
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

        $datos['id_sitio']       = $idSitio;
        $datos['id_pagina_tipo'] = 1;
        $datos['alias']          = 'acerca_de_la_empresa';
        $datos['eliminada']      = 0;
        $datos['fecha_creacion'] = date('Y-m-d H:i:s');

        $tipos .= 'iisis';

        if (!$database->insertRow('pagina', $datos, $tipos)) {
            return false;
        }

        return $database->lastId;
    }

    /**
     * @param $idPagina
     * @return bool|mixed|null|object|\stdClass
     */
    public function borrarImagen($idPagina)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT id_pagina_imagen
            FROM pagina_imagen
            WHERE id_pagina = $idPagina;
        ";

        $database->setQuery($query);
        $idPaginaImagen = $database->loadField('id_pagina_imagen');

        if ($idPaginaImagen > 0) {
            $datos = [
                'eliminada' => 1
            ];

            $tipos = 'i';

            $key   = [
                'field' => 'id_pagina_imagen',
                'value' => $idPaginaImagen,
                'type' => 'i'
            ];

            if (!$database->updateRow('pagina_imagen', $datos, $tipos, $key)) {
                return false;
            }

            return true;
        }

        return false;
    }

    /**
     * @param $idSitio
     * @param $idPagina
     * @param $texto
     * @param $titulo1
     * @param $titulo2
     * @return bool|int
     */
    public function guardarPaginaInformacion($idSitio, $idPagina, $texto, $titulo1, $titulo2)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        // guardar titulos pagina
        $datosPagina = [
            'titulo1' => $titulo1,
            'titulo2' => $titulo2,
            'texto' => $texto
        ];

        $tiposPagina = 'sss';

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
        $datosPagina['id_pagina_tipo'] = 1;
        $datosPagina['alias']          = 'acerca_de_la_empresa';
        $datosPagina['eliminada']      = 0;
        $datosPagina['fecha_creacion'] = date('Y-m-d H:i:s');

        $tiposPagina .= 'iisis';

        if (!$database->insertRow('pagina', $datosPagina, $tiposPagina)) {
            return false;
        }

        return $database->lastId;
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


}
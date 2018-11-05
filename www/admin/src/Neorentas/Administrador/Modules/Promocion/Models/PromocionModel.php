<?php

namespace Neorentas\Administrador\Modules\Promocion\Models;

use ZCode\Lighting\Database\Mysql\MysqlProvider;
use ZCode\Lighting\Model\BaseModel;

class PromocionModel extends BaseModel
{
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

    public function buscarPagina($idSitio)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT id_pagina, titulo1, titulo2, fondo
            FROM pagina
            WHERE id_sitio = $idSitio AND id_pagina_tipo = 5 AND eliminada = 0;
        ";

        $database->setQuery($query);
        $pagina = $database->loadObject();

        return $pagina;
    }

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

    public function cargarPromociones($idSitio)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT id_promocion, foto1, foto2, link, fecha_creacion
            FROM promocion 
            WHERE eliminado = 0 AND id_sitio = $idSitio
            ORDER BY orden ASC;
        ";

        $database->setQuery($query);
        $promociones = $database->loadObjectList();

        return $promociones;
    }

    public function buscarPromocion($idPromocion)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT id_promocion, foto1, foto2, link, fecha_creacion
            FROM promocion 
            WHERE id_promocion = $idPromocion;
        ";

        $database->setQuery($query);
        $promocion = $database->loadObject();

        return $promocion;
    }

    public function crearPromocion($idSitio)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT MAX(orden) AS total 
            FROM promocion 
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

        if (!$database->insertRow('promocion', $datos, $tipos)) {
            return false;
        }

        $promocion = $this->buscarPromocion($database->lastId);

        return $promocion;
    }

    public function guardarImagen($idSitio, $idPromocion, $archivo, $campo)
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
            $datos['alias']          = 'promociones_'.$sitio;
            $datos['id_pagina_tipo'] = 5;
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
        $key   = ['field' => 'id_promocion', 'value' => $idPromocion, 'type' => 'i'];

        if (!$database->updateRow('promocion', $datos, $tipos, $key)) {
            return false;
        }

        $promocion = $this->buscarPromocion($idPromocion);

        return $promocion;
    }

    public function borrarImagen($idPromocion, $campo)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        if ($campo == 'fondo') {
            $pagina = $this->cargarPagina($idPromocion);

            $datos = ['fondo' => null];
            $tipos = 's';
            $key   = ['field' => 'id_pagina', 'value' => $idPromocion, 'type' => 'i'];

            if (!$database->updateRow('pagina', $datos, $tipos, $key)) {
                return false;
            }

            return $pagina;
        }

        $promocion = $this->buscarPromocion($idPromocion);

        $datos = [$campo => ''];
        $tipos = 's';
        $key   = ['field' => 'id_promocion', 'value' => $idPromocion, 'type' => 'i'];

        if (!$database->updateRow('promocion', $datos, $tipos, $key)) {
            return false;
        }

        return $promocion;
    }

    public function guardarPromocion($idPromocion, $link)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $datos = ['link' => $link];
        $tipos = 's';
        $key   = ['field' => 'id_promocion', 'value' => $idPromocion, 'type' => 'i'];

        if (!$database->updateRow('promocion', $datos, $tipos, $key)) {
            return false;
        }

        return $idPromocion;
    }

    public function borrarPromocion($idPromocion)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $datos = ['eliminado' => 1];
        $tipos = 'i';
        $key   = ['field' => 'id_promocion', 'value' => $idPromocion, 'type' => 'i'];

        if (!$database->updateRow('promocion', $datos, $tipos, $key)) {
            return false;
        }

        $promocion = $this->buscarPromocion($idPromocion);

        return $promocion;
    }

    public function guardarOrden($idPromocion, $orden)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $datos = ['orden' => $orden];
        $tipos = 'i';

        $key   = ['field' => 'id_promocion', 'value' => $idPromocion, 'type' => 'i'];

        if (!$database->updateRow('promocion', $datos, $tipos, $key)) {
            return false;
        }

        return $idPromocion;
    }

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
        $datosPagina['alias']          = 'promociones_'.$sitio;
        $datosPagina['id_pagina_tipo'] = 5;
        $datosPagina['eliminada']      = 0;
        $datosPagina['fecha_creacion'] = date('Y-m-d H:i:s');

        $tiposPagina .= 'isiis';

        if (!$database->insertRow('pagina', $datosPagina, $tiposPagina)) {
            return false;
        }

        return $database->lastId;
    }

}
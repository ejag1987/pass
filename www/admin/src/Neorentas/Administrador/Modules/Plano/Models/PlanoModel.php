<?php

namespace Neorentas\Administrador\Modules\Plano\Models;

use Neorentas\Administrador\System\FuncionesGlobales;
use ZCode\Lighting\Database\Mysql\MysqlProvider;
use ZCode\Lighting\Model\BaseModel;

class PlanoModel extends BaseModel
{
    /**
     * @param $idSitio
     * @return mixed|null|object|\stdClass
     */
    public function cargarSitio($idSitio)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT id_sitio, carpeta
            FROM sitio
            WHERE id_sitio = $idSitio;
        ";

        $database->setQuery($query);
        $sitio = $database->loadObject();

        return $sitio;
    }

    /**
     * @param $idSitio
     * @return array|bool
     */
    public function cargarPlanos($idSitio)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT id_plano, nombre, imagen, pie_imagen, fecha_actualizacion
            FROM plano
            WHERE id_sitio = $idSitio
            AND eliminado = 0
            ORDER BY orden ASC
        ";

        $database->setQuery($query);
        $planos = $database->loadObjectList();

        return $planos;
    }

    /**
     * @param $idPlano
     * @return bool|mixed|null|object|\stdClass
     */
    public function borrarPlano($idPlano)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT id_plano, imagen
            FROM plano
            WHERE id_plano = $idPlano;
        ";

        $database->setQuery($query);
        $plano = $database->loadObject();

        $datos = ['imagen' => ''];
        $tipos = 's';
        $key   = ['field' => 'id_plano', 'value' => $idPlano, 'type' => 'i'];

        if (!$database->updateRow('plano', $datos, $tipos, $key)) {
            return false;
        }

        return $plano;
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
     * @param $idPlano
     * @param $archivo
     * @return bool|mixed|null|object|\stdClass
     */
    public function guardarImagenPlano($idPlano, $archivo)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $datos = ['imagen' => $archivo];
        $tipos = 's';
        $key   = ['field' => 'id_plano', 'value' => $idPlano, 'type' => 'i'];

        if (!$database->updateRow('plano', $datos, $tipos, $key)) {
            return false;
        }

        $query = "
            SELECT id_plano, imagen
            FROM plano
            WHERE id_plano = $idPlano;
        ";

        $database->setQuery($query);
        $plano = $database->loadObject();

        return $plano;
    }

    /**
     * @param $idSitio
     * @return bool|mixed|null|object|\stdClass
     */
    public function crearPlano($idSitio)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT MAX(orden) AS total 
            FROM plano 
            WHERE eliminado = 0 AND id_sitio = $idSitio;
        ";

        $database->setQuery($query);
        $indice = $database->loadField('total') + 1;

        $datos = [
            'id_sitio' => $idSitio,
            'nombre' => '',
            'eliminado' => 0,
            'orden' => $indice
        ];

        $tipos = 'isii';

        if (!$database->insertRow('plano', $datos, $tipos)) {
            return false;
        }

        $query = "
            SELECT id_plano, nombre, imagen, orden
            FROM plano
            WHERE id_plano = $database->lastId;
        ";

        $database->setQuery($query);
        $plano = $database->loadObject();

        return $plano;
    }

    /**
     * @param $idPlano
     * @param $nombre
     * @return bool
     */
    public function guardarPlano($idPlano, $nombre, $pieImagen, $fechaActualizacion)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $datos = [
            'nombre' => $nombre,
            'pie_imagen' => $pieImagen,
            'fecha_actualizacion' => $fechaActualizacion
        ];
        $tipos = 'sss';
        $key   = ['field' => 'id_plano', 'value' => $idPlano, 'type' => 'i'];

        if (!$database->updateRow('plano', $datos, $tipos, $key)) {
            return false;
        }

        return $idPlano;
    }

    /**
     * @param $idPlano
     * @return bool
     */
    public function cambiarEstadoPlano($idPlano)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $datos = ['eliminado' => 1];
        $tipos = 'i';
        $key   = ['field' => 'id_plano', 'value' => $idPlano, 'type' => 'i'];

        if (!$database->updateRow('plano', $datos, $tipos, $key)) {
            return false;
        }

        return $idPlano;
    }

    /**
     * @param $idPlano
     * @return mixed|null|object|\stdClass
     */
    public function cargarDatosPlano($idPlano)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT S.carpeta, P.imagen, P.id_sitio
            FROM sitio AS S
            LEFT OUTER JOIN plano AS P ON P.id_sitio = S.id_sitio
            WHERE P.id_plano = $idPlano;
        ";

        $database->setQuery($query);
        $plano = $database->loadObject();

        return $plano;
    }

    /**
     * @param $idPlano
     * @param $orden
     * @return bool
     */
    public function actualizarOrdenPlanos($idPlano, $orden)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $datos = [
            'orden' => $orden
        ];

        $tipos = 'i';

        $key = ['field' => 'id_plano', 'value' => $idPlano, 'type' => 'i'];

        if (!$database->updateRow('plano', $datos, $tipos, $key)) {
            return false;
        }

        return $idPlano;
    }

    /**
     * @param $idPlano
     * @return array|bool
     */
    public function cargarLocales($idPlano)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT id_local, numero, disponible, reservado, logo, metros, instalaciones, nota, descripcion, imagen_ficha,
                pie_ficha, fecha_actualizacion
            FROM local 
            WHERE id_plano = $idPlano AND eliminado = 0
            ORDER BY numero ASC;
        ";

        $database->setQuery($query);
        $locales = $database->loadObjectList();

        if ($locales) {
            foreach ($locales as $local) {
                // cargar puntos del local
                $query = "
                    SELECT id_local_punto, lat, `long` 
                    FROM local_punto 
                    WHERE id_local = $local->id_local
                    ORDER BY orden ASC;
                ";

                $database->setQuery($query);
                $local->puntos = $database->loadObjectList();

                // cargar terminaciones del local
                $query = "
                    SELECT id_terminacion, valor
                    FROM local_terminacion
                    WHERE id_local = $local->id_local;
                ";

                $database->setQuery($query);
                $local->terminaciones = $database->loadObjectList();
            }
        }

        return $locales;
    }

    /**
     * @param $idLocal
     * @param $disponible
     * @return bool
     */
    public function cambiarEstado($idLocal, $disponible)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $datos = ['disponible' => $disponible];
        $tipos = 'i';
        $key   = ['field' => 'id_local', 'value' => $idLocal, 'type' => 'i'];

        if (!$database->updateRow('local', $datos, $tipos, $key)) {
            return false;
        }

        return $idLocal;
    }

    /**
     * @param $idSitio
     * @return array|bool
     */
    public function cargarTerminaciones($idSitio)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT id_terminacion, descripcion, numero
            FROM terminacion
            WHERE id_sitio = $idSitio AND eliminado = 0
            ORDER BY numero ASC; 
        ";

        $database->setQuery($query);
        $terminaciones = $database->loadObjectList();

        return $terminaciones;

    }

    /**
     * @param $idLocal
     * @param $numero
     * @param $metros
     * @param $instalaciones
     * @param $nota
     * @param $terminaciones
     * @return bool
     */
    public function guardarDatosLocal($idLocal, $numero, $metros, $instalaciones, $nota, $terminaciones, $pieImagen, $fechaActualizacion)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $database->setAutocommit(0);

        $datos = [
            'numero' => $numero,
            'metros' => $metros,
            'instalaciones' => $instalaciones,
            'nota' => $nota,
            'pie_ficha' => $pieImagen,
            'fecha_actualizacion' => $fechaActualizacion
        ];

        $tipos = 'ssssss';

        if ($idLocal > 0) { // update
            $key = [
                'field' => 'id_local',
                'value' => $idLocal,
                'type' => 'i'
            ];

            if (!$database->updateRow('local', $datos, $tipos, $key)) {
                $database->rollback();
                return false;
            }

            // buscar terminaciones y actualizarlas
            // 1. buscar terminaciones local
            $query = "
                SELECT id_local_terminacion, id_terminacion, valor
                FROM local_terminacion 
                WHERE id_local = $idLocal
            ";

            $database->setQuery($query);
            $terminacionesLocal = $database->loadObjectList();

            $eliminar = [];
            $editar   = [];
            $nuevas   = [];

            if ($terminacionesLocal) {
                foreach ($terminacionesLocal as $key => $value) {
                    $compararEliminados = FuncionesGlobales::buscarValorArray($terminaciones, $value->id_terminacion, 0);

                    if (!$compararEliminados['existe']) {
                        $eliminar[] = $value->id_terminacion;
                    } else {
                        if ($compararEliminados['item'][1] != $value->valor) {
                            $compararEliminados['item'][] = $value->id_local_terminacion;
                            $editar[] = $compararEliminados['item'];
                        }
                    }
                }
            }

            if ($terminaciones) {
                foreach ($terminaciones as $value) {
                    $compararNuevos = FuncionesGlobales::buscarValorObjeto($terminacionesLocal, $value[0], 'id_terminacion');

                    if (!$compararNuevos['existe']) {
                        $nuevas[] = $value;
                    }

                }
            }

            // elimiinar las no existentes
            $datoEliminar = implode(",", $eliminar);
            if ($datoEliminar != '') {
                $query = "DELETE FROM local_terminacion WHERE (id_local = $idLocal AND id_terminacion IN ($datoEliminar));";

                $database->setQuery($query);
                if (!$database->executeQuery()) {
                    $database->rollback();
                    return false;
                }
            }

            // agregar nuevo
            if ($nuevas) {
                foreach ( $nuevas as $value) {
                    $datosTerminacion = [
                        'id_terminacion' => $value[0],
                        'id_local' => $idLocal,
                        'valor' => $value[1]
                    ];

                    $tiposTerminacion = 'iis';

                    if (!$database->insertRow('local_terminacion', $datosTerminacion, $tiposTerminacion)) {
                        $database->rollback();
                        return false;
                    }
                }
            }

            if ($editar) {
                foreach ( $editar as $value) {
                    $datosTerminacion = [
                        'valor' => $value[1]
                    ];

                    $tiposTerminacion = 's';

                    $key = [
                        'field' => 'id_local_terminacion',
                        'value' => $value[2],
                        'type' => 'i'
                    ];

                    if (!$database->updateRow('local_terminacion', $datosTerminacion, $tiposTerminacion, $key)) {
                        $database->rollback();
                        return false;
                    }

                }
            }

            $database->commit();
            $database->setAutocommit(1);

            return true;
        }

        $database->commit();
        $database->setAutocommit(1);

        return true;
    }

    /**
     * @param $idLocal
     * @param $archivo
     * @param $tipo
     * @return bool|mixed|null|object|\stdClass
     */
    public function guardarImagenLocal($idLocal, $archivo, $tipo)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $campo = 'imagen_ficha';

        switch ($tipo) {
            case "plano":
                $campo = 'imagen_ficha';
                break;
            case "logo":
                $campo = 'logo';
                break;
        }

        $datos = [$campo => $archivo];
        $tipos = 's';
        $key   = ['field' => 'id_local', 'value' => $idLocal, 'type' => 'i'];

        if (!$database->updateRow('local', $datos, $tipos, $key)) {
            return false;
        }

        $query = "
            SELECT id_local, imagen_ficha, logo
            FROM local
            WHERE id_local = $idLocal;
        ";

        $database->setQuery($query);
        $local = $database->loadObject();

        return $local;
    }

    /**
     * @param $idLocal
     * @return bool|mixed|null|object|\stdClass
     */
    public function borrarPlanoLocal($idLocal)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT id_local, imagen_ficha
            FROM local
            WHERE id_local = $idLocal;
        ";

        $database->setQuery($query);
        $local = $database->loadObject();

        $datos = ['imagen_ficha' => ''];
        $tipos = 's';
        $key   = ['field' => 'id_plano', 'value' => $idLocal, 'type' => 'i'];

        if (!$database->updateRow('local', $datos, $tipos, $key)) {
            return false;
        }

        return $local;
    }

    /**
     * @param $idPlano
     * @return bool|mixed|null|object|\stdClass
     */
    public function crearLocal($idPlano)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $datos = [
            'id_plano' => $idPlano,
            'numero' => '',
            'disponible' => 1,
            'eliminado' => 0
        ];

        $tipos = 'isii';

        if (!$database->insertRow('local', $datos, $tipos)) {
            return false;
        }

        $query = "
            SELECT id_local, numero, disponible, logo, metros, instalaciones, nota, descripcion, imagen_ficha, pie_ficha, fecha_actualizacion
            FROM local 
            WHERE id_local = $database->lastId;
        ";

        $database->setQuery($query);
        $local = $database->loadObject();

        if ($local) {
            // cargar puntos del local
            $query = "
                SELECT id_local_punto, lat, `long` 
                FROM local_punto 
                WHERE id_local = $local->id_local
                ORDER BY orden ASC;
            ";

            $database->setQuery($query);
            $local->puntos = $database->loadObjectList();

            // cargar terminaciones del local
            $query = "
                SELECT id_terminacion
                FROM local_terminacion
                WHERE id_local = $local->id_local;
            ";

            $database->setQuery($query);
            $local->terminaciones = $database->loadObjectList();
        }

        return $local;
    }

    /**
     * @param $idLocal
     * @return mixed|null|object|\stdClass
     */
    public function cargarLocal($idLocal)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT L.id_local, L.numero, L.disponible, L.logo, L.metros, L.instalaciones, L.nota, L.descripcion, 
                L.imagen_ficha, S.carpeta, S.id_sitio, L.pie_ficha, L.fecha_actualizacion
            FROM local AS L
            LEFT OUTER JOIN plano AS P ON L.id_plano = P.id_plano
            LEFT OUTER JOIN sitio AS S ON P.id_sitio = S.id_sitio
            WHERE id_local = $idLocal;
        ";

        $database->setQuery($query);
        $local = $database->loadObject();

        if ($local) {
            // cargar puntos del local
            $query = "
                SELECT id_local_punto, lat, `long` 
                FROM local_punto 
                WHERE id_local = $local->id_local
                ORDER BY orden ASC;
            ";

            $database->setQuery($query);
            $local->puntos = $database->loadObjectList();

            // cargar terminaciones del local
            $query = "
                SELECT id_terminacion, valor
                FROM local_terminacion
                WHERE id_local = $local->id_local;
            ";

            $database->setQuery($query);
            $local->terminaciones = $database->loadObjectList();
        }

        return $local;
    }

    /**
     * @param $idLocal
     * @param $reservado
     * @return bool
     */
    public function cambiarReserva($idLocal, $reservado)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $datos = ['reservado' => $reservado];
        $tipos = 'i';
        $key   = ['field' => 'id_local', 'value' => $idLocal, 'type' => 'i'];

        if (!$database->updateRow('local', $datos, $tipos, $key)) {
            return false;
        }

        return $idLocal;
    }


}
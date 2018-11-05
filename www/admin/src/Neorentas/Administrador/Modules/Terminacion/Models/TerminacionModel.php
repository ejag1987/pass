<?php

namespace Neorentas\Administrador\Modules\Terminacion\Models;

use ZCode\Lighting\Database\Mysql\MysqlProvider;
use ZCode\Lighting\Model\BaseModel;

class TerminacionModel extends BaseModel
{
    public function cargarTerminaciones($idSitio)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT id_terminacion, numero, descripcion
            FROM terminacion
            WHERE id_sitio = $idSitio
            AND eliminado = 0
            ORDER BY numero ASC;
        ";

        $database->setQuery($query);
        $terminaciones = $database->loadObjectList();

        return $terminaciones;
    }

    public function buscarTerminacionLocal($idTerminacion)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT COUNT(LT.id_local_terminacion) AS total, GROUP_CONCAT(L.numero SEPARATOR ', ') AS locales
            FROM local_terminacion AS LT
            LEFT OUTER JOIN local AS L ON LT.id_local = L.id_local
            WHERE LT.id_terminacion = $idTerminacion;
        ";

        $database->setQuery($query);
        $localTerminacion = $database->loadObject();

        return $localTerminacion;
    }

    public function eliminarTerminacion($idTerminacion, $idSitio)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $database->setAutocommit(0);

        $datos = ['eliminado' => 1];
        $tipos = 'i';
        $key   = ['field' => 'id_terminacion', 'value' => $idTerminacion, 'type' => 'i'];

        if (!$database->updateRow('terminacion', $datos, $tipos, $key)) {
            $database->rollback();
            return false;
        }

        /*$terminaciones = $this->cargarTerminaciones($idSitio);

        if ($terminaciones) {
            foreach ($terminaciones as $index => $terminacion) {
                $datos = ['numero' => ($index+1)];
                $tipos = 'i';
                $key   = ['field' => 'id_terminacion', 'value' => $terminacion->id_terminacion, 'type' => 'i'];

                if (!$database->updateRow('terminacion', $datos, $tipos, $key)) {
                    $database->rollback();
                    return false;
                }
            }
        }*/

        $database->commit();
        $database->setAutocommit(1);

        return true;
    }

    public function guardarTerminaciones($idSitio, $terminaciones)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $database->setAutocommit(0);

        if ($terminaciones) {
            foreach ($terminaciones as $terminacion) {
                $numero        = $terminacion[0];
                $descripcion   = $terminacion[1];
                $idTerminacion = $terminacion[2];

                $datos = [
                    'numero' => $numero,
                    'descripcion' => $descripcion
                ];

                $tipos = 'is';

                if ($idTerminacion == 0) { // crear
                    $datos['id_sitio'] = $idSitio;
                    $tipos .= 'i';

                    if (!$database->insertRow('terminacion', $datos, $tipos)) {
                        $database->rollback();
                        return false;
                    }

                    continue;
                }

                // actualizar
                $key = [
                    'field' => 'id_terminacion',
                    'value' => $idTerminacion,
                    'type' => 'i'
                ];

                if (!$database->updateRow('terminacion', $datos, $tipos, $key)) {
                    $database->rollback();
                    return false;
                }
            }
        }


        $database->commit();
        $database->setAutocommit(1);

        return true;
    }


}
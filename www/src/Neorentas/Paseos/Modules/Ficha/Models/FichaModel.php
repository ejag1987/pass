<?php

namespace Neorentas\Paseos\Modules\Ficha\Models;

use ZCode\Lighting\Database\Mysql\MysqlProvider;
use ZCode\Lighting\Model\BaseModel;

class FichaModel extends BaseModel
{
    /**
     * @param $idLocal
     * @return \stdClass
     */
    public function obtenerDatosFicha($idLocal)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('sitio');

        $query = "SELECT
                        P.nombre,
                        L.numero,
                        L.disponible,
                        L.logo,
                        L.metros,
                        L.altura,
                        L.instalaciones,
                        L.nota,
                        L.descripcion,
                        S.carpeta,
                        L.imagen_ficha,
                        P.id_sitio,
                        L.pie_ficha,
                        L.fecha_actualizacion
                    FROM
                        `local` AS L
                    JOIN plano AS P ON L.id_plano = P.id_plano
                    JOIN sitio AS S ON P.id_sitio = S.id_sitio
                    WHERE
                        L.id_local = $idLocal
                    AND L.eliminado = 0";

        $database->setQuery($query);

        $datos = $database->loadObject();

        $datos->disponible = intval($datos->disponible);

        if ($datos->disponible === 1) {
            $idSitio = intval($datos->id_sitio);

            if ($idSitio > 0) {
                $query = "
                    SELECT
                        LT.id_terminacion, LT.valor, T.id_terminacion, T.numero, T.descripcion 
                    FROM
                        local_terminacion AS LT
                    LEFT OUTER JOIN terminacion AS T ON LT.id_terminacion = T.id_terminacion
                    WHERE
                        id_local = $idLocal
                    ORDER BY T.numero ASC
                ";

                $database->setQuery($query);
                $termLocal = $database->loadObjectList();

                $datos->terminaciones = $termLocal;
            }
        }

        return $datos;
    }
}

<?php

namespace Neorentas\Administrador\Modules\Menusitio\Models;

use ZCode\Lighting\Database\Mysql\MysqlProvider;
use ZCode\Lighting\Model\BaseModel;

class MenusitioModel extends BaseModel
{
    /**
     * @param $idSitio
     * @return array|bool
     */
    public function cargarMenus($idSitio)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT id_menu, id_padre, alias, nombre, link, eliminado
            FROM menu
            WHERE id_sitio = $idSitio AND eliminado = 0
            ORDER BY id_padre ASC, orden ASC;
        ";

        $database->setQuery($query);
        $menus = $database->loadObjectList();

        return $menus;
    }

    /**
     * @param $idSitio
     * @return array|bool
     */
    public function buscarPaginas($idSitio)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $sitio = '';
        if ($idSitio != 1) {
            $sitio = "AND (P.id_sitio = $idSitio OR P.id_sitio = 1)";
        }

        $query = "
            SELECT 
              P.alias AS item_id, 
              CONCAT_WS(' ', S.nombre, '-', P.titulo1, P.titulo2) AS name
            FROM 
              pagina AS P
            LEFT OUTER JOIN sitio AS S ON P.id_sitio = S.id_sitio
            WHERE eliminada = 0
              $sitio
            UNION 
              SELECT '/' AS alias, CONCAT(S.nombre, ' - Inicio') AS name
              FROM sitio AS S 
            WHERE S.id_sitio = $idSitio
            ORDER BY name ASC
        ";

        $database->setQuery($query);
        $paginas = $database->loadObjectList();

        return $paginas;
    }

    /**
     * @param $idSitio
     * @return array|bool
     */
    public function obtenerDatosMenu($idSitio)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT id_menu, id_padre, alias, nombre, link, orden, eliminado
            FROM menu
            WHERE id_sitio = $idSitio
            ORDER BY id_padre ASC, orden ASC;
        ";

        //echo $query;
        $database->setQuery($query);
        $datos = $database->loadObjectList();

        return $datos;
    }

    /**
     * @param $idMenu
     * @param $idSitio
     * @param $alias
     * @param $nombre
     * @param $link
     * @param $orden
     * @param $activo
     * @return bool|int
     */
    public function guardarMenu($idMenu, $idSitio, $alias, $nombre, $link, $orden, $activo)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $datos = [
            'nombre' => $nombre,
            'alias' => $alias,
            'link' => $link
        ];

        $tipos = 'sss';

        if ($idMenu) {
            $key = array(
                'field' => 'id_menu',
                'value' => $idMenu,
                'type' => 'i'
            );

            if (!$database->updateRow('menu', $datos, $tipos, $key)) {
                return false;
            }

            return $idMenu;
        }

        $datos['eliminado'] = 0;
        $datos['orden'] = $orden;
        $datos['id_sitio'] = $idSitio;
        $datos['id_padre'] = 0;

        $tipos .= 'iiii';

        if (!$database->insertRow('menu', $datos, $tipos)) {
            return false;
        }

        return $database->lastId;
    }

    /**
     * @param $menus
     * @return bool
     */
    public function guardarOrden($menus)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        if ($menus) {
            $numeroMenus = sizeof($menus);

            $database->setAutocommit(0);

            for ($i=0; $i<$numeroMenus; $i++) {
                $padre = 0;

                if (intval($menus[$i]['parent_id']) > 0) {
                    $padre = intval($menus[$i]['parent_id']);
                }

                $datos = [
                    'id_padre' => $padre,
                    'orden' => ($i+1)
                ];

                $tipos = 'ii';

                $key = array(
                    'field' => 'id_menu',
                    'value' => $menus[$i]['id'],
                    'type' => 'i'
                );

                if (!$database->updateRow('menu', $datos, $tipos, $key)) {
                    $database->rollback();
                    return false;
                }
            }

            $database->commit();
            $database->setAutocommit(1);

            return true;
        }

        return false;
    }

    /**
     * @param $idMenu
     * @return mixed|null|object|\stdClass
     */
    public function buscarMenu($idMenu)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT id_menu, id_padre, alias, nombre, link, eliminado
            FROM menu
            WHERE id_menu = $idMenu;
        ";

        //echo $query;
        $database->setQuery($query);
        $datos = $database->loadObject();

        return $datos;
    }

    public function cambiarEstadoMenu($idMenu)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $datos = ['eliminado' => 1];
        $tipos = 'i';
        $key   = ['field' => 'id_menu', 'value' => $idMenu, 'type' => 'i'];

        if (!$database->updateRow('menu', $datos, $tipos, $key)) {
            return false;
        }

        return $idMenu;
    }


}


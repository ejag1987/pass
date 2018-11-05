<?php

namespace Neorentas\Administrador\Modules\Portada\Models;

use ZCode\Lighting\Database\Mysql\MysqlProvider;
use ZCode\Lighting\Model\BaseModel;

class PortadaModel extends BaseModel
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
        $carpeta = $database->loadField('carpeta');

        return $carpeta;
    }

    public function cargarSlide($idSitio)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT id_portada_slide, imagen, link, comentario
            FROM portada_slide
            WHERE id_sitio = $idSitio
            AND eliminado = 0
            ORDER BY orden ASC;
        ";

        $database->setQuery($query);
        $slides = $database->loadObjectList();

        return $slides;
    }

    public function cargarCaluga($idSitio)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT id_portada_caluga, imagen, link
            FROM portada_caluga
            WHERE id_sitio = $idSitio AND panel = 1
            ORDER BY orden ASC;
        ";

        $database->setQuery($query);
        $calugas = $database->loadObjectList();

        return $calugas;
    }
    public function cargarCalugaSite($idSitio)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT id_portada_caluga, imagen, link
            FROM portada_caluga
            WHERE id_sitio = $idSitio AND panel != 1
            ORDER BY orden ASC;
        ";

        $database->setQuery($query);
        $calugasite = $database->loadObjectList();

        return $calugasite;
    }
 

    public function crearSlide($idSitio)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT MAX(orden) AS total 
            FROM portada_slide 
            WHERE eliminado = 0 AND id_sitio = $idSitio;
        ";

        $database->setQuery($query);
        $indice = $database->loadField('total') + 1;

        $datos = [
            'id_sitio' => $idSitio,
            'eliminado' => 0,
            'orden' => $indice
        ];

        $tipos = 'iii';

        if (!$database->insertRow('portada_slide', $datos, $tipos)) {
            return false;
        }

        $query = "
            SELECT id_portada_slide, imagen, link, orden, comentario
            FROM portada_slide
            WHERE id_portada_slide = $database->lastId;
        ";

        $database->setQuery($query);
        $slide = $database->loadObject();

        return $slide;
    }

    public function crearCaluga($idSitio)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT MAX(orden) AS total 
            FROM portada_caluga
            WHERE eliminado = 0 AND id_sitio = $idSitio AND panel = 1;
        ";

        $database->setQuery($query);
        $indice = $database->loadField('total') + 1;

        $datos = [
            'id_sitio' => $idSitio,
            'eliminado' => 0,
            'orden' => $indice,
            'panel' => 1
        ];

        $tipos = 'iiii';

        if (!$database->insertRow('portada_caluga', $datos, $tipos)) {
            return false;
        }

        $query = "
            SELECT id_portada_caluga, imagen, link, orden
            FROM portada_caluga
            WHERE id_portada_caluga = $database->lastId;
        ";

        $database->setQuery($query);
        $caluga = $database->loadObject();

        return $caluga;
    }

    public function crearCalugaSite($idSitio)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $query = "
            SELECT MAX(orden) AS total 
            FROM portada_caluga
            WHERE eliminado = 0 AND id_sitio = $idSitio AND panel != 1;
        ";

        $database->setQuery($query);
        $indice = $database->loadField('total') + 1;

        $datos = [
            'id_sitio' => $idSitio,
            'eliminado' => 0,
            'orden' => $indice,
            'panel' => 0
        ];

        $tipos = 'iiii';

        if (!$database->insertRow('portada_caluga', $datos, $tipos)) {
            return false;
        }

        $query = "
            SELECT id_portada_caluga, imagen, link, orden
            FROM portada_caluga
            WHERE id_portada_caluga = $database->lastId;
        ";

        $database->setQuery($query);
        $calugasite = $database->loadObject();

        return $calugasite;
    }


    public function guardarImagen($id, $bloque, $nombreArchivo)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $tabla   = 'portada_'.$bloque;
        $idTabla = 'id_portada_'.$bloque;

        $datos = ['imagen' => $nombreArchivo];
        $tipos = 's';
        $key   = ['field' => $idTabla, 'value' => $id, 'type' => 'i'];

        if (!$database->updateRow($tabla, $datos, $tipos, $key)) {
            return false;
        }

        $query = "
            SELECT $idTabla, id_sitio, imagen, link, orden
            FROM $tabla
            WHERE $idTabla = $id;
        ";

        $database->setQuery($query);
        $reponse = $database->loadObject();

        return $reponse;
    }

    public function guardar($idSlide, $link, $bloque)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $tabla   = 'portada_'.$bloque;
        $idTabla = 'id_portada_'.$bloque;

        $datos = ['link' => $link];
        $tipos = 's';
        $key   = ['field' => $idTabla, 'value' => $idSlide, 'type' => 'i'];

        if (!$database->updateRow($tabla, $datos, $tipos, $key)) {
            return false;
        }

        return $idSlide;
    }

     public function guardarSlide($idSlide, $link, $bloque, $comentario)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $tabla   = 'portada_'.$bloque;
        $idTabla = 'id_portada_'.$bloque;

        $datos = ['link' => $link, 'comentario' => $comentario];
        $tipos = 'ss';
        $key   = ['field' => $idTabla, 'value' => $idSlide, 'type' => 'i'];

        if (!$database->updateRow($tabla, $datos, $tipos, $key)) {
            return false;
        }

        return $idSlide;
    }

    public function borrarImagen($id, $bloque)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $tabla   = 'portada_'.$bloque;
        $idTabla = 'id_portada_'.$bloque;

        $query = "
            SELECT $idTabla, id_sitio, imagen, link, orden
            FROM $tabla
            WHERE $idTabla = $id;
        ";

        $database->setQuery($query);
        $imagen = $database->loadObject();

        $datos = ['imagen' => null];
        $tipos = 's';
        $key   = ['field' => $idTabla, 'value' => $id, 'type' => 'i'];

        if (!$database->updateRow($tabla, $datos, $tipos, $key)) {
            return false;
        }

        return $imagen;
    }

    public function cambiarEstado($id, $bloque)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $tabla   = 'portada_'.$bloque;
        $idTabla = 'id_portada_'.$bloque;

        $datos = ['eliminado' => 1];
        $tipos = 'i';
        $key   = ['field' => $idTabla, 'value' => $id, 'type' => 'i'];

        if (!$database->updateRow($tabla, $datos, $tipos, $key)) {
            return false;
        }

        return $id;
    }

    public function actualizarOrden($idCaja, $orden, $bloque)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('administrador');

        $tabla   = 'portada_'.$bloque;
        $idTabla = 'id_portada_'.$bloque;

        $datos = [
            'orden' => $orden
        ];

        $tipos = 'i';

        $key = ['field' => $idTabla, 'value' => $idCaja, 'type' => 'i'];

        if (!$database->updateRow($tabla, $datos, $tipos, $key)) {
            return false;
        }

        return $idCaja;
    }
}
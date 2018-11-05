<?php

namespace Neorentas\Paseos\Sitio\Modules\Pagina\Models;

use Neorentas\Paseos\Sitio\System\Variables;
use ZCode\Lighting\Database\Mysql\MysqlProvider;
use ZCode\Lighting\Model\BaseModel;

class PaginaModel extends BaseModel
{
    /**
     * @param $alias
     * @return mixed|null|object|\stdClass
     */
    public function buscarPagina($alias)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('sitio');

        $query = "
            SELECT id_pagina, id_pagina_tipo, id_sitio, titulo1, titulo2, fondo, texto
            FROM pagina
            WHERE alias = '$alias' AND eliminada = 0;
        ";


        $database->setQuery($query);
        $pagina = $database->loadObject();

        return $pagina;
    }

    /**
     * @param $idSitio
     * @return mixed|null|object|\stdClass
     */
    public function buscarSitio($idSitio)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('sitio');

        $query = "
            SELECT id_sitio, nombre, link, carpeta, logo, informacion, direccion, terreno, construida, arrendable, 
              estacionamientos, correo, telefono, google_map, horario, como_llegar, fecha_apertura, render1, render2,
              pie_foto1, pie_foto2
            FROM sitio
            WHERE id_sitio = $idSitio
              AND eliminado = 0;
        ";

        $database->setQuery($query);
        $sitio = $database->loadObject();

        return $sitio;
    }

    /**
     * @param $idSitio
     * @return bool|null
     */
    public function buscarCarpeta($idSitio)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('sitio');

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
     * @param $idPagina
     * @return array|bool
     */
    public function buscarImagenes($idPagina)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('sitio');

        $query = "
            SELECT I.archivo
            FROM pagina_imagen AS PI
            LEFT OUTER JOIN imagen AS I ON PI.id_imagen = I.id_imagen 
            WHERE PI.id_pagina = $idPagina
            ORDER BY PI.orden ASC;
        ";

        //echo $query;
        $database->setQuery($query);
        $imagenes = $database->loadObjectList();

        return $imagenes;
    }

    /**
     * @param $idSitio
     * @return array|bool
     */
    public function cargarServicios($idSitio)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('sitio');

        $query = "
            SELECT S.nombre, S.icono
            FROM sitio_servicio AS SS 
            LEFT OUTER JOIN servicio AS S ON S.id_servicio = SS.id_servicio
            WHERE SS.id_sitio = $idSitio
            ORDER BY S.orden ASC;            
        ";

        $database->setQuery($query);
        $servicios = $database->loadObjectList();

        return $servicios;
    }

    /**
     * @param $idSitio
     * @return array|bool
     */
    public function cargarPromociones($idSitio)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('sitio');

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

    /**
     * @param $idSitio
     * @return array|bool
     */
    public function cargarEventos($idSitio, $inicio, $por_pag)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('sitio');

        $query = "SELECT SQL_CALC_FOUND_ROWS id_evento, alias, foto, titulo, descripcion, fecha_evento, horario, fecha_creacion from evento where id_sitio = $idSitio AND eliminado = 0 ORDER BY fecha_evento ASC, orden ASC LIMIT $inicio, $por_pag;";
        $database->setQuery($query);
        $evento = $database->loadObjectList();
        $query_row= "SELECT FOUND_ROWS()as total;";
        $database->setQuery($query_row);
        $rows=$database->loadField('total');

        $noticias = ['noticias' => $evento, 'total' => $rows];

        return $noticias;
    }

    public function cargarNoticia($alias) {

        $database = $this->getDatabase('sitio');
        $query="SELECT id_evento, alias, foto, titulo, descripcion, fecha_creacion, contenido from evento where alias = '$alias' ORDER BY orden ASC;";
        $database->setQuery($query);
        $noticia= $database->loadObject();

        return $noticia;
    }

    public function buscarGalerias($idSitio)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('sitio');

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

        //echo $query;
        $database->setQuery($query);
        $galerias = $database->loadObjectList();

        return $galerias;
    }

    public function buscarGaleria($idGaleria)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('sitio');

        $query = "
            SELECT
                GI.id_galeria_item,
                GI.id_galeria,
                GI.foto,
                S.carpeta
            FROM
                galeria_item AS GI
            LEFT OUTER JOIN galeria AS G ON GI.id_galeria = G.id_galeria
            LEFT OUTER JOIN sitio AS S ON G.id_sitio = S.id_sitio
            WHERE
                GI.id_galeria = $idGaleria
            AND GI.eliminado = 0
            ORDER BY
                GI.orden ASC;
        ";

        //echo $query;
        $database->setQuery($query);
        $galeria = $database->loadObjectList();

        return $galeria;
    }

    public function cargarPaginaInformacionGeneral($idSitio)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('sitio');

        $query = "
            SELECT id_sitio, nombre, link, carpeta, logo, informacion
            FROM sitio
            WHERE id_sitio = $idSitio
        ";

        $database->setQuery($query);
        $pagina = $database->loadObject();

        return $pagina;
    }

    public function cargarPagina($idSitio, $tipo)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('sitio');

        $query = "
            SELECT id_pagina, titulo1, titulo2, fondo, texto
            FROM pagina
            WHERE id_sitio = $idSitio AND id_pagina_tipo = $tipo AND eliminada = 0;
        ";

        $database->setQuery($query);
        $pagina = $database->loadObject();

        return $pagina;
    }

    public function cargarContacto($idSitio)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('sitio');

        $query = "
            SELECT id_sitio, carpeta, link, direccion, correo, telefono, google_map, como_llegar
            FROM sitio
            WHERE id_sitio = $idSitio;
        ";

        $database->setQuery($query);
        $pagina = $database->loadObject();

        return $pagina;
    }

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

    public function obtenerPlanosPaseo($idPaseo)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('sitio');

        $query = "SELECT
                        id_plano,
                        nombre,
                        pie_imagen,
                        fecha_actualizacion
                    FROM
                        plano
                    WHERE
                        id_sitio = $idPaseo
                    AND eliminado = 0
                    ORDER BY
                        orden ASC";

        $database->setQuery($query);
        $planos = $database->loadObjectList();

        return $planos;
    }

    public function obtenerTiendas($idPaseo, $busqueda)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('sitio');

        $query = "
            SELECT L.id_local, L.id_categoria, C.categoria, L.nombre, L.abierto, L.numero, L.alias
            FROM local AS L
            LEFT OUTER JOIN plano AS P ON P.id_plano = L.id_plano
            LEFT OUTER JOIN local_categoria AS C ON C.id_categoria = L.id_categoria
            WHERE L.disponible = 0
              AND P.id_sitio = $idPaseo
              AND L.id_categoria IS NOT NULL
              AND L.nombre LIKE '%$busqueda%' OR C.categoria LIKE '%$busqueda%'
            ORDER BY L.nombre;
        ";

        $database->setQuery($query);
        $tiendas = $database->loadObjectList();

        return $tiendas;
    }

    public function verTienda($idLocal)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('sitio');

        $query = "
            SELECT L.id_local, L.id_categoria, C.categoria, L.nombre, L.abierto, L.logo, L.descripcion, L.foto, 
              L.plano_ubicacion, L.telefono, L.pagina_web, L.numero
            FROM local AS L
            LEFT OUTER JOIN plano AS P ON P.id_plano = L.id_plano
            LEFT OUTER JOIN local_categoria AS C ON C.id_categoria = L.id_categoria
            WHERE L.id_local = $idLocal
        ";

        $database->setQuery($query);
        $tienda = $database->loadObject();

        return $tienda;
    }

    public function obtenerDatosPlano($idPlano)
    {
        $datos = [];
        $locales = [];

        /** @var MysqlProvider $database */
        $database = $this->getDatabase('sitio');

        $query = "SELECT
                        P.imagen,
                        S.carpeta,
                        P.pie_imagen,
                        P.fecha_actualizacion
                    FROM
                        plano AS P
                    JOIN sitio AS S ON P.id_sitio = S.id_sitio
                    WHERE
                        P.id_plano = $idPlano";

        $database->setQuery($query);
        $imagen = $database->loadObject();

        $datos['imagen'] = $imagen->imagen;
        $datos['carpeta'] = $imagen->carpeta;
        $datos['pie_imagen'] = $imagen->pie_imagen;
        $datos['fecha_actualizacion'] = $imagen->fecha_actualizacion;
        $datos['fecha_actualizacion'] = $imagen->fecha_actualizacion;

        $query = "SELECT
                        id_local,
                        disponible
                    FROM
                        `local`
                    WHERE
                        id_plano = $idPlano 
                    AND eliminado = 0 
                    AND reservado = 0;";

        $database->setQuery($query);
        $locales = $database->loadObjectList();

        if ($locales) {
            $totalLocales = sizeof($locales);

            for ($i = 0; $i < $totalLocales; $i++) {
                $query = "SELECT
                                lat,
                                `long`
                            FROM
                                local_punto
                            WHERE
                                id_local = " . $locales[$i]->id_local . "
                            ORDER BY
                                orden ASC";

                $database->setQuery($query);
                $puntos  = $database->loadObjectList();
                $poligono = [];

                if ($puntos) {
                    $totalPuntos = sizeof($puntos);

                    for ($j = 0; $j < $totalPuntos; $j++) {
                        $latlong = [];
                        $latlong[] = $puntos[$j]->lat;
                        $latlong[] = $puntos[$j]->long;

                        $poligono[] = $latlong;
                    }

                    $locales[$i]->poligono = $poligono;
                }
            }
        }

        $datos['locales'] = $locales;

        return $datos;
    }



    /** *******************/
    /** LOGIN - USUARIOS **/
    /** *******************/

    /**
     * @param $usuario
     * @return mixed|null|object|\stdClass
     */
    public function obtenerUsuario($usuario)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('sitio');

        $query = "
            SELECT id_usuario, email, contrasena, nombre, apellido, verificado
            FROM usuario
            WHERE email = '$usuario' AND eliminado = 0;
        ";

        //echo $query;
        $database->setQuery($query);
        $usuario = $database->loadObject();

        return $usuario;
    }

    /**
     * @param $email
     * @return mixed|null|object|\stdClass
     */
    public function verificarUsuario($email)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('sitio');

        $query = "
            SELECT id_usuario, email, contrasena, nombre, apellido, verificado, eliminado
            FROM usuario
            WHERE email = '$email';
        ";

        //echo $query;
        $database->setQuery($query);
        $usuario = $database->loadObject();

        return $usuario;
    }

    /**
     * @param $nombre
     * @param $apellido
     * @param $email
     * @param $rut
     * @param $telefono
     * @param $pass
     * @return bool|int
     */
    public function guardarDatos($nombre, $apellido, $email, $rut, $telefono, $pass)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('sitio');

        $datos = [
            'id_sitio' => Variables::ID_SITIO,
            'nombre' => $nombre,
            'apellido' => $apellido,
            'email' => $email,
            'rut' => $rut,
            'telefono' => $telefono,
            'contrasena' => $pass,
            'verificado' => 0,
            'eliminado' => 0,
            'fecha_creacion' => date('Y-m-d H:i:s'),
            'id_perfil' => 2
        ];

        $tipos = 'issssssiisi';

        if (!$database->insertRow('usuario', $datos, $tipos)) {
            return false;
        }

        return $database->lastId;
    }

    /**
     * @param $codigo
     * @return mixed|null|object|\stdClass
     */
    public function buscarCodigo($codigo)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('sitio');

        $query = "
            SELECT id_usuario, verificado, nombre, apellido, fecha_envio_correo
            FROM usuario 
            WHERE codigo = '$codigo';
        ";

        $database->setQuery($query);
        $usuario = $database->loadObject();

        if (!$usuario) {
            $usuario = null;
        }

        return $usuario;
    }

    /**
     * @param $idUsuario
     * @param $codigo
     * @return bool
     */
    public function actualizarCodigo($idUsuario, $codigo)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('sitio');

        $datos = ['codigo' => $codigo];
        $tipos = 's';


        $key = [
            'field' => 'id_usuario',
            'value' => $idUsuario,
            'type' => 'i'
        ];

        if (!$database->updateRow('usuario', $datos, $tipos, $key)) {
            return false;
        }

        return $idUsuario;
    }

    /**
     * @param $idUsuario
     * @return bool
     */
    public function validarUsuario($idUsuario)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('sitio');

        $datos = [
            'verificado' => 1,
            'fecha_verificacion' => date('Y-m-d H:i:s'),
            'codigo' => null
        ];

        $tipos = 'iss';

        $key = [
            'field' => 'id_usuario',
            'value' => $idUsuario,
            'type' => 'i'
        ];

        if (!$database->updateRow('usuario', $datos, $tipos, $key)) {
            return false;
        }

        return $idUsuario;
    }

    /**
     * @param $idUsuario
     * @return bool
     */
    public function actualizarEnvioCorreo($idUsuario)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('sitio');

        $datos = [ 'fecha_envio_correo' => date('Y-m-d H:i:s') ];
        $tipos = 's';

        $key = [
            'field' => 'id_usuario',
            'value' => $idUsuario,
            'type' => 'i'
        ];

        if (!$database->updateRow('usuario', $datos, $tipos, $key)) {
            return false;
        }

        return $idUsuario;
    }

    /**
     * @param $idUsuario
     * @param $pass
     * @return bool
     */
    public function cambiarPass($idUsuario, $pass)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('sitio');

        $datos = [
            'contrasena' => $pass,
            'codigo' => null
        ];
        $tipos = 'ss';

        $key = [
            'field' => 'id_usuario',
            'value' => $idUsuario,
            'type' => 'i'
        ];

        if (!$database->updateRow('usuario', $datos, $tipos, $key)) {
            return false;
        }

        return $idUsuario;
    }

    /**
     * @param $email
     * @return mixed|null|object|\stdClass
     */
    public function buscarUsuarioCorreo($email)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('sitio');

        $query = "
            SELECT id_usuario, verificado, nombre, apellido, email
            FROM usuario 
            WHERE email = '$email' AND eliminado = 0;
        ";

        $database->setQuery($query);
        $usuario = $database->loadObject();

        if (!$usuario) {
            $usuario = null;
        }

        return $usuario;
    }

    /**
     * @param $idUsuario
     * @return mixed|null|object|\stdClass
     */
    public function buscarUsuario($idUsuario)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('sitio');

        $query = "
            SELECT id_usuario, verificado, nombre, apellido, email
            FROM usuario 
            WHERE id_usuario = $idUsuario;
        ";

        $database->setQuery($query);
        $usuario = $database->loadObject();

        if (!$usuario) {
            $usuario = null;
        }

        return $usuario;
    }

    public function buscarPlanos($idSitio)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('sitio');

        $query = "
            SELECT id_plano
            FROM plano
            WHERE id_sitio = $idSitio AND eliminado = 0;
        ";

        $database->setQuery($query);
        $planos = $database->loadObjectList();

        return $planos;
    }

    public function obtenerDatosTienda($idLocal)
    {
        /** @var MysqlProvider $database */
        $database = $this->getDatabase('sitio');

        $query = "SELECT
                        L.numero,
                        L.nombre,
                        L.logo,
                        L.descripcion,
                        S.carpeta
                    FROM
                        `local` AS L
                    JOIN plano AS P ON L.id_plano = P.id_plano
                    JOIN sitio AS S ON P.id_sitio = S.id_sitio
                    WHERE
                        L.id_local = $idLocal";

        $database->setQuery($query);
        $datos = $database->loadObject();

        if (!$datos) {
            $datos = null;
        }

        return $datos;
    }
}
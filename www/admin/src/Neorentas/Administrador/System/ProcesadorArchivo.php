<?php

namespace Neorentas\Administrador\System;

use Neorentas\Administrador\Objetos\Archivo;

class ProcesadorArchivo
{
    /**
     * @param $indice
     * @param $dir
     * @param bool $incremental
     * @param bool $miniatura
     * @param $maxWidth
     * @param $maxHeight
     * @return bool|Archivo|null
     */
    public static function guardarArchivo($indice, $dir, $incremental=true, $miniatura=true, $maxWidth=100, $maxHeight=100)
    {
        $datosArchivo = null;

        if (isset($_FILES[$indice])) {
            $datosArchivo = pathinfo($_FILES[$indice]['name']);
        }

        if ($datosArchivo === null) {
            return null;
        }

        $archivo            = ProcesadorArchivo::generarRutaArchivo($dir, $datosArchivo, $incremental);
        //$archivo->nombre    = $datosArchivo['filename'];
        $archivo->extension = $datosArchivo['extension'];

        if (!move_uploaded_file($_FILES[$indice]['tmp_name'], $archivo->directorio.$archivo->archivo.'.'.$archivo->extension)) {
            return false;
        }

        if ($miniatura) {
            if (in_array($datosArchivo['extension'], Variables::imagenes)) {
                $miniatura = 'th-'.$archivo->archivo.'.'.$archivo->extension;

                $archivo->miniatura = $miniatura;

                if (!ProcesadorArchivo::make_thumb($archivo, $maxWidth, $maxHeight)) {
                    return false;
                }
            }
        }

        return $archivo;
    }

    /**
     * @param $ruta
     * @param $datosArchivo
     * @param $incremental
     * @return Archivo
     */
    private static function generarRutaArchivo($ruta, $datosArchivo, $incremental)
    {
        $archivo = new Archivo();
        $nombreArchivo = $datosArchivo['filename'];

        $nombre = $nombreArchivo;

        if ($incremental) {
            $i = 0;
            do {
                if ($i != 0) {
                    $nombre = $nombreArchivo;
                }

                $agregado = '_'.$i.'';

                if ($i == 0) {
                    $agregado = '';
                }

                $nombre = $nombre.$agregado;
                $i++;
            } while (file_exists($ruta.'/'.$nombre.'.'.$datosArchivo['extension']));
        }


        $archivo->archivo = $nombre;

        if (!is_dir($ruta)) {
            mkdir($ruta, 0770, true);
        }

        $archivo->nombre     = $nombre;
        $archivo->directorio = $ruta.'/';
        return $archivo;
    }

    /**
     * @param $original
     * @param $maxWidth
     * @param $maxHeight
     * @return bool
     * @internal param $width
     */
    private static function make_thumb($original, $maxWidth, $maxHeight)
    {
        $functions = array(
            IMAGETYPE_GIF => 'imagecreatefromgif',
            IMAGETYPE_JPEG => 'imagecreatefromjpeg',
            IMAGETYPE_PNG => 'imagecreatefrompng',
            IMAGETYPE_WBMP => 'imagecreatefromwbmp',
            IMAGETYPE_XBM => 'imagecreatefromwxbm'
        );

        $imagen    = $original->directorio.$original->archivo.'.'.$original->extension;
        $miniatura = $original->directorio.$original->miniatura;

        $info = @getimagesize($imagen);

        if(!$info) {
            return false;
        }

        if(!$functions[$info[2]]) {
            return false;
        }

        if(!function_exists($functions[$info[2]])) {
            return false;
        }

        $im = $functions[$info[2]]($imagen);

        list($ow, $oh) = getimagesize($imagen);

        $nh = $oh;
        $nw = $ow;

        if($oh > $maxHeight || $ow > $maxWidth){
            $nh = ($oh > $ow) ? $maxHeight : $oh*($maxWidth/$ow);
            $nw = $nh/$oh*$ow;
        }

        //create dst image
        $nm = imagecreatetruecolor($nw, $nh);
        imagesavealpha($nm, true);
        $color = imagecolorallocatealpha($nm, 0, 0, 0, 127);
        imagefill($nm, 0, 0, $color);

        //resize and copy image
        imagecopyresized($nm, $im, 0,0,0,0, $nw, $nh, imagesx($im), imagesy($im));

        if (!imagepng($nm, $miniatura)) {
            return false;
        }

        return true;
    }
}

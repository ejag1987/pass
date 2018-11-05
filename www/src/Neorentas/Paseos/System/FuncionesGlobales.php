<?php

namespace Neorentas\Paseos\System;

class FuncionesGlobales
{
    /**
     * @param $objeto
     * @param $valor
     * @param $indice
     * @return mixed
     */
    public static function buscarValorObjeto($objeto, $valor, $indice)
    {
        $item = null;
        $existe = false;

        if ($objeto) {
            foreach($objeto as $struct) {
                if ($valor == $struct->$indice) {
                    $item = $struct;
                    $existe = true;
                    break;
                }
            }
        }

        $datos['item'] = $item;
        $datos['existe'] = $existe;

        return $datos;
    }

    /**
     * @param $categorias
     * @param $opciones
     * @return string
     */
    public static function optionMultiple($categorias, $opciones)
    {
        $optionCategorias = '';

        if ($categorias) {
            $numItems = sizeof($categorias);

            for ($i = 0; $i < $numItems; $i++) {
                $selected = '';

                if ($opciones) {
                    $asociada = FuncionesGlobales::buscarValorObjeto(
                        $opciones,
                        $categorias[$i]->item_id,
                        'id_categoria_imagen'
                    );

                    if ($asociada['existe']) {
                        $selected = 'selected="selected"';
                    }

                }

                $optionCategorias .= '<option value="'.$categorias[$i]->item_id.'" '.$selected.'>';
                $optionCategorias .= $categorias[$i]->name;
                $optionCategorias .= '</option>';
            }
        }

        return $optionCategorias;
    }

    /**
     * @param $string
     * @return string
     */
    public static function sanitizar($string){
        $alias = strtolower($string);

        $unwanted_array = ['Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A',
            'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I',
            'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
            'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'S', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a',
            'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i',
            'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o',
            'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', ' '=>'_', ','=>'',  '.'=>'' , ':'=>'',
            '('=>'',  ')'=>'',  '"'=>'',  '\''=>'', '´'=>''];
        $alias = strtr( $alias, $unwanted_array );

        if (strlen($alias) > 250) {
            $alias = substr($alias, 1, 250);
        }

        return strtolower($alias);
    }

    public static function crearCarpeta($dir, $carpeta)
    {
        $datos['creado']  = false;
        $datos['mensaje'] = 'Carpeta ya existe.';

        $ruta = $dir.'/'.$carpeta;

        if (!is_dir($ruta)) {
            if (!mkdir($ruta, 0770, true)) {
                $datos['creado']  = false;
                $datos['mensaje'] = 'No se puedo crear la carpeta.';
            }

            $datos['creado']  = true;
            $datos['mensaje'] = 'Carpeta creada.';
        }

        $datos['carpeta'] = $ruta;

        return $datos;
    }

    public static function cpy($source, $dest) {
        if(is_dir($source)) {
            $dir_handle = opendir($source);

            while ($file = readdir($dir_handle)) {
                if ($file!="." && $file!="..") {
                    if (is_dir($source."/".$file)) {
                        if (!is_dir($dest."/".$file)) {
                            mkdir($dest."/".$file);
                        }

                        FuncionesGlobales::cpy($source."/".$file, $dest."/".$file);
                    } else {
                        if (!copy($source."/".$file, $dest."/".$file)) {
                            return false;
                            break;
                        }


                    }
                }
            }
            closedir($dir_handle);
        } else {
            if (!copy($source, $dest)) {
                return false;
            }
        }

        return true;
    }
}
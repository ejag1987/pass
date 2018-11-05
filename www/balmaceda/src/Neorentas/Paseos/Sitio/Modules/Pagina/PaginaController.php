<?php

namespace Neorentas\Paseos\Sitio\Modules\Pagina;

use Neorentas\Paseos\Sitio\Modules\Pagina\Controllers\LoginController;
use Neorentas\Paseos\Sitio\Modules\Pagina\Models\PaginaModel;
use Neorentas\Paseos\Sitio\Modules\Pagina\Views\PaginaView;
use Neorentas\Paseos\Sitio\System\Variables;
use ZCode\Lighting\Controller\BaseController;
use ZCode\Lighting\Http\Request;

class PaginaController extends BaseController
{
    public function run()
    {
        $alias = $this->request->getUrlVar(1);
        $htmlPagina = '';
        $clase = '';

        /** @var PaginaModel $model */
        $model = $this->createModel('PaginaModel');
        $pagina   = $model->buscarPagina($alias);
        $sitio    = $model->buscarSitio($pagina->id_sitio);
        $imagenes = $model->buscarImagenes($pagina->id_pagina);

        /** @var PaginaView $view */
        $view = $this->createView('PaginaView');

        $token = hash('sha512', uniqid(mt_rand(), true));
        $this->session->setString('token', $token);

        switch ($pagina->id_pagina_tipo) {
            case 1: // acerca de la empresa
                $htmlPagina = $view->cargarPaginaGeneral($sitio, $imagenes, $pagina->texto);
                break;
            case 2: // contacto
                $htmlPagina = $view->cargarPaginaContacto($sitio, $token);
                break;
            case 3: // información comercial
                $pagina->titulo1 = 'Información';
                $pagina->titulo2 = 'Comercial';

                $idUsuario = $this->session->getInt('idLogin');
                $mapa      = $this->request->getUrlVar(2);
                $servicios = $model->cargarServicios($pagina->id_sitio);

                $usuario = false;
                if ($idUsuario) {
                    $usuario = true;
                }

                if ($usuario && $mapa) {
                    $planos = $model->obtenerPlanosPaseo($pagina->id_sitio);
                    $htmlPagina = $view->cargarPaginaPlano($alias, $sitio, $planos);
                } else {
                    $existePlano = $model->buscarPlanos($pagina->id_sitio);
                    $htmlPagina = $view->cargarPaginaComercial($alias, $sitio, $usuario, $servicios, $existePlano);
                }

                break;
            case 4: // tiendas
                $tiendas = $model->obtenerTiendas($pagina->id_sitio, '');
                $htmlPagina = $view->cargarTiendas($tiendas, $alias, $sitio);
                break;
            case 5: //promociones
                $promociones = $model->cargarPromociones($pagina->id_sitio);

                $htmlPagina = $view->cargarPromociones($sitio, $promociones);
                break;
            case 6: //noticias
                    $htmlPagina = $this->cargarNoticias($sitio);
                    break;

            case 7: // eventos anteriores
                $galerias = $model->buscarGalerias($pagina->id_sitio);

                $htmlPagina = $view->cargarEventosAnteriores($sitio, $galerias);
                $clase = 'eventos-anteriores';
                break;
        }

        $html = $view->cargarPagina($pagina, $sitio->carpeta, $htmlPagina, $clase);

        $this->response = $html;
    }

    public function runAjax()
    {
        $controller = $this->request->getPostVar('controller', Request::STRING);

        if ($controller && $controller === 'login') {
            /** @var LoginController $controlador */
            $controlador = $this->createController('LoginController');
            $this->response = $controlador->runAjax();
            return;
        }

        $accion = $this->request->getPostVar('accion', Request::STRING);

        if ($accion !== null && strlen($accion) > 0) {
            switch ($accion) {
                case 'cargarGaleria':
                    $this->cargarGaleria();
                    break;
                case 'cargarFichaLocal':
                    $this->cargarFichaLocal();
                    break;
                case 'obtenerDatosPlano':
                    $this->obtenerDatosPlano();
                    break;
                case 'cargarDatosTienda':
                    $this->cargarDatosTienda();
                    break;
                case 'envioCorreo':
                    $this->envioCorreo();
                    break;
                case 'buscarTienda':
                    $this->buscarTienda();
                    break;
                case 'mostrarTienda':
                    $this->mostrarTienda();
                    break;
            }
        }
    }

    private function cargarGaleria()
    {
        $idGaleria = $this->request->getPostVar('idGaleria', Request::INTEGER);

        /** @var PaginaModel $model */
        $model = $this->createModel('PaginaModel');
        $galeria = $model->buscarGaleria($idGaleria);

        /** @var PaginaView $view */
        $view = $this->createView('PaginaView');
        $html = $view->cargarGaleria($galeria);

        $datos['html'] = $html;

        $this->generateJsonResponse(true, '', $datos);
    }

    private function cargarFichaLocal()
    {
        $idLocal = $this->request->getPostVar('idLocal', Request::INTEGER);

        /** @var PaginaModel $model */
        $model = $this->createModel('PaginaModel');

        $datosFicha = $model->obtenerDatosFicha($idLocal);

        /** @var PaginaView $view */
        $view = $this->createView('PaginaView');

        $html = $view->mostrarFichaLocal($datosFicha);

        $datos = [
            'disponible' => $datosFicha->disponible,
            'html' => $html
        ];

        $this->generateJsonResponse(true, '', $datos);
    }

    private function obtenerDatosPlano()
    {
        $idPlano = $this->request->getPostVar('idPlano', Request::INTEGER);

        /** @var PaginaModel $model */
        $model = $this->createModel('PaginaModel');

        $datos = $model->obtenerDatosPlano($idPlano);

        $this->generateJsonResponse(true, '', $datos);
    }

    private function cargarDatosTienda()
    {
        $idLocal = $this->request->getPostVar('idLocal', Request::INTEGER);

        /** @var PaginaModel $model */
        $model = $this->createModel('PaginaModel');

        /** @var PaginaView $view */
        $view = $this->createView('PaginaView');

        $tienda = $model->obtenerDatosTienda($idLocal);
        $html   = $view->obtenerHtmlTienda($tienda);

        $datos = ['html' => $html];

        $this->generateJsonResponse(true, '', $datos);
    }

    private function envioCorreo()
    {
        $nombre  = $this->request->getPostVar('nombre', Request::STRING);
        $email   = $this->request->getPostVar('mail', Request::STRING);
        $mensaje = $this->request->getPostVar('mensaje', Request::STRING);
        $token   = $this->request->getPostVar('token', Request::STRING);

        if (!$this->session->getString('token')) {
            $datos['enviado'] = true;
            $this->generateJsonResponse(false, 'Ya se ha enviado un mensaje anteriormente.', $datos);
            return;
        }

        if ($this->session->getString('token') != $token) {
            $datos['enviado'] = true;
            $this->generateJsonResponse(false, 'No se ha podido enviar el mensaje.', $datos);
            return;
        }

        /** @var PaginaModel $model */
        $model  = $this->createModel('PaginaModel');
        $sitio  = $model->buscarSitio(Variables::ID_SITIO);

        $mail = new \PHPMailer();

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->SMTPDebug = 0;
        $mail->Host = 'localhost';                            // Specify main and backup SMTP servers
        $mail->SMTPAuth = false;
        $mail->SMTPSecure = false;
        $mail->CharSet = 'UTF-8';

        $mail->setFrom('contacto@paseoschile.cl', $sitio->nombre);

        $mail->addAddress($sitio->correo, 'Contacto desde '.$sitio->nombre);
        $mail->isHTML(true);

        $mail->Subject = 'Contacto desde '.$sitio->nombre;

        $titulo = 'Contacto desde '.$sitio->nombre;
        $texto = 'Se ha contactado '.$nombre.'<br>'.'Correo: '.$email.'<br>'.'Mensaje: '.$mensaje;

        /** @var PaginaView $view */
        $view = $this->createView('PaginaView');
        $html = $view->cargarHtmlMail('Contacto desde '.$sitio->nombre, $sitio, $titulo, $texto);

        $mail->Body = $html;

        if(!$mail->send()) {
            //echo 'Mailer Error: ' . $mail->ErrorInfo;
            $this->generateJsonResponse(false, 'No se ha podido enviar el mensaje. Intente nuevamente.');
            return;
        }

        unset($_SESSION['token']);

        $this->generateJsonResponse(true, 'Gracias por contactarte con nosotros.');
    }

    private function buscarTienda()
    {
        $buscar = $this->request->getPostVar('buscar', Request::STRING);

        /** @var PaginaModel $model */
        $model = $this->createModel('PaginaModel');
        $tiendas = $model->obtenerTiendas(Variables::ID_SITIO, $buscar);

        /** @var PaginaView $view */
        $view = $this->createView('PaginaView');

        $html = $view->cargarBusquedaTiendas($tiendas);

        $datos = ['html' => $html];

        $this->generateJsonResponse(true, '', $datos);
    }

    private function mostrarTienda()
    {
        $idLocal = $this->request->getPostVar('idLocal', Request::INTEGER);

        /** @var PaginaModel $model */
        $model = $this->createModel('PaginaModel');
        $tienda = $model->verTienda($idLocal);
        $carpeta = $model->buscarCarpeta(Variables::ID_SITIO);


        /** @var PaginaView $view */
        $view = $this->createView('PaginaView');
        $html = $view->cargarInfoTienda($tienda, $carpeta);

      $datos = ['html' => $html];

       $this->generateJsonResponse(true, '', $datos);
    }

    private function cargarNoticias($sitio)
    {
        $pagina = intval($this->request->getUrlVar(2));
        $alias = $this->request->getUrlVar(2);
       

       if (is_numeric($alias) or !$alias){
        $this->session->setInt('paginaNoticia', $pagina);
      
        $inicio = 0;
        $por_pag = 3;


        if ($pagina > 1){
            $inicio = ($pagina-1) * $por_pag;
        }

        /** @var PaginaModel $model */
        $model = $this->createModel('PaginaModel');
        $noticias = $model->cargarEventos($sitio->id_sitio, $inicio, $por_pag);

        /** @var PaginaView $view */
        $view = $this->createView('PaginaView');
        $html = $view->cargarEventos($sitio, $noticias['noticias'], $noticias['total'], $pagina);


        return $html;
       }

       else {
        $urlRecuperada=$this->session->getInt('paginaNoticia');

        /** @var PaginaModel $model */
           $model = $this->createModel('PaginaModel');
        $noticias = $model->cargarNoticia($alias);

           /** @var PaginaView $view */
        $view = $this->createView('PaginaView');
        $html = $view->cargarNoticia($sitio, $noticias,$urlRecuperada);
        return $html;

       }    
    }
}

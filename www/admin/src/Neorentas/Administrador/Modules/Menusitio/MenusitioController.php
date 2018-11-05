<?php

namespace Neorentas\Administrador\Modules\Menusitio;

use Neorentas\Administrador\Modules\Menusitio\Models\MenusitioModel;
use Neorentas\Administrador\Modules\Menusitio\Views\MenusitioView;
use ZCode\Lighting\Controller\BaseController;
use ZCode\Lighting\Http\Request;

class MenusitioController extends BaseController
{
    public function run()
    {
        $idPerfil = $this->session->getString('idPerfil');
        $html     = '';

        if ($idPerfil != 1) {
            $this->response = $html;
            return;
        }

        $idSitio = $this->request->getUrlVar(1);

        $this->session->setInt('idSitio', intval($idSitio));

        /** @var MenusitioModel $model */
        $model = $this->createModel('MenusitioModel');
        $paginas = $model->buscarPaginas($idSitio);
        $datosMenu = $model->cargarMenus($idSitio);
        $menu = $this->procesarMenu($datosMenu);

        /** @var MenusitioView $view */
        $view = $this->createView('MenusitioView');
        $html = $view->cargarMenu(intval($idSitio), $menu, $paginas);

        $this->response = $html;
    }

    public function runAjax()
    {
        $accion = $this->request->getPostVar('accion', Request::STRING);

        if (strlen($accion) > 0) {
            switch ($accion) {
                case 'guardarMenu':
                    $this->guardarMenu();
                    break;
                case 'guardarOrden':
                    $this->guardarOrden();
                    break;
                case 'crearMenu':
                    $this->crearMenu();
                    break;
                case 'borrarMenu':
                    $this->borrarMenu();
                    break;
            }
        }
    }

    private function procesarMenu($datos, $idPadre = 0)
    {
        $menu = [];

        if ($datos) {
            foreach ($datos as $item) {

                if (intval($item->id_padre) === $idPadre) {
                    $idMenu = intval($item->id_menu);
                    $menu[$idMenu] = $item;

                    $hijos = $this->procesarMenu($datos, $idMenu);

                    if ($hijos) {
                        $menu[$idMenu]->submenu = $hijos;
                    }
                }
            }
        }


        return $menu;
    }

    private function guardarMenu()
    {
        $idSitio = $this->request->getPostVar('idSitio', Request::INTEGER);
        $idMenu  = $this->request->getPostVar('idMenu', Request::INTEGER);
        $alias   = $this->request->getPostVar('pagina-'.$idMenu, Request::STRING);
        $nombre  = $this->request->getPostVar('nombre-'.$idMenu, Request::STRING);
        $link    = $this->request->getPostVar('link-'.$idMenu, Request::STRING);
        $activo  = $this->request->getPostVar('activo', Request::BOOLEAN);

        /** @var MenusitioModel $model */
        $model = $this->createModel('MenusitioModel');
        $orden = sizeof($model->obtenerDatosMenu($idSitio)) +1;

        $eliminado = 0;
        if (!$activo) {
            $eliminado = 1;
        }

        $guardar = $model->guardarMenu($idMenu, $idSitio, $alias, $nombre, $link, $orden, $eliminado);

        if (!$guardar) {
            $this->generateJsonResponse(false, 'Error: No se ha podido guardar.');
            return;
        }

        $datos['idMenu'] = $guardar;

        $this->generateJsonResponse(true, 'Guardado!!', $datos);
    }

    private function guardarOrden()
    {
        $menus = $this->request->getPostVar('menus', Request::ARRAY_VAR);

        /** @var MenusitioModel $model */
        $model = $this->createModel('MenusitioModel');
        $orden = $model->guardarOrden($menus);

        if (!$orden) {
            $this->generateJsonResponse(false, 'No se pudo guardar el orden del menú.');
            return;
        }

        $this->generateJsonResponse(true, 'El nuevo orden del menú se ha guardado');
    }

    private function crearMenu()
    {
        $idMenu    = null;
        $idSitio   = $this->request->getPostVar('idSitio', Request::INTEGER);
        $nombre    = $this->request->getPostVar('nombre', Request::STRING);
        $alias     = $this->request->getPostVar('pagina', Request::STRING);
        $link      = $this->request->getPostVar('link', Request::STRING);
        $eliminado = 0;

        /** @var MenusitioModel $model */
        $model = $this->createModel('MenusitioModel');
        $orden = sizeof($model->obtenerDatosMenu($idSitio)) +1;

        $idMenu = $model->guardarMenu($idMenu, $idSitio, $alias, $nombre, $link, $orden, $eliminado);

        if (!$idMenu) {
            $this->generateJsonResponse(false, 'No se ha podido agregar el menu.');
            return;
        }

        $item    = $model->buscarMenu($idMenu);
        $paginas = $model->buscarPaginas($idSitio);

        /** @var MenusitioView $view */
        $view = $this->createView('MenusitioView');
        $html = $view->crearNuevoMenu($item, $paginas);

        $datos['idMenu'] = $idMenu;
        $datos['html'] = $html;

        $this->generateJsonResponse(true, 'Creado el nuevo menu.', $datos);
    }

    private function borrarMenu()
    {
        $idMenu = $this->request->getPostVar('idMenu', Request::INTEGER);

        /** @var MenusitioModel $model */
        $model = $this->createModel('MenusitioModel');
        $borrar = $model->cambiarEstadoMenu($idMenu);

        if (!$borrar) {
            $this->generateJsonResponse(false, 'No se ha podido borrar el menu.');
            return;
        }

        $this->generateJsonResponse(true, 'El menu ha sido borrado.');
    }
}
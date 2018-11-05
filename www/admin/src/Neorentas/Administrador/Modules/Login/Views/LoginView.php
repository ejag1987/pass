<?php

namespace Neorentas\Administrador\Modules\Login\Views;

use ZCode\Lighting\View\BaseView;

class LoginView extends BaseView
{
    /**
     * @return mixed
     */
    public function mostrarLogin()
    {
        $tmpl = $this->loadTemplate('login');

        $this->addCss('shake-horizontal');

        $this->addCss('login');
        $this->addJs('login');

        return $tmpl->getHtml();
    }
}
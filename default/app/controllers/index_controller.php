<?php

/**
 * Controller por defecto si no se usa el routes
 *
 */
Load::models('usuario');
Load::lib('auth');

class IndexController extends AppController
{

    public function index()
    {
        if (Input::hasPost("login","password")){
            $pwd = Input::post("password");
            $usuario=Input::post("login");

            $auth = new Auth("model", "class: usuario", "login: $usuario", "password: $pwd");
            if ($auth->authenticate()) {
                Redirect::to("admin/");
            } else {
                Flash::error("Falló");
            }
        }        
        
        $this->title = 'Bienvenido';

    }   
}

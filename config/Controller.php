<?php

abstract class Controller
{
    protected $_request;

    public function __construct($peticion)
    {
        $this->_request = $peticion;
    }

    //abstract public function index();

    protected function load_model($modelo,$modulo = false)
    {
        $modelo = $modelo . 'Model';
        $ruta_modelo = Configuration::get('path') . 'models' . DIRECTORY_SEPARATOR . $modelo . '.php';

        if(!$modulo){
            $modulo = $this->_request->get_modulo();
        }

        if($modulo){
            if($modulo != 'default'){
                $ruta_modelo = Configuration::get('path') . 'modules' . DIRECTORY_SEPARATOR . $modulo . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . $modelo . '.php';
            }
        }
        //var_dump($ruta_modelo);
        if(is_readable($ruta_modelo)){
            require_once $ruta_modelo;
            $modelo = new $modelo;
            return $modelo;
        }
        else {
            throw new Exception('Error de modelo');
        }
    }

    protected function load_controller($controlador,$modulo = false)
    {
        $controlador = $controlador . 'Controller';
        $ruta_controlador = Configuration::get('path') . 'controllers' . DIRECTORY_SEPARATOR . $controlador . '.php';

        //var_dump($ruta_controlador);

        if(!$modulo){
            $modulo = $this->_request->get_modulo();
        }
        //var_dump($modulo);
        if($modulo){
            if($modulo != 'default'){
                $ruta_controlador = Configuration::get('path') . 'modules' . DIRECTORY_SEPARATOR . $modulo . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $controlador . '.php';
            }
        }
        //var_dump($ruta_controlador);
        if(is_readable($ruta_controlador)){
            require_once $ruta_controlador;
            $controllerInstacia = new $controlador($this->_request);
            //$controlador = new $controlador;
            return $controllerInstacia;
        }
        else {
            throw new Exception('Error, controlador a cargar dinámicamente no existe');
        }
    }

    protected function get_library($libreria)
    {
        $ruta_libreria = Configuration::get('path') . 'libs' . DIRECTORY_SEPARATOR . $libreria . '.php';

        if(is_readable($ruta_libreria)){
            require_once $ruta_libreria;
        }
        else{
            throw new Exception('Error de libreria');
        }
    }

    protected function get_param($clave)
    {
        if(isset($this->_request->get_params()[$clave])){
            return $this->_request->get_params()[$clave];
        }
    }
}

?>

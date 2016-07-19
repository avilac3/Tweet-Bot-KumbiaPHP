<?php

/**
 * Controller por defecto si no se usa el routes
 *
 */
Load::models('tweet','configuracion');  // carga modelos
Load::lib('auth');

class AdminController extends AppController
{
    /**
     * Método para agregar
     */
    public function index(){
        if(Auth::is_valid()){        
            $this->count_tweets = Load::model('tweet')->count();
            $this->title = 'Bienvenido Admin';
        }  else {
            return Redirect::to("/");
        }
    }
    
     /**
     * Método para agregar
     */   
    public function listar(){
        if(Auth::is_valid()){         
            $this->title = 'Listar Tweets';
            $this->tweets = Load::model('tweet')->find("order: id desc");      
        }  else {
            return Redirect::to("/");
        }        
    }
    
    /**
     * Método para agregar
     */
    public function Agregar(){
        if(Auth::is_valid()){                 
            if(Input::hasPost('tweet')) {
                $tweet = new Tweet(Input::post('tweet'));
                $tweet->estado = 1;
                if($tweet->save()) {
                    Flash::valid('El Tweet se ha creado correctamente.');
                    return Redirect::toAction("listar");
                } else {
                    Flash::error('Error.');
                }            
            }
        }  else {
            return Redirect::to("/");
        }        
        $this->title = 'Agregar Tweet';           
    }   
    
    /**
     * Método para Editar
     */
    public function editar($id){
        if(Auth::is_valid()){                        
            $tweet = new Tweet();
            if(!$tweet->find_first($id)) {
                Flash::error('Lo sentimos, no se ha podido establecer la información del tweet');    
                return Redirect::toAction('listar');
            }   

            if(Input::hasPost('tweet')) {            
                $tweet = new Tweet(Input::post('tweet'));
                $tweet->find_first($id);
                if($tweet->update(Input::post('tweet'))){
                    Flash::valid('El Tweet se ha actualizado correctamente.');
                    return Redirect::toAction('listar');
                } else {
                    Flash::error('Error.');
                } 
            } 
            $this->title = 'Editar Tweet';                   
            $this->tweet = $tweet;
        }  else {
            return Redirect::to("/");
        }         
    } 
    
    /**
     * Método para la configuracion general
     */ 
    public function configuracion(){
        if(Auth::is_valid()){                        
            $this->configuracion = Load::model('configuracion')->find(); 
            $this->title = 'Configuración';
        }  else {
            return Redirect::to("/");
        }          
    }
    
    /**
     * Método para la configuracion general
     */ 
    public function configuracion_editar($id){
        if(Auth::is_valid()){                        
            $configuracion = new configuracion();
            if(!$configuracion->find_first($id)) {
                Flash::error('Lo sentimos, no se ha podido establecer la información');    
                return Redirect::toAction('configuracion');
            }   

            if(Input::hasPost('configuracion')) {            
                $configuracion = new configuracion(Input::post('configuracion'));
                $configuracion->find_first($id);
                if($configuracion->update(Input::post('configuracion'))){
                    Flash::valid('La configuración se ha actualizado correctamente.');
                    return Redirect::toAction('configuracion');
                } else {
                    Flash::error('Error.');
                } 
            } 
            $this->title = 'Editar Configuración';                   
            $this->configuracion = $configuracion;  
        }  else {
            return Redirect::to("/");
        }          
    } 
    
    /**
     * Método para la Cerrar session
     */     
    public function salir(){
    	Auth::destroy_identity();
    	//Flash::success("sesión finanliza");
    	return Redirect::to("./");
    }
    
}

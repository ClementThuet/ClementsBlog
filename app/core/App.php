<?php

class App {
    
    protected $controller = 'home';
    protected $method = 'E404';
    protected $params = [];
    
    public function __construct() {
        //Démarrage de la session pour toutes les pages
        session_start();
        
        //Découpage de l'URL sur la caractère "/" et retire les caractères illégaux
        $url =$this->parseUrl();
        
        //La première partie correspond au controlleur, si il existe on le stock en variable
        if(file_exists('../app/controllers/' .$url[0]. '.php')){
            $this->controller = $url[0];
            //On désatribue la variable pour ne garder que les paramètres optionnels dans $url
            unset($url[0]);
        }
        //On appele le controlleur
        require_once dirname(__DIR__, 2).'/app/controllers/'. $this->controller .'.php';
        
        //La première partie correspond à la méthode, si elle existe on la stock en variable ($this->method)
        if (isset($url[1])){
            if (method_exists($this->controller, $url[1])){
                $this->method = $url[1];
                unset($url[1]);
            }
        }
        //le reste de l'URL correspond aux paramètres
        $this->params = $url ? array_values($url) : [];
        
        //Appelle de la méthode $this->method du controller $this->controller avec en paramètres $this->params
        call_user_func_array([new $this->controller,$this->method],$this->params);
    }
    
    //Permet de découper l'URL
    public function parseUrl(){
        if(isset($_GET['url'])){
                return $url= explode('/',filter_var(rtrim($_GET['url'],'/'),FILTER_SANITIZE_URL));
        }
        //Si l'URL est la racine du site (clementsblog/) on affiche la page d'accueil (home/index)
        else{
            $url=['home','index'];
            return $url;
        }
    }
}

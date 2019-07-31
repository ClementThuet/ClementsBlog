<?php

use Doctrine\Common\DataFixtures\Loader;
use ClementsBlog\app\fixtures\dataFixtures;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

class App {
    
    protected $controller = 'home';
    protected $method = 'E404';
    protected $params = [];
    
    public function __construct() {
        
        $url =$this->parseUrl();
        if(file_exists('../app/controllers/' .$url[0]. '.php')){
            $this->controller = $url[0];
            unset($url[0]);
        }
        //Si controller existe on l'appele sinon par défaut => home
        require_once '../app/controllers/'. $this->controller .'.php';
       
        $this->controller = new $this->controller;
        
        if (isset($url[1])){
            if (method_exists($this->controller, $url[1])){
                $this->method = $url[1];
                unset($url[1]);
            }
        }
        
        $this->params = $url ? array_values($url) : [];
        call_user_func_array([$this->controller,$this->method],$this->params);
    }
    
    public function parseUrl(){
        if(isset($_GET['url'])){
            if($_GET['url']=='loadfixtures'){
               /* 
                $loader = new Loader();
                //$loader->addFixture(new UserDataLoader());
                $loader->addFixture(new UserFixtureLoader());
        
                $purger = new ORMPurger();
                $executor = new ORMExecutor($em, $purger);
                $executor->execute($loader->getFixtures(), true);*/
            }
            else{
                return $url= explode('/',filter_var(rtrim($_GET['url'],'/'),FILTER_SANITIZE_URL));
            }
            
            
        }
        //Si racine du site (clementsblog/) on affiche la page d'accueil (home/index)
        else{
            $url=['home','index'];
            return $url;
        }
    }
}

<?php

class Home extends Controller{
    
    public function index(){
        
        //Rendu du template
        $loader = new Twig\Loader\FilesystemLoader(__DIR__.'../../views');
        $twig = new Twig\Environment($loader);
        
        echo $twig->render('home.twig',['param'=>'test']);
        
   }
   
   public function E404(){
        
        //Rendu du template
        /*$loader = new Twig\Loader\FilesystemLoader(__DIR__.'../../views');
        $twig = new Twig\Environment($loader);
        
        echo $twig->render('404.twig');*/
        
   }

        
}
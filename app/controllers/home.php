<?php

class Home extends Controller{
   
    public function index(){
        
        //Impossible de joindre database.php (récupération de l'entity manager
        include('database2.php');
        
        
        $loader = new Twig\Loader\FilesystemLoader(__DIR__.'../../views');
        $twig = new Twig\Environment($loader);
        $twig->addExtension(new Twig_Extensions_Extension_Text());
        
        //Récupération des articles
        $articleRepository=$entityManager->getRepository('Article');
        $articles = $articleRepository->findAll();
        
        //Rendu du template
        echo $twig->render('home.twig',['articles'=>$articles]);
        

   }
   
   public function articles(){
       
       //Impossible de joindre database.php (récupération de l'entity manager
        include('database2.php');
       
        $loader = new Twig\Loader\FilesystemLoader(__DIR__.'../../views');
        $twig = new Twig\Environment($loader);
        $twig->addExtension(new Twig_Extensions_Extension_Text());
        
        //Récupération des articles
        $articleRepository=$entityManager->getRepository('Article');
        $articles = $articleRepository->findAll();
        
        //Rendu du template
        echo $twig->render('articles.twig',['articles'=>$articles]);
   }
   
   public function article($idArticle){
       
       //Impossible de joindre database.php (récupération de l'entity manager
        include('database2.php');
       
        $loader = new Twig\Loader\FilesystemLoader(__DIR__.'../../views');
        $twig = new Twig\Environment($loader);
        $twig->addExtension(new Twig_Extensions_Extension_Text());
        
        //Récupération des articles
        $articleRepository=$entityManager->getRepository('Article');
        $article = $articleRepository->find($idArticle);
        
        //Rendu du template
        echo $twig->render('article.twig',['article'=>$article]);
   }
   
   public function E404(){
        
        //Rendu du template
        $loader = new Twig\Loader\FilesystemLoader(__DIR__.'../../views');
        $twig = new Twig\Environment($loader);
        echo $twig->render('404.twig');
   }


        
}
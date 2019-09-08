<?php

//require (dirname(__DIR__, 2).'/vendor/autoload.php');
use ClementsBlog\Article;
use ClementsBlog\Comment;
use ClementsBlog\User;

use Doctrine\ORM\Tools\Pagination\Paginator;

class Home extends Controller{
   
    public function E404(){
        
        //Rendu du template
        $loader = new Twig\Loader\FilesystemLoader(__DIR__.'../../views');
        $twig = new Twig\Environment($loader);
        echo $twig->render('404.twig');
    } 
    
    public function index(){
        
        include(dirname(__DIR__, 1).'/database.php');
        $loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__, 1).'/views');
        $twig = new Twig\Environment($loader);
        $twig->addExtension(new Twig_Extensions_Extension_Text());
        $twig->addGlobal('session', $_SESSION);
        
        //Récupération des articles
        $articleRepository=$entityManager->getRepository('Article');
        $articles = $articleRepository->findAll();
        echo $twig->render('home.twig',['articles'=>$articles]);
    }
   
    public function articles($page){
       
        include(dirname(__DIR__, 1).'/database.php');
       
        $loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__, 1).'/views');
        $twig = new Twig\Environment($loader);
        $twig->addExtension(new Twig_Extensions_Extension_Text());
        $twig->addGlobal('session', $_SESSION);
        //Récupération des articles
        $articleRepository=$entityManager->getRepository('Article');
        //3 articles par page
        $minResult=3*$page-3;
        $maxResult=3*$page;
        $articles = $articleRepository->findAllByDateDESC($minResult,$maxResult);
        
        echo $twig->render('articles.twig',['articles'=>$articles]);
    }
   
    public function article($idArticle){
       
        include(dirname(__DIR__, 1).'/database.php');
       
        $loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__, 1).'/views');
        $twig = new Twig\Environment($loader);
        $twig->addExtension(new Twig_Extensions_Extension_Text());
        $twig->addGlobal('session', $_SESSION);
        //Récupération des articles
        $articleRepository=$entityManager->getRepository('Article');
        $article = $articleRepository->find($idArticle);
        $user=$article->getUser();
        
        
        $articles=$user->getArticles();
        $commentsRepository=$entityManager->getRepository('Comment');
        $commentaires=$article->getCommentaires();
        //var_dump($commentaires); // NULL
        
        echo $twig->render('article.twig',['article'=>$article,'commentaires'=>$commentaires,'user'=>$user]);
    }
   
    public function rechercherArticle(){
       
        $loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__, 1).'/views');
        $twig = new Twig\Environment($loader);
        $twig->addExtension(new Twig_Extensions_Extension_Text());
        $twig->addGlobal('session', $_SESSION);
        
        include(dirname(__DIR__, 1).'/database.php');
        $articleRepo=$entityManager->getRepository('Article');
        $articlesRecherchees=$articleRepo->rechercheArticle($_POST['recherche']);
        $recherche=true;
        echo $twig->render('articles.twig',['articles'=>$articlesRecherchees,'recherche'=>$recherche]);
    }

    public function contact(){
       
        $loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__, 1).'/views');
        $twig = new Twig\Environment($loader);
        $twig->addGlobal('session', $_SESSION);
        //Rendu du template
        echo $twig->render('contact.twig');
    }
    
    public function sendEmail(){
        // configuration
        $from = 'Clément Thuet <clementthuet7@gmail.com>';
        $sendTo = 'Clément Thuet <clementthuet7@gmail.com>';
        $subject = 'Nouveau message via le formulaire de contact de ClementsBlog';
        $fields = array('name' => 'Nom', 'surname' => 'Prénom', 'phone' => 'Téléphone', 'email' => 'Email', 'message' => 'Message'); // array variable name => Text to appear in email
        $okMessage = 'Message envoyé avec succès';
        $errorMessage = 'Une erreur est survenue lors de l\'envoi du message, veuillez réesayer';

        //envoi
        try
        {
            $emailText = "Nouveau message via le formulaire de contact de ClementsBlog\n=============================\n";

            foreach ($_POST as $key => $value) {

                if (isset($fields[$key])) {
                    $emailText .= "$fields[$key]: $value\n";
                }
            }
            mail($sendTo, $subject, $emailText, "From: " . $from);
            $responseArray = array('type' => 'success', 'message' => $okMessage);
        }
        catch (\Exception $e)
        {
            $responseArray = array('type' => 'danger', 'message' => $errorMessage);
        }

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $encoded = json_encode($responseArray);

            header('Content-Type: application/json');

            echo $encoded;
        }
        else {
            echo $responseArray['message'];
        }
    }
    
    public function quisuisje(){
         //Rendu du template
        $loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__, 1).'/views');
        $twig = new Twig\Environment($loader);
        $twig->addGlobal('session', $_SESSION);
        echo $twig->render('quiSuisJe.twig');
    }
    


        
}
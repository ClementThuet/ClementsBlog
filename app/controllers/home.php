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
        $articles = $articleRepository->findAllByDateDESC();
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
        $user=$article->getUser();
        $articles=$user->getArticles();
        $commentsRepository=$entityManager->getRepository('Comment');
        $commentaires=$article->getCommentaires();
        //Rendu du template
        echo $twig->render('article.twig',['article'=>$article,'commentaires'=>$commentaires,'user'=>$user]);
   }
   
   public function rechercherArticle(){
       
        $loader = new Twig\Loader\FilesystemLoader(__DIR__.'../../views');
        $twig = new Twig\Environment($loader);
        $twig->addExtension(new Twig_Extensions_Extension_Text());
        $twig->addGlobal('session', $_SESSION);
        
        include('database2.php');
        $articleRepo=$entityManager->getRepository('Article');
        $articlesRecherchees=$articleRepo->rechercheArticle($_POST['recherche']);
        
        $recherche=true;
        echo $twig->render('articles.twig',['articles'=>$articlesRecherchees,'recherche'=>$recherche]);
    }

    public function contact(){
       
        $loader = new Twig\Loader\FilesystemLoader(__DIR__.'../../views');
        $twig = new Twig\Environment($loader);
        
        //Rendu du template
        echo $twig->render('contact.twig');
    }
    
    public function sendEmail(){
        // configure (à mettre ailleurs)
        $from = 'Clément Thuet <clementthuet7@gmail.com>';
        $sendTo = 'Clément Thuet <clementthuet7@gmail.com>';
        $subject = 'Nouveau message via le formulaire de contact de ClementsBlog';
        $fields = array('name' => 'Nom', 'surname' => 'Prénom', 'phone' => 'Téléphone', 'email' => 'Email', 'message' => 'Message'); // array variable name => Text to appear in email
        $okMessage = 'Message envoyé avec succès';
        $errorMessage = 'Une erreur est survenue lors de l\'envoi du message, veuillez réesayer';

        // let's do the sending
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
        $loader = new Twig\Loader\FilesystemLoader(__DIR__.'../../views');
        $twig = new Twig\Environment($loader);
        echo $twig->render('quiSuisJe.twig');
    }
    public function E404(){
        
        //Rendu du template
        $loader = new Twig\Loader\FilesystemLoader(__DIR__.'../../views');
        $twig = new Twig\Environment($loader);
        echo $twig->render('404.twig');
    }


        
}
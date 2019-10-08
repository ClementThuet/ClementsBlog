<?php

//Controlleur pour toutes les actions effectuable par tous les utilisateurs
class Home{
   
    //Affiche la page d'erreur 404
    public function E404()
    {
        require_once(dirname(__DIR__, 1).'/includes/TwigConfig.php');
        echo $twig->render('404.twig');
    } 
    
    //Affiche la page d'accueil avec les derniers articles
    public function index(){
        
        require_once(dirname(__DIR__, 1).'/includes/TwigConfig.php');
        require_once(dirname(__DIR__, 1).'/database.php');
        
        $articleRepository=$entityManager->getRepository('Article');
        $articles = $articleRepository->findAll();
        echo $twig->render('home.twig',['articles'=>$articles]);
    }
   
    // Affiche la liste des articles, $page correspond à la page en cours pour la pagination
    public function articles($page){
       
        require_once(dirname(__DIR__, 1).'/includes/TwigConfig.php');
        require_once(dirname(__DIR__, 1).'/database.php');

        $articleRepository=$entityManager->getRepository('Article');
        //Pour la pagination permet d'avoir 3 articles par page, renvoit $minResult et $maxResult à la requète DQL
        $minResult=3*$page-3; //Exemple : Pour la page 2, les résultats iront de 3 à 6
        $maxResult=3*$page;
        //Fonction retournant les articles triés par date décroissantes correspondant aux articles de la page en cours
        $articles = $articleRepository->findAllByDateDESC($minResult,$maxResult);
        echo $twig->render('articles.twig',['articles'=>$articles]);
    }
   
    //Affiche l'article d'id $idArticle
    public function article($idArticle){
       
        require_once(dirname(__DIR__, 1).'/includes/TwigConfig.php');
        require_once(dirname(__DIR__, 1).'/database.php');
       
        //Récupération du contenu de l'article, de l'utilisateur et des commentaires de l'article pour l'affichage
        $articleRepository=$entityManager->getRepository('Article');
        $article = $articleRepository->find($idArticle);
        $user=$article->getUser();
        $commentaires=$article->getCommentaires();
        echo $twig->render('article.twig',['article'=>$article,'commentaires'=>$commentaires,'user'=>$user]);
    }
   
    //Recherche des articles sur leur titre selon la saisie de l'utilisateur
    public function rechercherArticle(){
       
        require_once(dirname(__DIR__, 1).'/includes/TwigConfig.php');
        require_once(dirname(__DIR__, 1).'/database.php');
        
        $articleRepo=$entityManager->getRepository('Article');
        //Recherche LIKE %string%, maximum 10 résultats
        $articlesRecherchees=$articleRepo->rechercheArticle($_POST['recherche']);
        //On stock le fait qu'une recherche a été effectuée pour afficher un bouton réinitialiser
        $recherche=true;
        echo $twig->render('articles.twig',['articles'=>$articlesRecherchees,'recherche'=>$recherche]);
    }

    //Affiche la page contact
    public function contact(){
       
        require_once(dirname(__DIR__, 1).'/includes/TwigConfig.php');
        echo $twig->render('contact.twig');
    }
    
    //Permet d'envoyer le mail via le formulaire de contact, configuration : wamp/sendmail/sendmail.ini voir https://www.grafikart.fr/blog/mail-local-wamp
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
    
    //Affiche la page quisuisje
    public function quisuisje(){
        
        require_once(dirname(__DIR__, 1).'/includes/TwigConfig.php');
        echo $twig->render('quiSuisJe.twig');
    }
    
}
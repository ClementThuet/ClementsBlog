<?php

//Controlleur pour toutes les actions effectuable par les utilisateurs inscrits ou s'inscrivant
class Admin extends Controller{
   
    //Affiche la page d'erreur 404
    public function E404(){
        
        require_once(dirname(__DIR__, 1).'/includes/TwigConfig.php');
        echo $twig->render('404.twig');
    }
    
    //Affiche la page de login avec l'état de la connexion, par défaut NULL
    public function login($messageRetour = null){
        
        require_once(dirname(__DIR__, 1).'/includes/TwigConfig.php');
        echo $twig->render('login.twig',['messageRetour'=>$messageRetour]);
    }
    
    //Logique de la connexion, vérifie et connecte l'utilisateur
    public function loginUser(){
        
        require_once(dirname(__DIR__, 1).'/includes/TwigConfig.php');
        require_once(dirname(__DIR__, 1).'/database.php');
        $loginSuccess=false; 
        //Recherche parmis les utilisateurs
        $userRepository=$entityManager->getRepository('User');
        $users = $userRepository->findAll();
        foreach ($users as $user){
            //Vérification si les identifiants soumis existent parmis nos utilisateurs
            if($user->getAdresseEmail()==$_POST['login']){
                if(password_verify($_POST['password'], $user->getMotDePasse())){
                    //On enregistre les informations utilisateurs en session
                    $_SESSION['logged'] = true;
                    $_SESSION['id'] = $user->getId();
                    $_SESSION['mail'] = $user->getAdresseEmail();
                    $_SESSION['prenom'] = $user->getPrenom();
                    $_SESSION['nom'] = $user->getNom();
                    header('Location: dashboard'); 
                }
            }
        }
        if ($loginSuccess==false){
            $messageRetour ="Identifiants incorrects";
            $messageAlert="danger";
            echo $twig->render('login.twig',['messageRetour'=>$messageRetour,'msgAlert'=>$messageAlert]);
        }
    }
    
    //Affiche le tableau de bord
    public function dashboard(){
        
        require_once(dirname(__DIR__, 1).'/includes/TwigConfig.php');
        //Réinitilisation du flashmessage
        $_SESSION['flashmessage']='';
        echo $twig->render('dashboard.twig');
    }
    
    //Affiche la page d'inscription
    public function signup(){
        
        require_once(dirname(__DIR__, 1).'/includes/TwigConfig.php');
        echo $twig->render('signup.twig');
    }
    
    //Logique de l'inscription, vérification que les 2 mots de passe saisies correspondent
    //et que l'adresse mail n'existe pas déjà
    public function registerUser(){
        
        require_once(dirname(__DIR__, 1).'/includes/TwigConfig.php');
        require_once(dirname(__DIR__, 1).'/database.php');
        
        $userRepository=$entityManager->getRepository('User');
        $users = $userRepository->findAll();
        $doublonMail=false;
        if ($_POST['password']==$_POST['passwordRepeat']){
            foreach ($users as $user) {
                if($_POST['mail']==$user->getAdresseEmail()){
                    $doublonMail=true;
                }
            }
            if($doublonMail == false){
                $user = new User;
                $user->setAdresseEmail($_POST['mail']);
                $user->setMotDePasse(password_hash($_POST['password'], PASSWORD_DEFAULT));
                $user->setNom($_POST['nom']);
                $user->setPrenom($_POST['prenom']);
                $user->setType('utilisateur');
                $entityManager->persist($user);
                $entityManager->flush();
                $messageRetour="Inscription effectuée, vous pouvez vous connecter";
                $messageAlert="success";
                echo $twig->render('login.twig',['messageRetour'=>$messageRetour,'msgAlert'=>$messageAlert]);
            }
            else{
                $messageRetour ="L'adresse email saisie existe déjà, essayer de vous connecter";
                $messageAlert="danger";
                echo $twig->render('login.twig',['messageRetour'=>$messageRetour,'msgAlert'=>$messageAlert]);
            }
        }
        else{
            $messageRetour ="Veuillez saisir deux mots de passe identiques.";
            $messageAlert="danger";
            echo $twig->render('signup.twig',['messageRetour'=>$messageRetour,'msgAlert'=>$messageAlert]);
        }
    }
    
    //Détruit la session
    public function deconnexion(){
        
        session_destroy();
        header('Location: /clementsblog/home/index'); 
    }
    
    //Enregistre un nouveau commentaire
    public function ajouterCommentaire($idCommentaire){
        
        require_once(dirname(__DIR__, 1).'/includes/TwigConfig.php');
        require_once(dirname(__DIR__, 1).'/database.php');

        //Récupération de l'article
        $articleRepository=$entityManager->getRepository('Article');
        $article = $articleRepository->find($idCommentaire);
        
        //Création du commentaire avec date du jour et statut "Non validé"
        $comment = new Comment();
        $comment->setDate(new \DateTime(date('Y-m-d H:i:s')));
        $comment->setContenu($_POST['commentaire']);
        $comment->setValide(0);
        $comment->setArticle($article);
        $article->addCommentaire($comment);
        $entityManager->persist($comment);
        $entityManager->persist($article);
        $entityManager->flush();
        header('Location: /clementsblog/home/article/'.$article->getId().'');
    }
    
    //Affiche tous les commentaires
    public function modererCommentaires(){
        
        require_once(dirname(__DIR__, 1).'/includes/TwigConfig.php');
        require_once(dirname(__DIR__, 1).'/database.php');
        //Réinitilisation du message flash 
        $_SESSION['flashmessage']='';
        
        $commentRepository = $entityManager->getRepository('Comment');
        $commentaires = $commentRepository->findAll();
        echo $twig->render('adminCommentaires.twig',['commentaires'=>$commentaires]); 
    }
    
    //Affiche les informations d'un commentaire et permet de modifier son statut
    public function modifierCommentaire($idCommentaire){
        
        require_once(dirname(__DIR__, 1).'/includes/TwigConfig.php');
        require_once(dirname(__DIR__, 1).'/database.php');
        
        $commentRepository=$entityManager->getRepository('Comment');
        $commentaire = $commentRepository->find($idCommentaire);
        echo $twig->render('modifierCommentaire.twig',['commentaire'=>$commentaire]);
    }
    
    //Enregistre la modification du statut d'un commentaire
    public function modifierCommentaireSave($idCommentaire){
        
        require_once(dirname(__DIR__, 1).'/includes/TwigConfig.php');
        require_once(dirname(__DIR__, 1).'/database.php');
        
        $commentRepository=$entityManager->getRepository('Comment');
        $commentaire = $commentRepository->find($idCommentaire);
        $commentaire->setValide($_POST['valide']);
        $entityManager->persist($commentaire);
        $entityManager->flush();
        
        //Définition du message flash d'information
        $_SESSION['flashmessage']='Commentaire modifié avec succès';
        header('Location: /clementsblog/admin/modererCommentaires');
    }
    
    //Supprime un commentaire
    public function supprimerCommentaire($idCommentaire){
        
        //Sécurité supplémentaire pour s'assurer que l'utilisateur est bien connecté
        if($_SESSION['logged']==true){
            require_once(dirname(__DIR__, 1).'/includes/TwigConfig.php');
            require_once(dirname(__DIR__, 1).'/database.php');
            
            $commentRepository=$entityManager->getRepository('Comment');
            $commentaire = $commentRepository->find($idCommentaire);
            $entityManager->remove($commentaire);
            $entityManager->flush();

            //Définition du message d'information
            $_SESSION['flashmessage']='Commentaire supprimé avec succès';
            header('Location: /clementsblog/admin/modererCommentaires');
        }
        else{
            header('Location: /clementsblog/admin/login');
        }
    }
    
    //Affiche tous les articles
    public function gestionArticles(){
        
        require_once(dirname(__DIR__, 1).'/includes/TwigConfig.php');
        require_once(dirname(__DIR__, 1).'/database.php');
        
        $_SESSION['flashmessage']='';
        $articleRepository=$entityManager->getRepository('Article');
        $articles = $articleRepository->findAll();
        echo $twig->render('adminArticles.twig',['articles'=>$articles]);
    }
    
    //Affiche la page permettant d'ajouter un article
    public function ajouterArticle(){
        
        require_once(dirname(__DIR__, 1).'/includes/TwigConfig.php');
        require_once(dirname(__DIR__, 1).'/database.php');
        
        echo $twig->render('ajouterArticle.twig');
    }
    
    //Enregistrement d'un article
    public function ajouterArticleSave(){
        
        require_once(dirname(__DIR__, 1).'/includes/TwigConfig.php');
        require_once(dirname(__DIR__, 1).'/database.php');
        
        $userRepo=$entityManager->getRepository('User');
        $user= $userRepo->find($_SESSION['id']);
        
        $article=new Article();
        $article->setTitre($_POST['titre']);
        $article->setChapo($_POST['chapo']);
        $article->setContenu($_POST['contenu']);
        $article->setDateDerniereModif(new \DateTime(date('Y-m-d H:i:s')));
        $user->addArticle($article);
        $article->setUser($user);
        $entityManager->persist($article);
        $entityManager->persist($user);
        $entityManager->flush();
        
        $_SESSION['flashmessage']='Article ajouté avec succès';
        header('Location: /clementsblog/admin/gestionArticles');
    }
    
    //Affiche la page permettant de modifier un article
    public function modifierArticle($idArticle){
        
        require_once(dirname(__DIR__, 1).'/includes/TwigConfig.php');
        require_once(dirname(__DIR__, 1).'/database.php');
        
        $articleRepository=$entityManager->getRepository('Article');
        $article = $articleRepository->find($idArticle);
        $userRepository=$entityManager->getRepository('User');
        $users = $userRepository->findAll();
        //En paramètre l'article et la liste des utilisateurs pouvant être séléctionnés comme auteur
        echo $twig->render('modifierArticle.twig',['article'=>$article,'users'=>$users]);
    }
    
    //Enregistre la modification d'un article
    public function modifierArticleSave($idArticle){
        
        require_once(dirname(__DIR__, 1).'/includes/TwigConfig.php');
        require_once(dirname(__DIR__, 1).'/database.php');
        
        $articleRepository=$entityManager->getRepository('Article');
        $article = $articleRepository->find($idArticle);
        $userRepository=$entityManager->getRepository('User');
        $user = $userRepository->find($_POST['auteurID']);
        
        $article->setTitre($_POST['titre']);
        $article->setChapo($_POST['chapo']);
        $article->setContenu($_POST['contenu']);
        $article->setUser($user);
        $article->setDateDerniereModif(new \DateTime(date('Y-m-d H:i:s')));
        $entityManager->persist($article);
        $entityManager->flush();
        
        //Définition du message d'information
        $_SESSION['flashmessage']='Article modifié avec succès';
        header('Location: /clementsblog/admin/gestionArticles');
    }
    
    //Supprime un article
    public function supprimerArticle($idArticle){
        
        //Sécurité supplémentaire pour s'assurer que l'utilisateur est bien connecté
        if($_SESSION['logged']==true){
            $require_once(dirname(__DIR__, 1).'/includes/TwigConfig.php');
            require_once(dirname(__DIR__, 1).'/database.php');
            
            $articleRepository=$entityManager->getRepository('Article');
            $article = $articleRepository->find($idArticle);
            $entityManager->remove($article);
            $entityManager->flush();

            //Définition du message d'information
            $_SESSION['flashmessage']='Article supprimé avec succès';
            header('Location: /clementsblog/admin/gestionArticles');
        }
        else{
            header('Location: /clementsblog/admin/login');
        }
    }
    
}
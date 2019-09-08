<?php

//use ClementsBlog\app\core\Controller;



class Admin extends Controller{
   
   
    public function E404(){
        
        //Rendu du template
        $loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__, 1).'/views');
        $twig = new Twig\Environment($loader);
        $twig->addGlobal('session', $_SESSION);
        echo $twig->render('404.twig');
    }
    
    public function login($etat = null){
        
        $loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__, 1).'/views');
        $twig = new Twig\Environment($loader);
        $twig->addExtension(new Twig_Extensions_Extension_Text());
        $twig->addGlobal('session', $_SESSION);
        //Rendu du template
        echo $twig->render('login.twig',['messageRetour'=>$etat]);
   }
    public function loginUser(){
        
        $loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__, 1).'/views');
        $twig = new Twig\Environment($loader);
        $twig->addGlobal('session', $_SESSION);
        //Impossible de joindre database.php (récupération de l'entity manager
        include(dirname(__DIR__, 1).'/database.php');
        
        //Récupération des utilisateurs
        $userRepository=$entityManager->getRepository('User');
        $users = $userRepository->findAll();
        //Parcours des utilisateurs enregistrés
        $success=false;
        foreach ($users as $user){
            //Test si le mail soumis existe parmis nos utilisateurs
            if($user->getAdresseEmail()==$_POST['login']){
                //Test si le mot de passe correspond bien à l'utilisateur
                if($user->getMotDePasse()==$_POST['password']){
                    //Début de la session pour vérifier si un utilisateur est connecté
                    $twig->addGlobal('session', $_SESSION);
                    // On crée quelques variables de session dans $_SESSION
                    $_SESSION['logged'] = true;
                    $_SESSION['id'] = $user->getId();
                    $_SESSION['mail'] = $user->getAdresseEmail();
                    $_SESSION['prenom'] = $user->getPrenom();
                    $_SESSION['nom'] = $user->getNom();
                    header('Location: dashboard'); 
                }
            }
        }
        if ($success==false){
            echo $twig->render('login.twig');
        }
    }
    
    public function dashboard(){
        $loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__, 1).'/views');
        $twig = new Twig\Environment($loader);
        $twig->addGlobal('session', $_SESSION);
        
        //Réinitilisation flashmessage
        $_SESSION['flashmessage']='';
        echo $twig->render('dashboard.twig');
    }
    
    public function signup(){
        
        $loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__, 1).'/views');
        $twig = new Twig\Environment($loader);
        $twig->addExtension(new Twig_Extensions_Extension_Text());
        $twig->addGlobal('session', $_SESSION);
        //Rendu du template
        echo $twig->render('signup.twig');
    }
   
    public function registerUser(){
        
        $loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__, 1).'/views');
        $twig = new Twig\Environment($loader);
        $twig->addGlobal('session', $_SESSION);
        //Impossible de joindre database.php (récupération de l'entity manager
        include(dirname(__DIR__, 1).'/database.php');

        //Récupération des articles
        $userRepository=$entityManager->getRepository('User');
        //Si success password
        if ($_POST['password']==$_POST['passwordRepeat']){
            $user = new User;
            $user->setAdresseEmail($_POST['mail']);
            $user->setMotDePasse($_POST['password']);
            $user->setNom($_POST['nom']);
            $user->setPrenom($_POST['prenom']);
            $user->setType('utilisateur');
            $entityManager->persist($user);
            $entityManager->flush();
            
            $messageRetour ="Inscription effectuée, vous pouvez maintenant vous connecter.";
            
            //Neccessite un mix de ces trois la, une redirection vers la route 'signup' avec passage de parametre
            //return $twig->redirectToRoute('login', array('messageRetour' => $messageRetour));
            header('Location: login/signupSuccess'); 
            // echo $twig->render('login.twig',['messageRetour'=>$messageRetour]);
        }
        //Si echec password
        else{
            $messageRetour ="Veuillez saisir deux mots de passe identiques.";
            echo $twig->render('signup.twig',['messageRetour'=>$messageRetour]);
        }
    }
    
    public function deconnexion(){
        
        session_destroy();
        header('Location: /clementsblog/home/index'); 
    }
    
    public function ajouterCommentaire($idCommentaire){
        
        $loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__, 1).'/views');
        $twig = new Twig\Environment($loader);
        $twig->addGlobal('session', $_SESSION);
        //Impossible de joindre database.php (récupération de l'entity manager
        include(dirname(__DIR__, 1).'/database.php');

        //Récupération de l'article
        $articleRepository=$entityManager->getRepository('Article');
        $article = $articleRepository->find($idCommentaire);
        
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
    
    public function modererCommentaires(){
        
        $loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__, 1).'/views');
        $twig = new Twig\Environment($loader);
        $twig->addGlobal('session', $_SESSION);
        $twig->addExtension(new Twig_Extensions_Extension_Text());
        include(dirname(__DIR__, 1).'/database.php');
        //Réinitilisation flash message 
        $_SESSION['flashmessage']='';
        //Récupération des articles
        $commentRepository = $entityManager->getRepository('Comment');
        $commentaires = $commentRepository->findAll();
        
        echo $twig->render('adminCommentaires.twig',['commentaires'=>$commentaires]); 
    }
    
    public function modifierCommentaire($idCommentaire){
        
        $loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__, 1).'/views');
        $twig = new Twig\Environment($loader);
        $twig->addGlobal('session', $_SESSION);
        $twig->addExtension(new Twig_Extensions_Extension_Text());
        
        include(dirname(__DIR__, 1).'/database.php');
        //Récupération des articles
        $commentRepository=$entityManager->getRepository('Comment');
        $commentaire = $commentRepository->find($idCommentaire);
        
        //Rendu du template
        echo $twig->render('modifierCommentaire.twig',['commentaire'=>$commentaire]);
    }
    
    public function modifierCommentaireSave($idCommentaire){
        
        $loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__, 1).'/views');
        $twig = new Twig\Environment($loader);
        $twig->addGlobal('session', $_SESSION);
        include(dirname(__DIR__, 1).'/database.php');
        
        $commentRepository=$entityManager->getRepository('Comment');
        $commentaire = $commentRepository->find($idCommentaire);
        $commentaire->setValide($_POST['valide']);
        $entityManager->persist($commentaire);
        $entityManager->flush();
        
        //Définition du message d'information
        $_SESSION['flashmessage']='Commentaire modifié avec succès';
        header('Location: /clementsblog/admin/modererCommentaires');
    }
    
    public function supprimerCommentaire($idCommentaire){
        if($_SESSION['logged']==true){
            $loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__, 1).'/views');
            $twig = new Twig\Environment($loader);
            $twig->addGlobal('session', $_SESSION);
            $twig->addExtension(new Twig_Extensions_Extension_Text());

            include(dirname(__DIR__, 1).'/database.php');
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
    
    public function gestionArticles(){
        
        $loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__, 1).'/views');
        $twig = new Twig\Environment($loader);
        $twig->addGlobal('session', $_SESSION);
        $twig->addExtension(new Twig_Extensions_Extension_Text());
        include(dirname(__DIR__, 1).'/database.php');
        //Réinitilisation flash message 
        $_SESSION['flashmessage']='';
        //Récupération des articles
        $articleRepository=$entityManager->getRepository('Article');
        $articles = $articleRepository->findAll();
        //Rendu du template
        echo $twig->render('adminArticles.twig',['articles'=>$articles]);
    }
    
    public function ajouterArticle(){
        
        $loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__, 1).'/views');
        $twig = new Twig\Environment($loader);
        $twig->addGlobal('session', $_SESSION);
        include(dirname(__DIR__, 1).'/database.php');
        //Rendu du template
        echo $twig->render('ajouterArticle.twig');
    }
    public function ajouterArticleSave(){
        
        $loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__, 1).'/views');
        $twig = new Twig\Environment($loader);
        $twig->addGlobal('session', $_SESSION);
        
        include(dirname(__DIR__, 1).'/database.php');
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
        //Redirection
        header('Location: /clementsblog/admin/gestionArticles');
       
    }
    public function modifierArticle($idArticle){
        
        $loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__, 1).'/views');
        $twig = new Twig\Environment($loader);
        $twig->addGlobal('session', $_SESSION);
        $twig->addExtension(new Twig_Extensions_Extension_Text());
        
        include(dirname(__DIR__, 1).'/database.php');
        //Récupération des articles
        $articleRepository=$entityManager->getRepository('Article');
        $article = $articleRepository->find($idArticle);
        //$article->getUser
        $userRepository=$entityManager->getRepository('User');
        $users = $userRepository->findAll();
        //Rendu du template
        echo $twig->render('modifierArticle.twig',['article'=>$article,'users'=>$users]);
    }
    
    public function modifierArticleSave($idArticle){
        
        $loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__, 1).'/views');
        $twig = new Twig\Environment($loader);
        $twig->addGlobal('session', $_SESSION);
        
        include(dirname(__DIR__, 1).'/database.php');
        //Récupération des articles
        $articleRepository=$entityManager->getRepository('Article');
        $article = $articleRepository->find($idArticle);
        $userRepository=$entityManager->getRepository('User');
        $user = $userRepository->find($_POST['auteur']);
        
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
    
    public function supprimerArticle($idArticle){
        if($_SESSION['logged']==true){
            $loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__, 1).'/views');
            $twig = new Twig\Environment($loader);
            $twig->addGlobal('session', $_SESSION);
            $twig->addExtension(new Twig_Extensions_Extension_Text());

            include(dirname(__DIR__, 1).'/database.php');
            //Récupération des articles
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
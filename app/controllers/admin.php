<?php

class Admin extends Controller{
   
    public function E404(){
        
        //Rendu du template
        $loader = new Twig\Loader\FilesystemLoader(__DIR__.'../../views');
        $twig = new Twig\Environment($loader);
        echo $twig->render('404.twig');
    }
    
    public function login($etat=null){
        
        $loader = new Twig\Loader\FilesystemLoader(__DIR__.'../../views');
        $twig = new Twig\Environment($loader);
        $twig->addExtension(new Twig_Extensions_Extension_Text());
        
        //Rendu du template
        echo $twig->render('login.twig',['messageRetour'=>$etat]);
   }
    public function loginUser(){
        
        $loader = new Twig\Loader\FilesystemLoader(__DIR__.'../../views');
        $twig = new Twig\Environment($loader);
        
        //Impossible de joindre database.php (récupération de l'entity manager
        include('database2.php');
        
        //Récupération des utilisateurs
        $userRepository=$entityManager->getRepository('User');
        $users = $userRepository->findAll();
        var_dump($_POST['login']);
        var_dump($_POST['password']);
        //Parcours des utilisateurs enregistrés
        foreach ($users as $user){
            //Test si le mail soumis existe parmis nos utilisateurs
            if($user->getAdresseEmail()==$_POST['login']){
                //Test si le mot de passe correspond bien à l'utilisateur
                if($user->getMotDePasse()==$_POST['password']){
                    //Début de la session pour vérifier si un utilisateur est connecté
                    var_dump('ds la connexion');
                    $twig->addGlobal('session', $_SESSION);
                    // On crée quelques variables de session dans $_SESSION
                    $_SESSION['logged'] = true;
                    $_SESSION['id'] = $user->getId();
                    $_SESSION['mail'] = $user->getAdresseEmail();
                    $_SESSION['prenom'] = $user->getPrenom();
                    $_SESSION['nom'] = $user->getNom();
                   
                    echo $twig->render('dashboard.twig',['user'=>$user]);
                }
            }
            else{
               //echo $twig->render('login.twig');
            }
        }
    }
    
    public function dashboard(){
        $loader = new Twig\Loader\FilesystemLoader(__DIR__.'../../views');
        $twig = new Twig\Environment($loader);
        $twig->addGlobal('session', $_SESSION);
        
        //Réinitilisation flashmessage
        $_SESSION['flashmessage']='';
        echo $twig->render('dashboard.twig');
    }
    
    public function signup(){
        
        $loader = new Twig\Loader\FilesystemLoader(__DIR__.'../../views');
        $twig = new Twig\Environment($loader);
        $twig->addExtension(new Twig_Extensions_Extension_Text());
        
        //Rendu du template
        echo $twig->render('signup.twig');
    }
   
    public function registerUser(){
        
        $loader = new Twig\Loader\FilesystemLoader(__DIR__.'../../views');
        $twig = new Twig\Environment($loader);

        //Impossible de joindre database.php (récupération de l'entity manager
        include('database2.php');

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
    
    public function addComment(){
        
        $loader = new Twig\Loader\FilesystemLoader(__DIR__.'../../views');
        $twig = new Twig\Environment($loader);

        //Impossible de joindre database.php (récupération de l'entity manager
        require_once('database2.php');

        //Récupération de l'article
        $articleRepository=$entityManager->getRepository('Article');
        $article = $articleRepository->find(2);
        //var_dump($article->getCommentaires());
        
        
        $comment = new Comment();
        $comment->setDate(new \DateTime(date('Y-m-d H:i:s')));
        $comment->setContenu('Démonstration de ma réussite');
        $comment->setEtat('En attente de validation');
        $comment->setArticle($article);
        $article->addCommentaire($comment);
        $entityManager->persist($comment);
        //$entityManager->flush();

        
        //echo $twig->render('article.twig',['article'=>$article]);
    }
   
    public function gestionArticles(){
        
        $loader = new Twig\Loader\FilesystemLoader(__DIR__.'../../views');
        $twig = new Twig\Environment($loader);
        $twig->addGlobal('session', $_SESSION);
        $twig->addExtension(new Twig_Extensions_Extension_Text());
        include('database2.php');
        //Récupération des articles
        $articleRepository=$entityManager->getRepository('Article');
        $articles = $articleRepository->findAll();
        
        //Rendu du template
        echo $twig->render('adminArticles.twig',['articles'=>$articles]);
    }
    
    public function ajouterArticle(){
        
        $loader = new Twig\Loader\FilesystemLoader(__DIR__.'../../views');
        $twig = new Twig\Environment($loader);
        $twig->addGlobal('session', $_SESSION);
        
        include('database2.php');
        //Récupération des articles
       
        //Rendu du template
        echo $twig->render('ajouterArticle.twig');
    }
    public function ajouterArticleSave(){
        
        $loader = new Twig\Loader\FilesystemLoader(__DIR__.'../../views');
        $twig = new Twig\Environment($loader);
        $twig->addGlobal('session', $_SESSION);
        
        include('database2.php');
        $userRepo=$entityManager->getRepository('User');
        $user= $userRepo->find($_SESSION['id']);
        $article=new Article();
        $article->setTitre($_POST['titre']);
        $article->setChapo($_POST['chapo']);
        $article->setContenu($_POST['contenu']);
        $article->setDateDerniereModif(new \DateTime(date('Y-m-d H:i:s')));
        $user->addArticle($article);
        $article->setUser($user);
       // var_dump($user);
        $entityManager->persist($article);
        $entityManager->persist($user);
        $entityManager->flush();
        $_SESSION['flashmessage']='Article ajouté avec succès';
        //Redirection
        header('Location: /clementsblog/admin/gestionArticles');
       
    }
    public function modifierArticle($idArticle){
        
        $loader = new Twig\Loader\FilesystemLoader(__DIR__.'../../views');
        $twig = new Twig\Environment($loader);
        $twig->addGlobal('session', $_SESSION);
        $twig->addExtension(new Twig_Extensions_Extension_Text());
        
        include('database2.php');
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
        
        $loader = new Twig\Loader\FilesystemLoader(__DIR__.'../../views');
        $twig = new Twig\Environment($loader);
        $twig->addGlobal('session', $_SESSION);
        
        include('database2.php');
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
        if(!isset($_SESSION)){
            $loader = new Twig\Loader\FilesystemLoader(__DIR__.'../../views');
            $twig = new Twig\Environment($loader);
            $twig->addGlobal('session', $_SESSION);
            $twig->addExtension(new Twig_Extensions_Extension_Text());

            include('database2.php');
            //Récupération des articles
            $articleRepository=$entityManager->getRepository('Article');
            $article = $articleRepository->find($idArticle);
            $entityManager->remove($article);
            $entityManager->flush();

            //Définition du message d'information
            $_SESSION['flashmessage']='Article supprimé avec succès';

            
        }
        else{

            header('Location: /clementsblog/admin/login');
        }
    }
    
    }
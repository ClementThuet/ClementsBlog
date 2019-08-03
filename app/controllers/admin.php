<?php

class Admin extends Controller{
   
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
        //Parcours des utilisateurs enregistrés
        foreach ($users as $user){
            //Test si le mail soumis existe parmis nos utilisateurs
            if($user->getAdresseEmail()==$_POST['login']){
                //Test si le mot de passe correspond bien à l'utilisateur
                if($user->getMotDePasse()==$_POST['password']){
                    echo $twig->render('dashboard.twig',['user'=>$user]);
                }
            }
        }
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
            //$entityManager->flush();
            
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
    public function addComment($idArticle){
        
        $loader = new Twig\Loader\FilesystemLoader(__DIR__.'../../views');
        $twig = new Twig\Environment($loader);

        //Impossible de joindre database.php (récupération de l'entity manager
        include('database2.php');

        //Récupération de l'article
        $articleRepository=$entityManager->getRepository('Article');
        $article = $articleRepository->find($idArticle);
        
        //Création du nouveau commentaire
        $comment = new Comment;
        $comment->setDate(new \DateTime(date('Y-m-d H:i:s')));
        $comment->setContenu('Contenu à récupérer en POST');
        $comment->setEtat('En attente de validation');
        $comment->setArticle($article);
        
        
        // Fonctionne $article->setCommentaires($comment);
        $article->addCommentaire($comment);
        $entityManager->persist($comment);
        $entityManager->persist($article);
        //$entityManager->flush();
            
        echo $twig->render('article.twig',['article'=>$article]);
    }
   
}
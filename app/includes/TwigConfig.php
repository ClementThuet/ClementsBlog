<?php

//Paramètrage du dossier des views, ajout des extensions et de la globale session
$loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__, 1).'/views');
$twig = new Twig\Environment($loader);
$twig->addExtension(new Twig_Extensions_Extension_Text());
$twig->addGlobal('session', $_SESSION);
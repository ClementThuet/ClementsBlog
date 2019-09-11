<?php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
require_once dirname(__DIR__, 1).'/vendor/autoload.php';

$isDevMode = true;
//Définition du chemin d'accès aux entités
$config = Setup::createAnnotationMetadataConfiguration(array("./src"), $isDevMode);

//Paramètrage de la connextion à la BDD
$dbParams = array(
    'driver'   => 'pdo_mysql',
    'host'     => 'localhost',
    'charset'  => 'utf8',
    'user'     => 'root',
    'password' => '',
    'dbname'   => 'clementsblog',
);

$entityManager = EntityManager::create($dbParams, $config);
return $entityManager;
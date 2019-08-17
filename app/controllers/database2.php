<?php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;


$isDevMode = true;
$config = Setup::createAnnotationMetadataConfiguration(array("./src"), $isDevMode);

// the connection configuration
$dbParams = array(
    'driver'   => 'pdo_mysql',
    'host'     => 'localhost',
    'charset'  => 'utf8',
    'user'     => 'root',
    'password' => '',
    'dbname'   => 'clementsblog',
);

$entityManager = EntityManager::create($dbParams, $config);

<?php
use Doctrine\ORM\Tools\Console\ConsoleRunner;

require_once "app/database.php";

$entityManager = GetEntityManager();

return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($entityManager);

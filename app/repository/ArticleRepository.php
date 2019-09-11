<?php

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class ArticleRepository extends EntityRepository{
    
    //Recherche les articles (max 10) dont le titre contient $stringToSearch
    public function rechercheArticle($stringToSearch){
        
        include(dirname(__DIR__, 1).'/database.php');
        $queryBuilder = $entityManager->createQueryBuilder();
        
        $queryBuilder->select('a')
            ->from(Article::class, 'a')
            ->where('a.titre LIKE :titre')
            ->setParameter('titre','%'.addcslashes($stringToSearch, '%_').'%')
            ->setMaxResults(10);
        
        $query = $queryBuilder->getQuery();
        $results = $query->getResult();
        
        return $results;
    }
    
    //Retourne les articles compris entre $first_result et $max_results par date décroissante pour la pagination
    public function findAllByDateDESC($first_result, $max_results ){
        include(dirname(__DIR__, 1).'/database.php');
        $queryBuilder = $entityManager->createQueryBuilder();
        
        $queryBuilder->select('a')
            ->from(Article::class, 'a')
            ->orderBy('a.dateDerniereModif', 'DESC')
            ->setFirstResult($first_result)
            ->setMaxResults($max_results);
       
        $results = new Paginator($queryBuilder);
        return $results;
    }
}

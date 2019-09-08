<?php

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
class ArticleRepository extends EntityRepository{
    
    public function rechercheArticle($toSearch){
        
        
        include(dirname(__DIR__, 1).'/database.php');
        $articleRepo = $entityManager->getRepository('Article');
        $queryBuilder = $entityManager->createQueryBuilder();
        
        $queryBuilder->select('a')
            ->from(Article::class, 'a')
            ->where('a.titre LIKE :titre')
            ->setParameter('titre','%'.addcslashes($toSearch, '%_').'%')
            ->setMaxResults(10);
        
        $query = $queryBuilder->getQuery();
        $results = $query->getResult();
        
        return $results;
    }
    
    public function findAllByDateDESC($first_result, $max_results ){
        include(dirname(__DIR__, 1).'/database.php');
        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('a')
            ->from(Article::class, 'a')
            ->orderBy('a.dateDerniereModif', 'DESC')
            ->setFirstResult($first_result)
            ->setMaxResults($max_results);
        /*$query = $queryBuilder->getQuery();
        $results = $query->getResult();
         * return $results;*/
       

        $results = new Paginator($queryBuilder);
        return $results;
        
        
    }
}

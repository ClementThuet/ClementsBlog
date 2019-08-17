<?php


use Doctrine\ORM\EntityRepository;


/*class ArticleRepository extends EntityRepository{
    
    public function rechercheArticle($toSearch){
        
        
        include(__DIR__.'/../controllers/database2.php');

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
}*/

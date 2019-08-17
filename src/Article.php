<?php



use Doctrine\Common\Collections\ArrayCollection;


/**
 * @Entity @Table(name="Articles") 
 * @Entity(repositoryClass="ArticleRepository")
 */


class Article
{
    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $id;
    
    /** @Column(type="string") **/
    protected $titre;
    
    /** @Column(type="string") **/
    protected $chapo;
    
    /** @Column(type="text") **/
    protected $contenu;
    
    /** @Column(type="datetime") **/
    protected $dateDerniereModif;
    
    /**
     * Many article have one user. This is the owning side.
     * @ManyToOne(targetEntity="User", inversedBy="articles")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;
    
    /* One article has many comments. This is the inverse side.
    * @OneToMany(targetEntity="Comment", cascade={"persist", "remove"}, mappedBy="article")
    */
    protected $commentaires;
    

    public function __construct()
    {
        $this->commentaires = new Doctrine\Common\Collections\ArrayCollection;
    }
    
    
    public function getCommentaires()
    {
        return $this->commentaires;
    }
     
    public function addCommentaire(Comment $comment): self
    {
        if(!$this->commentaires->contains($comment)){
            $this->commentaires[] = $comment;
            $comment->setArticle($this);
        }
        
    }
    
    public function removeCommentaire(Comment $comment): self
    {
        if ($this->commentaires->contains($comment)) {
            $this->commentaires->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getArticle() === $this) {
                $comment->setArticle(null);
            }
        }
        return $this;
    }
    
    function getId() {
        return $this->id;
    }

    function getTitre() {
        return $this->titre;
    }

    function getChapo() {
        return $this->chapo;
    }

    function getContenu() {
        return $this->contenu;
    }

    function getDateDerniereModif() {
        return $this->dateDerniereModif;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setTitre($titre) {
        $this->titre = $titre;
    }

    function setChapo($chapo) {
        $this->chapo = $chapo;
    }

    function setContenu($contenu) {
        $this->contenu = $contenu;
    }

    function setDateDerniereModif($dateDerniereModif) {
        $this->dateDerniereModif = $dateDerniereModif;
    }
    
    function setCommentaires($commentaires) {
        $this->commentaires = $commentaires;
    }
    function getUser() {
        return $this->user;
    }

    function setUser($user) {
        $this->user = $user;
    }





}



use Doctrine\ORM\EntityRepository;


class ArticleRepository extends EntityRepository{
    
    public function rechercheArticle($toSearch){
        include('../app/controllers/database2.php');
        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('a')
            ->from(Article::class, 'a')
            ->where('a.titre LIKE :titre')
            ->setParameter('titre','%'.addcslashes($toSearch, '%_').'%')
            ->orderBy('a.dateDerniereModif', 'DESC')
            ->setMaxResults(10);
        $query = $queryBuilder->getQuery();
        $results = $query->getResult();
        
        return $results;
    }
    
    public function findAllByDateDESC(){
         include('../app/controllers/database2.php');
        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('a')
            ->from(Article::class, 'a')
            ->orderBy('a.dateDerniereModif', 'DESC');
        $query = $queryBuilder->getQuery();
        $results = $query->getResult();
        
        return $results;
    }
}

<?php

include((dirname(__DIR__, 1).'/app/repository/ArticleRepository.php'));
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * @Entity @Table(name="Articles") 
 * @Entity(repositoryClass="ArticleRepository")
 **/
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
     */
    protected $user;
    
    /** One article has many comments. This is the inverse side.
    * @OneToMany(targetEntity="Comment", mappedBy="article", cascade={"persist", "remove"})
    */
    protected $commentaires;
    

    public function __construct()
    {
         $this->commentaires = new ArrayCollection();
    }
    
    public function getCommentaires()
    {
        return $this->commentaires;
    }
     
    public function addCommentaire(Comment $commentaire)
    {
        if(!$this->commentaires->contains($commentaire)){
            $this->commentaires[] = $commentaire;
            $commentaire->setArticle($this);
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
    
    public function getId() {
        return $this->id;
    }

    public function getTitre() {
        return $this->titre;
    }

    public function getChapo() {
        return $this->chapo;
    }

    public function getContenu() {
        return $this->contenu;
    }

    public function getDateDerniereModif() {
        return $this->dateDerniereModif;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setTitre($titre) {
        $this->titre = $titre;
    }

    public function setChapo($chapo) {
        $this->chapo = $chapo;
    }

     public function setContenu($contenu) {
        $this->contenu = $contenu;
    }

     public function setDateDerniereModif($dateDerniereModif) {
        $this->dateDerniereModif = $dateDerniereModif;
    }
    
    public function setCommentaires($commentaires) {
        $this->commentaires = $commentaires;
    }
    public function getUser() {
        return $this->user;
    }

    public function setUser($user) {
        $this->user = $user;
    }
}

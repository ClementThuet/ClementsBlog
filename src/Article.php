<?php

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity @Table(name="Articles")
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
    
    /* One article has many comments. This is the inverse side.
    * @OneToMany(targetEntity="Comment", mappedBy="article")
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
        //$this->commentaires= new ArrayCollection();
       // var_dump($this);
        $this->commentaires->add($commentaire);
        $commentaire->setArticle($this);
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



}
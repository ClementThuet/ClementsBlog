<?php

/**
 * @Entity @Table(name="Comments")
 **/
class Comment
{
    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $id;
    
    /** @Column(type="datetime") **/
    protected $date;
    
    /** @Column(type="boolean") **/
    protected $valide;
    
    /** @Column(type="text") **/
    protected $contenu;
    
    /**
     * Many comments have one article. This is the owning side.
     * @ManyToOne(targetEntity="Article", inversedBy="commentaires")
    */
    protected $article;
    
    function getId() {
        return $this->id;
    }

    function getDate() {
        return $this->date;
    }

    function getValide() {
        return $this->valide;
    }

    function getContenu() {
        return $this->contenu;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setDate($date) {
        $this->date = $date;
    }

    function setValide($valide) {
        $this->valide = $valide;
    }

    function setContenu($contenu) {
        $this->contenu = $contenu;
    }
    
    function getArticle() {
        return $this->article;
    }

    function setArticle($article) {
        $this->article = $article;
    }



  
}


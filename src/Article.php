<?php


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


}
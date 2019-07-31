<?php

/**
 * @Entity @Table(name="Users")
 **/
class User
{
    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $id;
    
    /** @Column(type="string") **/
    protected $adresseEmail;
    
    /** @Column(type="string") **/
    protected $motDePasse;
    
    /** @Column(type="string") **/
    protected $nom;
    
    /** @Column(type="string") **/
    protected $prenom;
    
    /** @Column(type="string") **/
    protected $type;

    public function getId()
    {
        return $this->id;
    }

    function getAdresseEmail() {
        return $this->adresseEmail;
    }

    function getMotDePasse() {
        return $this->motDePasse;
    }

    function getNom() {
        return $this->nom;
    }

    function getPrenom() {
        return $this->prenom;
    }

    function getType() {
        return $this->type;
    }

    function setAdresseEmail($adresseEmail) {
        $this->adresseEmail = $adresseEmail;
    }

    function setMotDePasse($motDePasse) {
        $this->motDePasse = $motDePasse;
    }

    function setNom($nom) {
        $this->nom = $nom;
    }

    function setPrenom($prenom) {
        $this->prenom = $prenom;
    }

    function setType($type) {
        $this->type = $type;
    }


}

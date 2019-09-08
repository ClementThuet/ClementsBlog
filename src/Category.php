<?php

namespace ClementsBlog\Category;
/**
 * @Entity @Table(name="Categories")
 **/
class Category
{
    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $id;
    
    /** @Column(type="string") **/
    protected $intitule;
    
    function getId() {
        return $this->id;
    }

    function getIntitule() {
        return $this->intitule;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setIntitule($intitule) {
        $this->intitule = $intitule;
    }


}
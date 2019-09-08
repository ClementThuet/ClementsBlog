<?php

namespace ClementsBlog\Image;
/**
 * @Entity @Table(name="Images")
 **/
class Image
{
    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $id;
    
    /** @Column(type="string") **/
    protected $url;
    
    /** @Column(type="string") **/
    protected $alt;
    
    function getId() {
        return $this->id;
    }

    function getUrl() {
        return $this->url;
    }

    function getAlt() {
        return $this->alt;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setUrl($url) {
        $this->url = $url;
    }

    function setAlt($alt) {
        $this->alt = $alt;
    }


}
<?php

class Contact extends Controller{
    
    public function index($name=''){
        
       
        
        $this->view('contact/contact');
        echo ('home/contact');
    }
    
    public function phone(){
        
        
        $this->view('contact/phone');
        echo ('contact/phone');
    }
        
}

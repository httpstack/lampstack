<?php

class Home {
    public $strSubRoutes;
    public $arrUrlVars;
    
    public function __construct($strSubRoutes, $arrUrlVars) {
        $this->strSubRoutes = $strSubRoutes ? $strSubRoutes : null;
        $this->arrUrlVars = $arrUrlVars;
    }
    public function index($renderedDocument, $strMethod) {
        echo $strMethod . " was the method used to fetch the home page!";
    }
}
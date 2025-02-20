<?php

class Home {
    public $strSubRoutes;
    public $arrUrlVars;
    
    public function __construct($strSubRoutes, $arrUrlVars) {
        $this->strSubRoutes = $strSubRoutes ? $strSubRoutes : null;
        $this->arrUrlVars = $arrUrlVars;
    }
    public function index() {
        echo "Welcome to the home page!";
    }
}
?>
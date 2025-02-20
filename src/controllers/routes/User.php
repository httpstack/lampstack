<?php

class User {
    private $intUserID;
    private $intMessageID;

    public $strSubRoutes;
    public $arrUrlVars;

    public function __construct($strSubRoutes, $arrUrlVars) {
        $this->strSubRoutes = $strSubRoutes ? $strSubRoutes : null;
        $this->arrUrlVars   = $arrUrlVars;
        $this->intUserID       = $arrUrlVars['intUserID'];
        //var_dump($this->arrUrlVars);
        //var_dump($this->strSubRoutes);
    }

    public function index() {
        if ($this->strSubRoutes) {
            $arrSubRoutes = explode("/", trim($this->strSubRoutes, '/'));
            //var_dump($arrSubRoutes);
            $strRoute = ucfirst(array_shift($arrSubRoutes));
            //print $strRoute;
            $this->$strRoute($arrSubRoutes);
        } else {
            $this->Dash(null);
        }
    }

    private function Dash($arrSubRoutes) {
        //var_dump($arrSubRoutes);
        if($arrSubRoutes){
            $strRoute = ucfirst(array_shift($arrSubRoutes));
            $this->$strRoute($arrSubRoutes);
        }else{
            echo "Dashboard";
        }
        // Handle sub-routes and parameters here
    }

    private function Inbox($arrSubRoutes) {
        echo "enterbox";
        if($arrSubRoutes){
            $strRoute = ucfirst(array_shift($arrSubRoutes));
            print($strRoute);
            $this->$strRoute($arrSubRoutes);
        }else{
            echo "Inbox Overview";
        }
    }

    private function Message($arrSubRoutes) {
        var_dump($arrSubRoutes);
        $intMessageID = $this->arrUrlVars['intMessageID'];
        print($intMessageID);
    }
}
?>
<?php

class Services {
    public $strSubRoutes;
    public $arrUrlVars;

    public function __construct($strSubRoutes, $arrUrlVars) {
        $this->strSubRoutes = $strSubRoutes ? $strSubRoutes : null;
        $this->arrUrlVars = $arrUrlVars;
    }

    public function index() {
        if ($this->strSubRoutes) {
            $arrRoutes = explode("/", trim($this->strSubRoutes, '/'));
            $strRoute = ucfirst(array_shift($arrRoutes));
            if (method_exists($this, $strRoute)) {
                $this->$strRoute($arrRoutes);
            } else {
                echo "Route not found.";
            }
        } else {
            $this->Services();
        }
    }

    private function Services() {
        echo "Services";
    }

    private function Development($arrSubRoutes) {
        echo "Development";
        // Handle sub-routes and parameters here
    }

    private function Quote($arrSubRoutes) {
        echo "Quote ID: " . $this->arrUrlVars['quoteID'];
        // Handle sub-routes and parameters here
    }
}
?>
<?php

class Products {
    private $subRoute;
    private $urlVars;

    public function __construct($strSubRoutes, $arrUrlVars) {
        $this->subRoute = $strSubRoutes ? $strSubRoutes : null;
        $this->urlVars = $arrUrlVars;
    }

    public function index($renderedDocument, $strMethod) {
        echo "HTTP Method: " . $strMethod . "<br>";
        echo "List all products<br>";
        echo $renderedDocument;
    }

    public function show($renderedDocument, $strMethod) {
        $productId = $this->urlVars['id'];
        echo "HTTP Method: " . $strMethod . "<br>";
        echo "Show product with ID: " . $productId . "<br>";
        echo $renderedDocument;
    }

    public function create($renderedDocument, $strMethod) {
        echo "HTTP Method: " . $strMethod . "<br>";
        echo "Create a new product<br>";
        echo $renderedDocument;
    }

    public function update($renderedDocument, $strMethod) {
        $productId = $this->urlVars['id'];
        echo "HTTP Method: " . $strMethod . "<br>";
        echo "Update product with ID: " . $productId . "<br>";
        echo $renderedDocument;
    }

    public function delete($renderedDocument, $strMethod) {
        $productId = $this->urlVars['id'];
        echo "HTTP Method: " . $strMethod . "<br>";
        echo "Delete product with ID: " . $productId . "<br>";
        echo $renderedDocument;
    }
}
?>
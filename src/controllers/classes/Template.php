<?php

class Template {
    public $file;
    public $doc;
    private $arrData = [];
    private $rendered = null;
    private $arrDeps = [];
    public function __construct($templatePath) {
        $this->file = new File();
        $this->doc = new Doc($this->file);
        $this->doc->loadTemplate($templatePath);
            
        $this->arrDeps["CSS"] = array();
        $this->arrDeps["CSS"]["main"] = "/src/static/css/style.css";
        $this->arrDeps["JS"] = array();
        $this->arrDeps["JS"]["main"] = "/src/static/js/script.js";
        $this->addDepends();
        
    }

    public function addData($key, $value = null) {
        if (is_array($key)) {
            $this->arrData = array_merge($this->arrData, $key);
        } else {
            $this->arrData[$key] = $value;
        }
    }

    public function compileDataAttributes($strAttribute) {
        $nodes = $this->doc->select("//*[@$strAttribute]");
        foreach ($nodes as $node) {
            $attrValue = $node->getAttribute($strAttribute);
            if (isset($this->arrData[$attrValue])) {
                $value = $this->arrData[$attrValue];
                if ($this->isHTML($value)) {
                    $fragment = $this->doc->createDocumentFragment();
                    $fragment->appendXML($value);
                    $node->appendChild($fragment);
                } else {
                    $node->textContent = $value;
                }
            }
        }
    }

    public function compileHandleBars() {
        $content = $this->doc->saveHTML();
        foreach ($this->arrData as $key => $value) {
            $content = str_replace("{{{$key}}}", $value, $content);
        }
        $this->doc->loadHTML($content);
    }

    public function render() {
        $this->compileDataAttributes('data-template');
        $this->compileHandleBars();
        $this->rendered = $this->doc->saveHTML();
        return $this->rendered;
    }
    private function addDepends(){
        $objHead = $this->doc->select("//head");
        $arrCss = $this->arrDeps["CSS"];
        $arrJs = $this->arrDeps["JS"];
        foreach($arrCss as $k => $v){
            $objSheet = $this->doc->createElement("link");
            $objSheet->setAttribute("type", "text/css");
            $objSheet->setAttribute("href", $v);
            $objSheet->setAttribute("rel", "stylesheet");
            $objHead[0]->appendChild($objSheet);
        }
        foreach($arrJs as $k => $v){
            $objScript = $this->doc->createElement("script");
            $objScript->setAttribute("src", $v);
            $objHead[0]->appendChild($objScript);
        }
    }
    private function isHTML($string) {
        return preg_match("/<[^<]+>/", $string, $m) != 0;
    }

    public function getViewDoc($path) {
        $viewContent = $this->file->read($path);
        $viewDoc = new Doc($this->file);
        $viewDoc->loadHTML($viewContent);
        return $viewDoc;
    }

    public function insertView($strDataViewValue) {
        $nodes = $this->doc->select("//*[@data-view='$strDataViewValue']");
        foreach ($nodes as $node) {
            $viewDoc = $this->getViewDoc($this->arrData[$strDataViewValue]);
            foreach ($viewDoc->documentElement->childNodes as $child) {
                $importedNode = $this->doc->importNode($child, true);
                $node->appendChild($importedNode);
            }
        }
    }
}
?>
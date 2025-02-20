<?php

class Doc extends DOMDocument {
    private $xpath;
    private $file;

    public function __construct(File $file) {
        parent::__construct();
        $this->file = $file;
    }

    public function loadTemplate($template) {
        $content = $this->file->read($template);
        if ($content !== false) {
            $this->loadHTML($content);
            $this->xpath = new DOMXPath($this);
        }
    }

    public function querySelector($selector) {
        $xpathQuery = $this->cssToXPath($selector);
        return $this->xpath->query($xpathQuery);
    }

    private function cssToXPath($selector) {
        // Convert CSS selector to XPath
        // This is a basic implementation, you may need to extend it for more complex selectors
        $selector = preg_replace('/\s+/', ' ', trim($selector));
        $selector = str_replace(' ', '//', $selector);
        $selector = str_replace('>', '/', $selector);
        $selector = preg_replace('/\#([\w\-]+)/', '[@id="$1"]', $selector);
        $selector = preg_replace('/\.([\w\-]+)/', '[contains(concat(" ", normalize-space(@class), " "), " $1 ")]', $selector);
        return '//' . $selector;
    }

    public function select($xpathQuery) {
        return $this->xpath->query($xpathQuery);
    }

    public function insert($parent, $newNode) {
        $parent->appendChild($newNode);
    }

    public function remove($node) {
        $node->parentNode->removeChild($node);
    }

    public function create($tagName, $attributes = []) {
        $element = $this->createElement($tagName);
        foreach ($attributes as $key => $value) {
            $element->setAttribute($key, $value);
        }
        return $element;
    }

    public function setAttribute($node, $name, $value) {
        $node->setAttribute($name, $value);
    }

    public function getAttribute($node, $name) {
        return $node->getAttribute($name);
    }
}
?>
<?php
namespace Framework;
class Router{
    private array $objRoutes = [];

    public function addRoute(string $strMethod, string $strPath){
        $this->objRoutes[] = [
            "strPath" => $strPath,
            "strMethod" => $strMethod
        ];
    }

    public function add(string $strMethod, string $strPath){
        $this->addRoute($strMethod, $strPath);
    }
}

class App{
    private Router $objRouter;
    public function get(string $strPath){
        $this->objRouter->add("GET", $strPath);
    }
}
?>
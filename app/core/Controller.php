<?php
class Controller{
    public function view($name,$data = []){
        $filename="../app/views/".$name.".view.php" ;
        if(file_exists($filename)){
            extract($data);
            require $filename;
        }else{
            $filename="../app/views/404.view.php";
            require $filename;
        }
    }

    public function model($model) {
        $filename = "../app/models/" . $model . ".php";
        if (file_exists($filename)) {
            require_once $filename;
            return new $model();
        } else {
            die("Model file not found: " . $filename);
        }
    }
}
<?php
function show($data)
    {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    } //this is for debugging purposes

function esc($str){
    return htmlspecialchars($str);
}
function redirect($path): never{
    header("Location: ".ROOT."/".$path);
    die;
}
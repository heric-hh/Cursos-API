<?php

class Conexion {
    public static function conectar() {
        $link = new PDO("mysql:host=localhost;dbname=api_rest","root","");
        $link->exec("set names utf8");
        return $link;
    }
}
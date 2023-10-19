<?php

require_once "controladores/Controlador.Rutas.php";

require_once "controladores/Cursos.Controlador.php";
require_once "controladores/Clientes.Controlador.php";

require_once "modelos/Cursos.Modelo.php";
require_once "modelos/Clientes.Modelo.php";

$rutas = new ControladorRutas();

$rutas->mostrarInicio();
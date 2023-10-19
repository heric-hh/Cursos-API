<?php

$arrayRutas = explode( "/", $_SERVER[ "REQUEST_URI" ] );

// echo "<pre>";
// print_r( $arrayRutas );
// echo "<pre>";


//* PARA LA PAGINACION

if( isset( $_GET["pagina"] ) && is_numeric( $_GET["pagina"] ) ) {
    $cursos = new CursosControlador();
    $cursos->paginar( $_GET["pagina"] );
}
else {
/*=======================================
    Cuando no se hace ninguna petición a la API
========================================*/

if ( count( array_filter( $arrayRutas ) ) == 1 ) {    
    $json = array(
        "detalle" => "no encontrado"
    );
    echo json_encode($json, true);
} 
else
{
    /*=======================================
        Cuando pasamos solo un indice en el arrayRutas
    ========================================*/
    
    if ( count ( array_filter( $arrayRutas ) ) == 2 ) {
        
        //*Si "cursos" esta especificado en la URL: 
        
        if ( array_filter( $arrayRutas)[2] == "cursos" ) {

            if ( isset( $_SERVER["REQUEST_METHOD"] ) && $_SERVER["REQUEST_METHOD"] == "GET" ) {
               $cursos = new CursosControlador;
            //    $cursos->mostrarIndex();
            $cursos->mostrarInner();
            }
            else if ( isset( $_SERVER["REQUEST_METHOD"] ) && $_SERVER["REQUEST_METHOD"] == "POST" ) {
                
                /*=======================================
                CAPTURAR DATOS
                ========================================*/
                
                $datos = [
                    "titulo" => $_POST["titulo"],
                    "descripcion" => $_POST["descripcion"],
                    "instructor" => $_POST["instructor"],
                    "imagen" => $_POST["imagen"],
                    "precio" => $_POST["precio"]
                ];

                $cursos = new CursosControlador();
                $cursos->crearConPost( $datos );
            }
        }

        //*Si "registro" esta especificado en la URL: 

        if ( array_filter( $arrayRutas)[2] == "registro" ) {

            if ( isset( $_SERVER["REQUEST_METHOD"] ) && $_SERVER["REQUEST_METHOD"] == "GET" ) {
                $clientes = new ClientesControlador();
                $clientes->crearRegistro();
            } 
            else if ( isset( $_SERVER["REQUEST_METHOD"] ) && $_SERVER["REQUEST_METHOD"] == "POST" ) {

                //? CAPTURAR DATOS QUE VIENEN DESDE "POST"

                $datos = [
                    "nombre" => $_POST["nombre"],
                    "apellido" => $_POST["apellido"],
                    "email" => $_POST["email"]
                ];

                $cursos = new ClientesControlador();
                $cursos->crearRegistroConPost( $datos );
            }
        }
    } // fin - count ( array filter == 2 )
    else 
        if ( array_filter( $arrayRutas )[2] == "cursos" && is_numeric( array_filter( $arrayRutas )[3] ) ) {
            
            /*===================================
            PETICIONES GET
            ====================================*/
            
            if ( isset( $_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "GET" ) {
                $clientes = new CursosControlador();
                $clientes->mostrarId( array_filter( $arrayRutas)[3] );
            }

            /*===================================
            PETICIONES PUT
            ====================================*/

            if ( isset( $_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "PUT" ) {

                //! Capturando datos

                $datosPut = [];
                parse_str(file_get_contents('php://input'), $datosPut);

                // echo "<pre>"; print_r( $datosPut ); echo "</pre>";

                $editarCurso = new CursosControlador();
                $editarCurso->actualizarCurso( array_filter( $arrayRutas)[3], $datosPut );
            }

             /*===================================
            PETICIONES DELETE
            ====================================*/
            if ( isset( $_SERVER["REQUEST_METHOD"] ) && $_SERVER["REQUEST_METHOD"] == "DELETE" ) {
                $clientes = new CursosControlador();
                $clientes->borrarCurso( array_filter( $arrayRutas)[3] );
            }

        }
} 

}
<?php

class ClientesControlador {

    public function crearRegistro() {
        $json = array(
            "detalle" => "estas en la vista registro"
        );
        echo json_encode( $json, true );
    }

    

    public function crearRegistroConPost( $datos ) {

        /*=================================
            VALIDANDO NOMBRE
        ===================================*/
        
        if( isset( $datos["nombre"] ) && !preg_match( '/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/' , $datos["nombre"] ) ) {
            $json = [
                "detalle" => "error en el campo del nombre. Permitido solo letras"
            ];

            echo json_encode( $json, true );
        }

        /*=================================
            VALIDANDO APELLIDO
        ===================================*/

        if( isset( $datos["apellido"] ) && !preg_match( '/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/' , $datos["apellido"] ) ) {
            $json = [
                "detalle" => "error en el campo del apellido. Permitido solo letras"
            ];

            echo json_encode( $json, true );
        }

        /*=================================
            VALIDANDO EMAIL
        ===================================*/

        if( isset( $datos["email"] ) && !preg_match( '/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/' , $datos["email"] ) ) {
            $json = [
                "detalle" => "error en el campo del email. Formato incorrecto"
            ];

            echo json_encode( $json, true );
        }

        /*=================================
            VALIDANDO EMAIL REPETIDO    
        ===================================*/

        $clientes = ClientesModelo::mostrarTodosRegistros( "clientes" );

        foreach( $clientes as $key => $value ) {
            if( $value["email"] == $datos["email"] ) {
                $json = [
                    "detalle" => "el email esta repetido"
                ];
                echo json_encode( $json, true ); 
            }
        }


        /*=================================
            GENERAR CREDENCIALES DEL CLIENTE    
        ===================================*/

        $idCliente = str_replace( "$", "c", crypt( $datos["nombre"].$datos["apellido"].$datos["email"], 
        '$2a$07$afartwetsdAD52356FEDGsfhsd' ) );
        
        $llaveSecreta = str_replace( "$", "a", crypt( $datos["email"].$datos["apellido"].$datos["nombre"], 
        '$2a$07$afartwetsdAD52356FEDGsfhsd' ) );

        $datos = [
            "nombre" => $datos["nombre"],
            "apellido" => $datos["apellido"],
            "email" => $datos["email"],
            "id_cliente" => $idCliente,
            "llave_secreta" => $llaveSecreta,
            "created_at" => date( 'Y-m-d h:i:s' ),
            "updated_at" => date( 'Y-m-d h:i:s') 
        ];

        $create = ClientesModelo::crearRegistro( "clientes", $datos );

        if( $create == "ok" ) {
            $json = [
                "detalle" => "registro generado correctamente"
            ];

            echo json_encode( $json, true );
        }   
    }
}
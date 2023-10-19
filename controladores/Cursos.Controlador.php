<?php

class CursosControlador {

    public function mostrarIndex() {

         /*=================================
            VALIDAR CREDENCIALES DEL CLIENTE   
        ===================================*/

        $clientes = ClientesModelo::mostrarTodosRegistros( "clientes" );
    
        if( isset( $_SERVER["PHP_AUTH_USER"] ) && isset( $_SERVER["PHP_AUTH_PW"] ) ) {

            foreach( $clientes as $key => $value ) {
                if( base64_encode($_SERVER["PHP_AUTH_USER"] . ":" . $_SERVER["PHP_AUTH_PW"]) == base64_encode($value["id_cliente"] . ":" . $value["llave_secreta"] ) ){
                    $cursos = CursosModelo::mostrarRegistros( "cursos" );

                    $json = array(
                        "detalle" => $cursos
                    );
                    echo json_encode( $json, true );
                }
            }
        }
    }

    public function crearConPost( $datos ) {

        $clientes = ClientesModelo::mostrarTodosRegistros( "clientes" );
    
        if( isset( $_SERVER["PHP_AUTH_USER"] ) && isset( $_SERVER["PHP_AUTH_PW"] ) ) {

            foreach( $clientes as $key => $valueCliente ) {
                if( base64_encode($_SERVER["PHP_AUTH_USER"] . ":" . $_SERVER["PHP_AUTH_PW"]) == base64_encode($valueCliente["id_cliente"] . ":" . $valueCliente["llave_secreta"] ) ){
                    
                    /*=================================
                     VALIDAR DATOS DEL CLIENTE   
                    ===================================*/
                    foreach( $datos as $key => $valueDatos ) {
                        if( isset( $valueDatos ) && !preg_match( '/^[(\\)\\=\\&\\$\\;\\-\\_\\*\\"\\<\\>\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $valueDatos ) ) {

                            $json = [
                                "descripcion" => "registros con post"
                            ];

                            echo json_encode( $json, true );
                        }
                    }

                    /*=================================
                     VALIDAR QUE EL TITULO O LA DESCRIPCION NO ESTEN REPETIDOS   
                    ===================================*/

                    $cursos = CursosModelo::mostrarRegistros( "cursos" );
                    foreach( $cursos as $key => $valueCursos ) {
                        if( $valueCursos->titulo == $datos["titulo"] ) {
                            $json = [
                                "detalle" => "el titulo ya existe en la base de datos"
                            ];
                            echo json_encode( $json, true );
                        }

                        if( $valueCursos->descripcion == $datos["descripcion"] ) {
                            $json = [
                                "detalle" => "la descripcion ya existe en la base de datos"
                            ];
                            echo json_encode( $json, true );
                        }
                    } // foreach

                    /*=================================
                     LLLEVAR DATOS AL MODELO   
                    ===================================*/

                    $datos = [
                        "titulo" => $datos["titulo"],
                        "descripcion" => $datos["descripcion"],
                        "instructor" => $datos["instructor"],
                        "imagen" => $datos["imagen"],
                        "precio" => $datos["precio"],
                        "id_creador" => $valueCliente["id_cliente"],
                        "created_at" => date('Y-m-d h:i:s'),
                        "updated_at" => date('Y-m-d h:i:s')
                    ];

                    $create = CursosModelo::insertarRegistro( $datos );

                    if( $create == "ok" ) {
                        $json = [
                            "status" => 200,
                            "detalle" => "Registro guardado exitosamente"
                        ];

                        echo json_encode( $json, true);
                    }

                } // fin validacion
            } //fin recorrido de la tabla clientes
        } // fin auth user
    }

    public function mostrarId( $id ) {
        $json = array(
            "detalle" => "este es el curso con el id " . $id
        );
        echo json_encode( $json, true );
    }

    public function actualizarCurso( $id, $datosPut ) {
        //* VALIDANDO CREDENCIALES DEL CLIENTE

        $clientes = ClientesModelo::mostrarTodosRegistros( "clientes" );

        // echo "<pre>"; print_r( $clientes ); echo "</pre>";

    
        if( isset( $_SERVER["PHP_AUTH_USER"] ) && isset( $_SERVER["PHP_AUTH_PW"] ) ) {
            foreach( $clientes as $key => $valueCliente ) {

                if( base64_encode($_SERVER["PHP_AUTH_USER"] . ":" . $_SERVER["PHP_AUTH_PW"]) == base64_encode($valueCliente["id_cliente"] . ":" . $valueCliente["llave_secreta"] ) ){
                    
                    //*VALIDANDO ENTRADAS
                    foreach( $datosPut as $key => $valueDatosPut ) {
                        if( isset( $valueDatosPut ) && !preg_match( '/^[(\\)\\=\\&\\$\\;\\-\\_\\*\\"\\<\\>\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $valueDatosPut ) ) {

                            $json = [
                                "status" => "404",
                                "detalle" => "error en el campo " . $key
                            ];

                            echo json_encode( $json, true );
                        }
                    }

                    //*VALIDAR ID CREADOR   
                    $curso = CursosModelo::mostrarPorId( $id );
                    foreach( $curso as $key => $valueCurso ) {
                        if( $valueCurso->id_creador == $valueCliente["id"] ) {
                            //! Llevando datos al modelo
                            $datosPut = [
                                "id" => $id,
                                "titulo" => $datosPut["titulo"],
                                "descripcion" => $datosPut["descripcion"],
                                "instructor" => $datosPut["instructor"],
                                "imagen" => $datosPut["imagen"],
                                "precio" => $datosPut["precio"],
                                "updated_at" => date('Y-m-d h:i:s')
                            ];

                            $update = CursosModelo::actualizarCurso( $datosPut );

                            if( $update == "ok") {
                                $json = [
                                    "status" => 200,
                                    "detalle" => "Registro exitoso, su curso ha sido actualizado"
                                ];

                                echo json_encode( $json, true ); 
                            }
                            else {
                                $json = [
                                    "status" => 404,
                                    "detalle" => "No esta autorizado para modificar este curso"
                                ];

                                echo json_encode( $json, true );
                            }
                        }
                    }
                }else {
                    $json = ["detalle" => "error al encontrar al cliente en la bd"];
                    echo json_encode( $json, true);
                }
            }
        }
        else {
            $json = ["detalle" => "Sin autenticacion"];
            echo json_encode( $json, true);
        }
    }

    public function borrarCurso( $id) {
        
        //* VALIDAR CREDENCIALES DEL CLIENTE
        $clientes = ClientesModelo::mostrarTodosRegistros( "clientes" );

        if( isset( $_SERVER["PHP_AUTH_USER"] ) && isset( $_SERVER["PHP_AUTH_PW"] ) ) {
            foreach( $clientes as $key => $valueCliente ) {
                if( base64_encode( $_SERVER["PHP_AUTH_USER"] . ":" . $_SERVER["PHP_AUTH_PW"] ) 
                == base64_encode( $valueCliente["id_cliente"] . ":" . $valueCliente["llave_secreta"] ) ) {

                    //* VALIDANDO ID DEL CREADOR
                    $curso = CursosModelo::mostrarPorId( $id );
                    foreach( $curso as $key => $valueCurso ) {
                        if( $valueCurso->id_creador == $valueCliente["id"] );
                            
                        //*LLEVANDO DATOS AL MODELO
                            $delete = CursosModelo::borrarCurso( $id );

                            if ( $delete == "ok" ) {
                                $json = [
                                    "status" => 200,
                                    "detalle" => "Se ha borrado el curso" 
                                ];

                                echo json_encode( $json, true );
                            }
                    }
                }
            }
        }
    }

    public function mostrarInner() {

        /*=================================
           VALIDAR CREDENCIALES DEL CLIENTE   
       ===================================*/

       $clientes = ClientesModelo::mostrarTodosRegistros( "clientes" );
   
       if( isset( $_SERVER["PHP_AUTH_USER"] ) && isset( $_SERVER["PHP_AUTH_PW"] ) ) {

           foreach( $clientes as $key => $value ) {
               if( base64_encode($_SERVER["PHP_AUTH_USER"] . ":" . $_SERVER["PHP_AUTH_PW"]) == base64_encode($value["id_cliente"] . ":" . $value["llave_secreta"] ) ){
                   $cursos = CursosModelo::mostrarRegistrosInner( "cursos", "clientes" );

                   $json = array(
                       "detalle" => $cursos
                   );
                   echo json_encode( $json, true );
               }
           }
       }
   }

   public function paginar( $pagina ) {
        if ($pagina != null ) {
            $cantidadRegistros = 10;
            $desde = ( $pagina - 1 ) * $cantidadRegistros;
            $cursos = CursosModelo::mostrarRegistrosPaginados("cursos", "clientes", $cantidadRegistros, $desde );
            $json = [
                "detalle" => $cursos
            ];

            echo json_encode( $json, true );
        }
   }


} 
<?php

require_once "Conexion.php";

class CursosModelo {
    
    public static function mostrarRegistros( $tabla ) {
        $stmt = Conexion::conectar()->prepare( "SELECT * FROM $tabla" );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    public static function insertarRegistro( $datos ) {
        $stmt = Conexion::conectar()->prepare( "INSERT INTO cursos(titulo, descripcion, instructor, imagen, precio, id_creador, created_at, updated_at) 
        VALUES (:titulo, :descripcion, :instructor, :imagen, :precio, :id_creador, :created_at, :updated_at)");
        $stmt->bindParam( ":titulo", $datos["titulo"], PDO::PARAM_STR );
        $stmt->bindParam( ":descripcion", $datos["descripcion"], PDO::PARAM_STR );
        $stmt->bindParam( ":instructor", $datos["instructor"], PDO::PARAM_STR );
        $stmt->bindParam( ":imagen", $datos["imagen"], PDO::PARAM_STR );
        $stmt->bindParam( ":precio", $datos["precio"], PDO::PARAM_STR );
        $stmt->bindParam( ":id_creador", $datos["id_creador"], PDO::PARAM_STR );
        $stmt->bindParam( ":created_at", $datos["created_at"], PDO::PARAM_STR );
        $stmt->bindParam( ":updated_at", $datos["updated_at"], PDO::PARAM_STR );

        if( $stmt->execute() ) {
            return "ok";
        }
        else {
            print_r( Conexion::conectar()->errorInfo() );
        }
    }

    public static function mostrarPorId( $id ) {
        $stmt = Conexion::conectar()->prepare("SELECT * FROM cursos WHERE id = :id");
        $stmt->bindParam( ":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll( PDO::FETCH_CLASS );
    }

    public static function actualizarCurso( $datosPut ) {
        $stmt = Conexion::conectar()->prepare( "UPDATE cursos 
        SET titulo=:titulo, descripcion = :descripcion, instructor = :instructor, imagen = :imagen, precio = :precio, updated_at = :updated_at WHERE id = :id");
        $stmt->bindParam( ":titulo", $datosPut["titulo"], PDO::PARAM_STR);
        $stmt->bindParam( ":descripcion", $datosPut["descripcion"], PDO::PARAM_STR );
        $stmt->bindParam( ":instructor", $datosPut["instructor"], PDO::PARAM_STR );
        $stmt->bindParam( ":imagen", $datosPut["imagen"], PDO::PARAM_STR );
        $stmt->bindParam( ":precio", $datosPut["precio"], PDO::PARAM_STR );
        $stmt->bindParam( ":updated_at", $datosPut["updated_at"], PDO::PARAM_STR );
        $stmt->bindParam( ":id", $datosPut["id"], PDO::PARAM_STR );
        $stmt->execute();
        if ( $stmt->execute() ) {
            return "ok";
        } else {
            print_r( Conexion::conectar()->errorInfo() );
        }
    }


    public static function borrarCurso( $id ) {
        $stmt = Conexion::conectar()->prepare("DELETE * FROM cursos WHERE id = :id");
        $stmt->bindParam( ":id", $id, PDO::PARAM_STR );
        if( $stmt->execute() ) {
            return "ok";
        } else {
            print_r( Conexion::conectar()->errorInfo() );
        }
    }

    public static function mostrarRegistrosInner( $tabla1, $tabla2 ) {
        $stmt = Conexion::conectar()->prepare( "SELECT $tabla1.id, $tabla1.titulo, $tabla1.descripcion, $tabla1.instructor, $tabla1.imagen
        , $tabla1.precio, $tabla1.id_creador, $tabla2.primer_nombre, $tabla2.primer_apellido
        FROM $tabla1 INNER JOIN $tabla2 ON $tabla1.id_creador = $tabla2.id" );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    public static function mostrarRegistrosPaginados( $tabla1, $tabla2, $cantidadRegistros, $desde ) {
        if( $cantidadRegistros != null ) {
            $stmt = Conexion::conectar()->prepare( "SELECT $tabla1.id, $tabla1.titulo, $tabla1.descripcion, $tabla1.instructor, $tabla1.imagen
            , $tabla1.precio, $tabla1.id_creador, $tabla2.primer_nombre, $tabla2.primer_apellido
            FROM $tabla1 INNER JOIN $tabla2 ON $tabla1.id_creador = $tabla2.id LIMIT $desde, $cantidadRegistros" );
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS);
        }
        else {
            $stmt = Conexion::conectar()->prepare( "SELECT $tabla1.id, $tabla1.titulo, $tabla1.descripcion, $tabla1.instructor, $tabla1.imagen
            , $tabla1.precio, $tabla1.id_creador, $tabla2.primer_nombre, $tabla2.primer_apellido
            FROM $tabla1 INNER JOIN $tabla2 ON $tabla1.id_creador = $tabla2.id" );
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS);
        }
    }
}
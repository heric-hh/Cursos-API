<?php   
require_once "Conexion.php";

class ClientesModelo {

    public static function mostrarTodosRegistros( $nombreTabla ) {
        $stmt = Conexion::conectar()->prepare( "SELECT * FROM $nombreTabla" );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function crearRegistro( $tabla, $datos ) {
        $stmt = Conexion::conectar()->prepare( 
            "INSERT INTO clientes(primer_nombre, primer_apellido, email, id_cliente, llave_secreta, created_at, updated_at) 
            VALUES (:nombre, :apellido, :email, :id_cliente, :llave_secreta, :created_at, :updated_at)");
        $stmt->bindParam( ":nombre", $datos["nombre"], PDO::PARAM_STR );
        $stmt->bindParam( ":apellido", $datos["apellido"], PDO::PARAM_STR );
        $stmt->bindParam( ":email", $datos["email"], PDO::PARAM_STR );
        $stmt->bindParam( ":id_cliente", $datos["id_cliente"], PDO::PARAM_STR );
        $stmt->bindParam( ":llave_secreta", $datos["llave_secreta"], PDO::PARAM_STR );
        $stmt->bindParam( ":created_at", $datos["created_at"], PDO::PARAM_STR );
        $stmt->bindParam( ":updated_at", $datos["updated_at"], PDO::PARAM_STR );
        
        if( $stmt->execute() )
            return "ok";
        else 
            print_r( Conexion::conectar()->errorInfo() );
    }
}
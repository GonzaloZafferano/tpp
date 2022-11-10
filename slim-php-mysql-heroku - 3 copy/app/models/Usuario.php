<?php

class Usuario
{
    public $empleadoId;
    public $nombre;
    public $perfil;
    public $fechaIngreso;
    public $estado;
    public $clave;

    public function crearUsuario()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO usuarios (nombre, perfil, fechaIngreso, estado, clave)
         VALUES (:nombre, :perfil, :fechaIngreso, :estado, :clave)");


        $consulta->bindValue(':perfil', $this->perfil, PDO::PARAM_STR);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);

        $fechaIngreso = new DateTime($this->fechaIngreso); //12/11/2020
        $consulta->bindValue(':fechaIngreso', date_format($fechaIngreso, 'Y-m-d H:i:s'));

        $consulta->bindValue(':estado', "activo", PDO::PARAM_STR);

        $claveHash = password_hash($this->clave, PASSWORD_DEFAULT);
        $consulta->bindValue(':clave', $claveHash);

        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM usuarios WHERE estado not like '%baja%'");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Usuario');
    }

    public static function obtenerUsuario($nombre)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM usuarios WHERE nombre = :nombre AND estado not like '%baja%'");
        $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Usuario');
    }



    public static function modificarUsuario($usuario)
    {
        try{
            if(isset($usuario)){
                $objAccesoDato = AccesoDatos::obtenerInstancia();

                $consulta = $objAccesoDato->prepararConsulta("UPDATE usuarios SET nombre = :nombre, clave = :clave,
                perfil = :perfil, estado = :estado WHERE empleadoId = :empleadoId and estado not like '%baja%'");

                $consulta->bindValue(':nombre', $usuario->nombre, PDO::PARAM_STR);
                $consulta->bindValue(':estado', $usuario->estado, PDO::PARAM_STR);
                $consulta->bindValue(':perfil', $usuario->perfil, PDO::PARAM_STR);
                $consulta->bindValue(':empleadoId', $usuario->empleadoId, PDO::PARAM_INT);

                $claveHash = password_hash($usuario->clave, PASSWORD_DEFAULT);
                $consulta->bindValue(':clave', $claveHash);

                $consulta->execute();
    
                return true;
            }
        }catch(Exception $ex){
            echo "Excepcion: " . $ex->getMessage();
        }
        return false;
    }

    public static function borrarUsuario($usuarioId)
    {
        try{

            if(Usuario::obtenerUsuarioPorId($usuarioId)){

                if(isset($usuarioId) && !empty($usuarioId)){
                    $objAccesoDato = AccesoDatos::obtenerInstancia();

                    $consulta = $objAccesoDato->prepararConsulta("UPDATE usuarios SET estado = :estado WHERE empleadoId = :empleadoId and estado not like '%baja%'");
                  
                    //$fecha = new DateTime(date("d-m-Y"));
                    //$consulta->bindValue(':fechaBaja', date_format($fecha, 'Y-m-d H:i:s'));
                    $consulta->bindValue(':empleadoId', $usuarioId, PDO::PARAM_INT);
                    $consulta->bindValue(':estado', "Baja", PDO::PARAM_STR);
                    $consulta->execute();
                    
                    return true;
                }
            }

        }catch(Exception $ex){
            echo "Excepcion: " . $ex->getMessage();
        }
        return false;
    }

    public static function obtenerUsuarioPorId($usuarioId)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();

        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM usuarios WHERE empleadoId = :usuarioId and estado not like '%baja%'");
       
        $consulta->bindValue(':usuarioId', $usuarioId, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Usuario');
    }
}
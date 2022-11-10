<?php

class Producto
{
    public $productoId;
    public $nombre;
    public $precio;
    public $activo;
    public $perfil;

    public function crearProducto()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO productos (nombre, perfil, precio, activo)
         VALUES (:nombre, :perfil, :precio, :activo)");
        
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_STR);
        $consulta->bindValue(':perfil', $this->perfil, PDO::PARAM_STR);
        $consulta->bindValue(':activo', true, PDO::PARAM_BOOL);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT nombre, activo, perfil, precio, productoId FROM productos
        where activo != false");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
    }

    public static function obtenerProducto($nombre)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT nombre, perfil, precio, productoId 
        FROM productos WHERE nombre = :nombre and activo = true");
      
        $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Producto');
    }

    public static function modificarProducto($producto)
    {
        try{
            if(isset($producto)){
     
                $objAccesoDato = AccesoDatos::obtenerInstancia();
                $consulta = $objAccesoDato->prepararConsulta("UPDATE productos
                 SET precio = :precio WHERE productoId = :productoId");
               
                $consulta->bindValue(':precio', $producto->precio, PDO::PARAM_STR);
                $consulta->bindValue(':productoId', $producto->productoId, PDO::PARAM_INT);

                $consulta->execute();
    
                return true;
            }
        }catch(Exception $ex){
            echo "Excepcion: " . $ex->getMessage();
        }
        return false;
    }

    public static function borrarProducto($productoId)
    {
        try{
            if(Producto::obtenerProductoPorId($productoId)){            
                $objAccesoDato = AccesoDatos::obtenerInstancia();
                $consulta = $objAccesoDato->prepararConsulta("UPDATE productos SET activo = false WHERE productoId = :productoId and activo != false");
               
                $consulta->bindValue(':productoId', $productoId, PDO::PARAM_INT);

                $consulta->execute();
                
                return true;             
            }
        }catch(Exception $ex){
            echo "Excepcion: " . $ex->getMessage();
        }
        return false;
    }

    public static function obtenerProductoPorId($productoId)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
       
        $consulta = $objAccesoDatos->prepararConsulta("SELECT nombre, activo, perfil, precio, productoId FROM productos
         WHERE productoId = :productoId AND activo = true");

        $consulta->bindValue(':productoId', $productoId, PDO::PARAM_INT);
       
        $consulta->execute();

        return $consulta->fetchObject('Producto');
    }
}
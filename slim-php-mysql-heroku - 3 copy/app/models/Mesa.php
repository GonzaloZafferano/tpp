<?php

class Mesa
{
    public $mesaId;
    public $estado;
    public $activo;
    public $fotoUrl;

    public function crearMesa()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();

        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO mesas (estado, fotoUrl, activo)
         VALUES (:estado, :fotoUrl, :activo)");

        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':fotoUrl', $this->fotoUrl, PDO::PARAM_STR);
        $consulta->bindValue(':activo', true, PDO::PARAM_BOOL);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT mesaId, estado, fotoUrl, activo FROM mesas
        where activo != false");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    }

    public static function obtenerMesa($mesaId)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT mesaId, estado, fotoUrl, activo 
        FROM mesas WHERE mesaId = :mesaId and activo = true");
      
        $consulta->bindValue(':mesaId', $mesaId, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Mesa');
    }

    public static function modificarMesa($mesa)
    {
        try{
            if(isset($mesa)){
     
                $objAccesoDato = AccesoDatos::obtenerInstancia();

                $consulta = $objAccesoDato->prepararConsulta("UPDATE mesas
                 SET estado = :estado, fotoUrl = :fotoUrl WHERE mesaId = :mesaId");
               
                $consulta->bindValue(':estado', $mesa->estado, PDO::PARAM_STR);
                $consulta->bindValue(':fotoUrl', $mesa->fotoUrl, PDO::PARAM_STR);
                $consulta->bindValue(':mesaId', $mesa->mesaId, PDO::PARAM_STR);

                $consulta->execute();
    
                return true;
            }
        }catch(Exception $ex){
            echo "Excepcion: " . $ex->getMessage();
        }
        return false;
    }

    public static function borrarMesa($mesaId)
    {
        try{
            if(Mesa::obtenerMesaPorId($mesaId)){            
                $objAccesoDato = AccesoDatos::obtenerInstancia();

                $consulta = $objAccesoDato->prepararConsulta("UPDATE mesas
                 SET activo = false WHERE mesaId = :mesaId and activo != false");
               
                $consulta->bindValue(':mesaId', $mesaId, PDO::PARAM_INT);

                $consulta->execute();
                
                return true;             
            }
        }catch(Exception $ex){
            echo "Excepcion: " . $ex->getMessage();
        }
        return false;
    }

    public static function obtenerMesaPorId($mesaId)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
       
        $consulta = $objAccesoDatos->prepararConsulta("SELECT estado, activo, mesaId FROM mesas
         WHERE mesaId = :mesaId AND activo = true");

        $consulta->bindValue(':mesaId', $mesaId, PDO::PARAM_INT);
       
        $consulta->execute();

        return $consulta->fetchObject('Mesa');
    }
}
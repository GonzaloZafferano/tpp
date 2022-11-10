<?php

class Pedido
{
    public $pedidoId;
    public $codigoAlfanumerico;
    public $nombreCliente;
    public $mesaId;
    public $estado;
 //   public $tiempoPreparacion;
  //  public $productoId;
   // public $perfil;
   // public $empleadoId;

    public function crearPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();

        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedidos
        (codigoAlfanumerico, nombreCliente, mesaId, estado)
        VALUES (:codigoAlfanumerico, :nombreCliente, :mesaId, :estado)");
        // (codigoAlfanumerico, nombreCliente, mesaId, tiempoPreparacion, empleadoId, perfil, productoId, estado)
        // VALUES (:codigoAlfanumerico, :nombreCliente, :mesaId, :tiempoPreparacion, :empleadoId, :perfil, :productoId,  :estado)");
              

        //CODIGO ALFANUMERICO
        //echo substr(md5(time()), 0, 5);
        //$alfanumerico = substr(md5(time()), 0, 5);

        $consulta->bindValue(':codigoAlfanumerico', $this->codigoAlfanumerico, PDO::PARAM_STR);
        $consulta->bindValue(':nombreCliente', $this->nombreCliente, PDO::PARAM_STR);
        $consulta->bindValue(':mesaId', $this->mesaId, PDO::PARAM_INT);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR); //SI FUE CANCELADO O NO.

     //   $consulta->bindValue(':tiempoPreparacion', $this->tiempoPreparacion, PDO::PARAM_INT);
      //  $consulta->bindValue(':empleadoId', $this->empleadoId, PDO::PARAM_INT);
      //  $consulta->bindValue(':perfil', $this->perfil, PDO::PARAM_STR);
    //    $consulta->bindValue(':productoId', $this->productoId, PDO::PARAM_INT);

        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
  /*     
       $consulta = $objAccesoDatos->prepararConsulta("SELECT pedidoId, 
        codigoAlfanumerico, nombreCliente, mesaId, tiempoPreparacion, empleadoId, 
        perfil, productoId, estado FROM pedidos where estado != 'cancelado'");
*/
        $consulta = $objAccesoDatos->prepararConsulta("SELECT pedidoId, 
        codigoAlfanumerico, nombreCliente, mesaId,
        estado FROM pedidos where estado != 'cancelado'");

        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerPedido($codigoAlfanumerico)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT *
        FROM pedidos WHERE codigoAlfanumerico = :codigoAlfanumerico and estado != 'cancelado'");
      
        $consulta->bindValue(':codigoAlfanumerico', $codigoAlfanumerico, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Pedido');
    }

    public static function modificarPedido($pedido)
    {
        try{
            if(isset($pedido)){
     
                $objAccesoDato = AccesoDatos::obtenerInstancia();

                $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos
                 SET estado = :estado WHERE pedidoId = :pedidoId");
               
                $consulta->bindValue(':estado', $pedido->estado, PDO::PARAM_STR);
             //   $consulta->bindValue(':tiempoPreparacion', $pedido->tiempoPreparacion, PDO::PARAM_INT);
             //   $consulta->bindValue(':empleadoId', $pedido->empleadoId, PDO::PARAM_INT);
                $consulta->bindValue(':pedidoId', $pedido->pedidoId, PDO::PARAM_STR);

                $consulta->execute();
    
                return true;
            }
        }catch(Exception $ex){
            echo "Excepcion: " . $ex->getMessage();
        }
        return false;
    }

    public static function borrarPedido($pedidoId)
    {
        try{
            if(Pedido::obtenerPedidoPorId($pedidoId)){            
                $objAccesoDato = AccesoDatos::obtenerInstancia();

                $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos
                 SET estado = 'cancelado' WHERE pedidoId = :pedidoId and estado != 'cancelado'");
               
                $consulta->bindValue(':pedidoId', $pedidoId, PDO::PARAM_INT);

                $consulta->execute();
                
                return true;             
            }
        }catch(Exception $ex){
            echo "Excepcion: " . $ex->getMessage();
        }
        return false;
    }

    public static function obtenerPedidoPorId($pedidoId)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
       
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidos
         WHERE pedidoId = :pedidoId AND estado != 'cancelado'");

        $consulta->bindValue(':pedidoId', $pedidoId, PDO::PARAM_INT);
       
        $consulta->execute();

        return $consulta->fetchObject('Pedido');
    }
}
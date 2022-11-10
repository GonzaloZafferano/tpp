<?php
require_once './models/Pedido.php';
//require_once './interfaces/IApisable.php';

class PedidoController extends Pedido //implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
 
        $nombreCliente = $parametros['nombreCliente'];
        $mesaId = $parametros['mesaId'];
        $estado = $parametros['estado'];     
      //  $tiempoPreparacion = null;     
       // $productoId = $parametros['productoId'];     
       // $perfil = $parametros['perfil'];     
      //  $empleadoId = null;   


        $productos = $parametros['productos'];
       
        $productos = json_decode($productos);

        foreach($productos as $producto){
        //  echo $producto->perfil . PHP_EOL;
          echo $producto->producto . PHP_EOL;
        }


        // Creamos el Pedido
        $pedido = new Pedido();
        $pedido->codigoAlfanumerico = substr(md5(time()), 0, 5);;
        $pedido->nombreCliente = $nombreCliente;      
        $pedido->mesaId = $mesaId;      
        $pedido->estado = $estado;      
     //   $pedido->tiempoPreparacion = $tiempoPreparacion;      
      //  $pedido->productoId = $productoId;      
       // $pedido->perfil = $perfil;      
       // $pedido->empleadoId = $empleadoId;      
        
        $pedido->crearPedido();

        $payload = json_encode(array("mensaje" => "Pedido creado con exito. Su codigo alfanumerico de pedido es: " . $pedido->codigoAlfanumerico));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        // Buscamos pedido por codigo alfanumerico
        $codigoAlfanumerico = $args['codigoAlfanumerico'];
        $pedido = Pedido::obtenerPedido($codigoAlfanumerico);

        if($pedido){
          $payload = json_encode($pedido);
  
          $response->getBody()->write($payload);
          return $response
            ->withHeader('Content-Type', 'application/json');           
        }else{
          $payload = json_encode(array("mensaje" => "No se encontro el pedido de codigo: " . $codigoAlfanumerico));
    
          $response->getBody()->write($payload);
          return $response
            ->withHeader('Content-Type', 'application/json');

        }
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Pedido::obtenerTodos();
        $payload = json_encode(array("listaPedidos" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args)
    {
      //OBTENGO LOS PARAMETROS DEL BODY DE LA PETICION PUT.
      $parametros = $request->getParsedBody();

      //OBTENGO EL NOMBRE DEL USUARIO QUE VIENE EN LA URL.
      $pedidoId = $args["id"];
      
      //OBTENGO EL USUARIO A PARTIR DEL NOMBRE.
      $pedido = Pedido::obtenerPedidoPorId($pedidoId);

      if($pedido){
        $pedido->estado = $parametros['estado'];
      //  $pedido->tiempoPreparacion = $parametros['tiempoPreparacion'];
        //$pedido->empleadoId = $parametros['empleadoId'];
  
        if(Pedido::modificarPedido($pedido)){
  
          $payload = json_encode(array("mensaje" => "Pedido modificado con exito"));
    
          $response->getBody()->write($payload);
          return $response
            ->withHeader('Content-Type', 'application/json');

        }else{
          $payload = json_encode(array("mensaje" => "No se ha podido modificar el pedido. Revise que los datos enviados sean correctos."));  
          $response->getBody()->write($payload);
          return $response
            ->withHeader('Content-Type', 'application/json');
        }

      }else{
        $payload = json_encode(array("mensaje" => "No se ha encontrado al pedido de id: " . $pedidoId));  
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
      }
    }

    public function BorrarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $pedidoId = $parametros['id'];

        if(Pedido::borrarPedido($pedidoId)){

          //CONVIERTE UN OBJETO A JSON STRING.
          $payload = json_encode(array("mensaje" => "Pedido cancelado con exito"));
  
          $response->getBody()->write($payload);
          return $response
            ->withHeader('Content-Type', 'application/json');
        }else{
          //CONVIERTE UN OBJETO A JSON STRING.
          $payload = json_encode(array("mensaje" => "No se ha encontrado al pedido para borrar."));
  
          $response->getBody()->write($payload);
          return $response
            ->withHeader('Content-Type', 'application/json');
        }
    }

    /*

    public function ValidarUsuario($request, $response, $args){

      $parametros = $request->getParsedBody();
      $nombre = $parametros['usuario'];
      $clave = $parametros['clave'];

      $usuario = Usuario::obtenerUsuario($nombre);

      //CONVIERTE UN OBJETO A JSON STRING.
      $payload = json_encode(array("mensaje" => "Usuario incorrecto!"));

      if($usuario){

        //echo "AAAA" . password_verify($clave, $usuario->clave) . "bbb"; 1 true, vacio false.

        if(password_verify($clave, $usuario->clave)){
       // if(strcmp($usuario->clave, $clave) == 0){

          //CONVIERTE UN OBJETO A JSON STRING.
          $payload = json_encode(array("mensaje" => "Bienvenido!"));
        }else{
          //PASS NO EXISTE
          //CONVIERTE UN OBJETO A JSON STRING.
          $payload = json_encode(array("mensaje" => "Clave incorrecta"));
        }
      }

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }
    
    public function CalcularTiempo($request, $response, $args){

      //CONVIERTE UN OBJETO A JSON STRING.
      $payload = json_encode(array("mensaje" => "Demorando..."));

      sleep(3);
    
      //RETORNA UN JSON STRING.
      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    */
}

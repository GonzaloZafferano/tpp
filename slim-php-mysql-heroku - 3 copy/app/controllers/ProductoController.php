<?php
require_once './models/Producto.php';
//require_once './interfaces/IApisable.php';

class ProductoController extends Producto //implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $nombre = $parametros['nombre'];
        $precio = $parametros['precio'];
        $perfil = $parametros['perfil'];

        // Creamos el Producto
        $producto = new Producto();
        $producto->nombre = $nombre;
        $producto->precio = $precio;      
        $producto->perfil = $perfil;      
        
        $producto->crearProducto();

        $payload = json_encode(array("mensaje" => "Producto creado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        // Buscamos producto por nombre
        $nombre = $args['nombre'];
        $producto = Producto::obtenerProducto($nombre);

        if($producto){
          $payload = json_encode($producto);
  
          $response->getBody()->write($payload);
          return $response
            ->withHeader('Content-Type', 'application/json');           
        }else{
          $payload = json_encode(array("mensaje" => "No se encontro al producto: " . $nombre));
    
          $response->getBody()->write($payload);
          return $response
            ->withHeader('Content-Type', 'application/json');

        }
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Producto::obtenerTodos();
        $payload = json_encode(array("listaProductos" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args)
    {
      //OBTENGO LOS PARAMETROS DEL BODY DE LA PETICION PUT.
      $parametros = $request->getParsedBody();

      //OBTENGO EL NOMBRE DEL USUARIO QUE VIENE EN LA URL.
      $productoId = $args["id"];
      
      //OBTENGO EL USUARIO A PARTIR DEL NOMBRE.
      $producto = Producto::obtenerProductoPorId($productoId);

      if($producto){
        $producto->precio = $parametros['precio'];
  
        if(Producto::modificarProducto($producto)){
  
          $payload = json_encode(array("mensaje" => "Producto modificado con exito"));
    
          $response->getBody()->write($payload);
          return $response
            ->withHeader('Content-Type', 'application/json');

        }else{
          $payload = json_encode(array("mensaje" => "No se ha podido modificar el producto. Revise que los datos enviados sean correctos."));  
          $response->getBody()->write($payload);
          return $response
            ->withHeader('Content-Type', 'application/json');
        }

      }else{
        $payload = json_encode(array("mensaje" => "No se ha encontrado al producto de id: " . $productoId));  
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
      }
    }

    public function BorrarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $productoId = $parametros['id'];

        if(Producto::borrarProducto($productoId)){

          //CONVIERTE UN OBJETO A JSON STRING.
          $payload = json_encode(array("mensaje" => "Producto borrado con exito"));
  
          $response->getBody()->write($payload);
          return $response
            ->withHeader('Content-Type', 'application/json');
        }else{
          //CONVIERTE UN OBJETO A JSON STRING.
          $payload = json_encode(array("mensaje" => "No se ha encontrado al producto para borrar."));
  
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

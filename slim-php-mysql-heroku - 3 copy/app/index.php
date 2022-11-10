<?php
//Zafferano Gonzalo
// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';

//SI O SI
require_once './db/AccesoDatos.php';
require_once './controllers/UsuarioController.php';
require_once './controllers/ProductoController.php';
require_once './controllers/MesaController.php';
require_once './controllers/PedidoController.php';

// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Instantiate App
$app = AppFactory::create();





//LA BASE TIENE QUE EMPEZAR CON '/app' y despues de ahi, viene el resto 
//de la ruta, por la cual trabajamos, preguntando si tiene esto o lo otro.
//http://localhost:666/app es el LINK BASE
$app->setBasePath('/app');  //AGREGO ESTA LINEA.




// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add parse body
$app->addBodyParsingMiddleware();



//  Hacer middleware de grupo, solo para post, que permita
//  agregar un nuevo usuario, sólo si el perfil es ‘admin’.
$app->group('/usuarios', function (RouteCollectorProxy $group) {

    $group->post('/agregar[/]',  \UsuarioController::class . ':CargarUno');
    $group->get('[/]',  \UsuarioController::class . ':TraerTodos');
    $group->put('/{id}',  \UsuarioController::class . ':ModificarUno');
    $group->delete('[/]',  \UsuarioController::class . ':BorrarUno');
  
  });

  $app->group('/productos', function (RouteCollectorProxy $group) {

    $group->post('/agregar[/]',  \ProductoController::class . ':CargarUno');
    $group->get('[/]',  \ProductoController::class . ':TraerTodos');
    $group->put('/{id}',  \ProductoController::class . ':ModificarUno');
    $group->delete('[/]',  \ProductoController::class . ':BorrarUno');
  
  });

  $app->group('/mesas', function (RouteCollectorProxy $group) {

    $group->post('/agregar[/]',  \MesaController::class . ':CargarUno');
    $group->get('[/]',  \MesaController::class . ':TraerTodos');
    $group->put('/{id}',  \MesaController::class . ':ModificarUno');
    $group->delete('[/]',  \MesaController::class . ':BorrarUno');
  
  });

  $app->group('/pedidos', function (RouteCollectorProxy $group) {

    $group->post('/agregar[/]',  \PedidoController::class . ':CargarUno');
    $group->get('[/]',  \PedidoController::class . ':TraerTodos');
    $group->put('/{id}',  \PedidoController::class . ':ModificarUno');
    $group->delete('[/]',  \PedidoController::class . ':BorrarUno');
  
  });


// '/app' o '/app/' PERO GET
$app->get('[/]', function (Request $request, Response $response) {    
    $response->getBody()->write("Slim Framework 4.2 PHP");
    return $response;

});



$app->run();

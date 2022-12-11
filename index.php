<?php

session_start();

set_time_limit(-1);

setlocale( LC_ALL, 'pt', 'pt.iso-8859-1', 'pt.utf-8', 'portuguese' );

date_default_timezone_set('Europe/Lisbon');

require_once("vendor/autoload.php");


use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

$app = new \Slim\App([
        'settings' => [
            'displayErrorDetails' => true,
            'addContentLengthHeader' => false
        ]
]);

$app->add(
            function ($request, $response, $next) {
        
                $uri = $request->getUri()->getPath();
                
                $uriPieces = explode("/", $uri);

                $_MODULE = $uriPieces[0];
                $_ACTION = $uriPieces[1];
                $_ENTITY_ID = "";
                if (count($uriPieces) == 3) {
                    $_ENTITY_ID = $uriPieces[2];
                }
                
                $request->
                                withAttribute('session', $_SESSION)->
                                withAttribute('module',$_MODULE)->
                                withAttribute('action',$_ACTION)->
                                withAttribute('entity_id', $_ENTITY_ID);
              
                return $next($request, $response);
                 
});

$app->get('/', 
            function ($request, $response, $args)
            {

                echo "<h1>Hello! I'm at home page!</h1>";
});   

$app->map(['GET', 'POST'], '/{module}/{action}[/{id}[/{idsecondary}]]', 
          function ($request, $response, $args) {
			  
	        $_MODULE = $request->getAttribute('module');
                
            $_ACTION = $request->getAttribute('action');
			
            $_ENTITY_ID = $request->getAttribute('id');
	            
            if ($_SERVER['REQUEST_METHOD'] === 'POST')
            {
                echo 'Hi module '.$_MODULE.' WITH ACTION '. $_ACTION.' with POST METHOD';      
            }
            else
            {   
                echo 'Hi module '.$_MODULE.' WITH ACTION '. $_ACTION;               
            }
               
    });  
    
    $app->run();
<?php
namespace Framework;

use App\Controllers\ErrorController;
use Framework\Middleware\Authorize;

class Router
{
    protected $routes = [];
    public function regesterRoutes($method, $uri, $action , $middleware = [])
    {
         list($controller , $controllerMethod) = explode('@' , $action);
        $this->routes[] = [
            'uri' => $uri,
            'controller' => $controller,
            'method' => $method,
            'controllerMethod' => $controllerMethod ,
            'middleware'=> $middleware

        ];
    }
    /**
     * Add a route  GET to the router
     * 
     * @param string $uri
     * @param string $controller
     * @param array $middleware
     * @return void
     */
    public function get($uri, $controller , $middleware = [])
    {
       $this->regesterRoutes('GET', $uri, $controller , $middleware);
    }
     /**
     * Add a route  POST to the router
     * 
     * @param string $uri
     * @param string $controller
     * @param array $middleware
     * @return void
     */
    public function post($uri, $controller , $middleware = [])
    {
        $this->regesterRoutes('POST', $uri, $controller , $middleware);
    }
     /**
     * Add a route  PUT to the router
     * 
     * @param string $uri
     * @param string $controller
     * @param array $middleware
     * @return void
     */
    public function put($uri, $controller , $middleware = [])
    {
        $this->regesterRoutes('PUT', $uri, $controller , $middleware );
    }
     /**
     * Add a route  DELETE to the router
     * 
     * @param string $uri
     * @param string $controller
     * @param array $middleware
     * @return void
     */
    public function delete($uri, $controller , $middleware = [])
    {
        $this->regesterRoutes('DELETE', $uri, $controller , $middleware);
    }
    
    /**
     * Route the request
     * @param string $uri
     * @param string $requestMethod
     * @return void
     */
    public function route($uri)
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        //check for _method input
        if($requestMethod === 'POST' && isset($_POST['_method']))
        {
            $requestMethod = strtoupper($_POST['_method']);
        }
        foreach ($this->routes as $route) 
        {
            //split the uri in segaments
            $uriSegments = explode('/', trim($uri , '/'));
            // split the current uri into segamnets
            $routeSegments = explode('/' , trim($route['uri'] , '/'));
            
            if (count($uriSegments) === count($routeSegments) && strtoupper($route['method']) === $requestMethod) {
                $params = [];
                $match = true;
    
                // Compare each segment
                for ($i = 0; $i < count($uriSegments); $i++) {
                    // Check if the segment is a variable placeholder (e.g., {id})
                    if (!isset($routeSegments[$i]) || !isset($uriSegments[$i])) {
                        $match = false;
                        break;
                    }
    
                    if ($routeSegments[$i] !== $uriSegments[$i]) {
                        // Check if the route segment is a variable placeholder
                        if (!preg_match('/^\{(.+?)\}$/', $routeSegments[$i], $matches)) {
                            $match = false;
                            break;
                        } else {
                            // Extract the parameter value
                           
                            //$paramName = $matches[1];
                            $params[$matches[1]] = $uriSegments[$i];
                           
                        }
                    }
                }
    
                // If all segments match, execute the corresponding controller
                if ($match) {
                    foreach($route['middleware'] as $middleware)
                    {
                        (new Authorize())->handle($middleware);
                    }
                    $controller = 'App\\Controllers\\' . $route['controller'];
                    $controllerMethod = $route['controllerMethod'];
    
                    if (class_exists($controller) && method_exists($controller, $controllerMethod)) {
                        $controllerInstance = new $controller();
                        $controllerInstance->$controllerMethod($params);
                        return;
                    } else {
                        ErrorController::notFound("Controller or method not found.");
                        return;
                    }
                } 
            }
        }
    
        // If no matching route is found, show a 404 error
        ErrorController::notFound();
    }
}
<?php
class Router
{
    protected $routes = [];
    public function regesterRoutes($method, $uri, $controller)
    {
        $this->routes[] = [
            'uri' => $uri,
            'controller' => $controller,
            'method' => $method
        ];
    }
    /**
     * Add a route  GET to the router
     * 
     * @param string $uri
     * @param string $controller
     * @return void
     */
    public function get($uri, $controller)
    {
       $this->regesterRoutes('GET', $uri, $controller);
    }
     /**
     * Add a route  POST to the router
     * 
     * @param string $uri
     * @param string $controller
     * @return void
     */
    public function post($uri, $controller)
    {
        $this->regesterRoutes('POST', $uri, $controller);
    }
     /**
     * Add a route  PUT to the router
     * 
     * @param string $uri
     * @param string $controller
     * @return void
     */
    public function put($uri, $controller)
    {
        $this->regesterRoutes('PUT', $uri, $controller);
    }
     /**
     * Add a route  DELETE to the router
     * 
     * @param string $uri
     * @param string $controller
     * @return void
     */
    public function delete($uri, $controller)
    {
        $this->regesterRoutes('DELETE', $uri, $controller);
    }
    /**
     * Load error page
     * @param int $httpCode
     * @return void
     */
    public function error($httpCode = 404)
    {
        http_response_code($httpCode);
        loadView("error/{$httpCode}");
        exit;
    }
    /**
     * Route the request
     * @param string $uri
     * @param string $method
     * @return void
     */
    public function route($uri, $method)
    {
        foreach ($this->routes as $route) {
            if ($route['uri'] === $uri && $route['method'] === $method) {
                require basePath($route['controller']);
                return;
            }
        }
        $this->error();
    }
}
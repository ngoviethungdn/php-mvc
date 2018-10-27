<?php

namespace Core;

class Router
{

    /**
     * @var array
     */
    protected $routes = [];
    /**
     * @var array
     */
    protected $params = [];
    /**
     * @var mixed
     */
    protected $request;

    /**
     * @param $request
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * @param $route        Name of route
     * @param array $params Controller and Action
     */
    public function add($route, $params = [])
    {
        // Convert the route to a regular expression: escape forward slashes
        $route = preg_replace('/\//', '\\/', $route);
        // Convert variables e.g. {controller}
        $route = preg_replace('/\{([a-z]+)\}/', '(.*)', $route);
        $route = '/^' . $route . '$/i';
        $this->routes[$route] = $params;
    }

    public function dispatch()
    {
        $route = $this->match();
        if (!empty($route)) {
            $controller = "App\Controllers\\" . $this->routes[$route]['controller'];
            $action = $this->routes[$route]['action'];
            if (class_exists($controller)) {
                $controller = new $controller();
                if (method_exists($controller, $action)) {
                    echo call_user_func_array([$controller, $action], array_merge($this->params, [$this->request->data]));
                } else {
                    throw new \Exception("Method $method not found in controller " . get_class($this));
                }
            } else {
                throw new \Exception("Controller class $controller not found");
            }
        } else {
            echo '404';
        }
    }

    /**
     * @return string
     */
    public function match()
    {
        foreach ($this->routes as $route => $item) {
            if (preg_match($route, $this->request->url, $matches)) {
                unset($matches[0]);
                //
                foreach ($matches as $val) {
                    if (is_string($val)) {
                        $this->params[] = $val;
                    }
                }
                return $route;
            }
        }
        return null;
    }
}

<?php
namespace Core;

class Router {
    private $routes = [];
    private $params = [];
    
    public function add($route, $params = []) {
        // Convert route to regex
        $route = preg_replace('/\//', '\\/', $route);
        $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z0-9-]+)', $route);
        $route = '/^' . $route . '$/i';
        
        $this->routes[$route] = $params;
    }
    
    public function get($route, $params = []) {
        $params['method'] = 'GET';
        $this->add($route, $params);
    }
    
    public function post($route, $params = []) {
        $params['method'] = 'POST';
        $this->add($route, $params);
    }
    
    public function match($url) {
        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {
                foreach ($matches as $key => $match) {
                    if (is_string($key)) {
                        $params[$key] = $match;
                    }
                }
                
                $this->params = $params;
                return true;
            }
        }
        
        return false;
    }
    
    public function dispatch($url) {
        $url = $this->removeQueryStringVariables($url);
        
        if ($this->match($url)) {
            $controller = $this->params['controller'];
            $controller = $this->convertToStudlyCaps($controller);
            
            // Add "Controller" suffix if not present
            if (!str_ends_with($controller, 'Controller')) {
                $controller .= 'Controller';
            }
            
            $controller = "App\\Controllers\\{$controller}";
            
            if (class_exists($controller)) {
                $controllerObject = new $controller($this->params);
                
                $action = $this->params['action'];
                $action = $this->convertToCamelCase($action);
                
                if (is_callable([$controllerObject, $action])) {
                    $controllerObject->$action();
                } else {
                    throw new \Exception("Method {$action} not found in controller {$controller}");
                }
            } else {
                throw new \Exception("Controller class {$controller} not found");
            }
        } else {
            throw new \Exception("No route matched for URL: {$url}", 404);
        }
    }
    
    protected function convertToStudlyCaps($string) {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }
    
    protected function convertToCamelCase($string) {
        return lcfirst($this->convertToStudlyCaps($string));
    }
    
    protected function removeQueryStringVariables($url) {
        if ($url != '') {
            $parts = explode('&', $url, 2);
            if (strpos($parts[0], '=') === false) {
                $url = $parts[0];
            } else {
                $url = '';
            }
        }
        
        return $url;
    }
    
    public function getParams() {
        return $this->params;
    }
}

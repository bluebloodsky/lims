<?php

namespace FastRoute\Dispatcher;

use FastRoute\Dispatcher;

abstract class RegexBasedAbstract implements Dispatcher {
    protected $staticRouteMap;
    protected $variableRouteData;

    protected abstract function dispatchVariableRoute($routeData, $uri);

    public function dispatch($httpMethod, $uri) {
        if (isset($this->staticRouteMap[$httpMethod][$uri])) {
            $handler = $this->staticRouteMap[$httpMethod][$uri];
            return [self::FOUND, $handler, []];
        } else if ($httpMethod === 'HEAD' && isset($this->staticRouteMap['GET'][$uri])) {
            $handler = $this->staticRouteMap['GET'][$uri];
            return [self::FOUND, $handler, []];
        }

        $varRouteData = $this->variableRouteData;
        if (isset($varRouteData[$httpMethod])) {
            $respult = $this->dispatchVariableRoute($varRouteData[$httpMethod], $uri);
            if ($respult[0] === self::FOUND) {
                return $respult;
            }
        } else if ($httpMethod === 'HEAD' && isset($varRouteData['GET'])) {
            $respult = $this->dispatchVariableRoute($varRouteData['GET'], $uri);
            if ($respult[0] === self::FOUND) {
                return $respult;
            }
        }

        // Find allowed methods for this URI by matching against all other HTTP methods as well
        $allowedMethods = [];

        foreach ($this->staticRouteMap as $method => $uriMap) {
            if ($method !== $httpMethod && isset($uriMap[$uri])) {
                $allowedMethods[] = $method;
            }
        }

        foreach ($varRouteData as $method => $routeData) {
            if ($method === $httpMethod) {
                continue;
            }

            $respult = $this->dispatchVariableRoute($routeData, $uri);
            if ($respult[0] === self::FOUND) {
                $allowedMethods[] = $method;
            }
        }

        // If there are no allowed methods the route simply does not exist
        if ($allowedMethods) {
            return [self::METHOD_NOT_ALLOWED, $allowedMethods];
        } else {
            return [self::NOT_FOUND];
        }
    }
}

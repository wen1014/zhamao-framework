<?php


namespace ZM\Http;


use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use ZM\Annotation\Http\Controller;
use ZM\Annotation\Http\RequestMapping;
use ZM\Console\Console;

class RouteManager
{
    /** @var null|RouteCollection */
    public static $routes = null;

    public static function importRouteByAnnotation(RequestMapping $vss, $method, $class, $methods_annotations) {
        if (self::$routes === null) self::$routes = new RouteCollection();

        // 拿到所属方法的类上面有没有控制器的注解
        $prefix = '';
        foreach ($methods_annotations as $annotation) {
            if ($annotation instanceof Controller) {
                $prefix = $annotation->prefix;
                break;
            }
        }
        $tail = trim($vss->route, "/");
        $route_name = $prefix . ($tail === "" ? "" : "/") . $tail;
        Console::debug("添加路由：" . $route_name);
        $route = new Route($route_name, ['_class' => $class, '_method' => $method]);
        $route->setMethods($vss->request_method);

        self::$routes->add(md5($route_name), $route);
    }
}
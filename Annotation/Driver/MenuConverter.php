<?php

namespace Tigreboite\FunkylabBundle\Annotation\Driver;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Cache\PhpFileCache;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Tigreboite\FunkylabBundle\Annotation\Menu;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\ExpressionLanguage\Expression;

class MenuConverter
{
    private $reader;
    private $router;
    private $authorizationChecker;
    private $cache;

    public function __construct(Reader $reader, Router $router, AuthorizationChecker $authorizationChecker, PhpFileCache $cache)
    {
        $this->reader = $reader;
        $this->router = $router;
        $this->cache = $cache;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function getFunkylabConfiguration()
    {
        return $this->cache->fetch('tigreboite_funkylab.default_menu');
    }

    /**
     * @return array
     */
    public function onKernel()
    {
        $menu = $this->getControllersWithAnnotationModules();

        return $menu;
    }

    /**
     * All Controller with annotation @Menu.
     *
     * @return array
     */
    public function getControllersWithAnnotationModules()
    {
        $controllers = array();
        foreach ($this->router->getRouteCollection()->all() as $route) {
            $defaults = $route->getDefaults();
            if (isset($defaults['_controller'])) {
                $controllerAction = explode(':', $defaults['_controller']);
                $controller = $controllerAction[0];
                if (!isset($controllers[$controller]) && class_exists($controller)) {
                    $controllers[$controller] = $controller;
                }
            }
        }
        $controllersWithModules = array();
        foreach ($controllers as $ka => $controller) {
            $reflectionClass = new \ReflectionClass($controller);
            $methods = $reflectionClass->getMethods();
            foreach ($methods as $m) {
                $method = new \ReflectionMethod($controller, $m->getName());
                if (!$annotations = $this->reader->getMethodAnnotations($method)) {
                    continue;
                }

                foreach ($annotations as $key => $annotation) {
                    $k = $ka . '\\' . $m->getName();

                    $collector[$key] = array(
                        'name' => $k,
                    );

                    if ($annotation instanceof Route && !isset($controllersWithModules[$k]['route'])) {
                        $controllersWithModules = $this->addAnnotation($controllersWithModules, $k, 'route', $annotation->getName());
                    }
                    if ($annotation instanceof Security && !isset($controllersWithModules[$k]['security'])) {
                        $controllersWithModules = $this->addAnnotation($controllersWithModules, $k, 'security', $annotation);
                    }
                    if ($annotation instanceof Menu && !isset($controllersWithModules[$k]['menu'])) {
                        $controllersWithModules = $this->addAnnotation($controllersWithModules, $k, 'menu', $annotation);
                    }
                }

                if (isset($controllersWithModules[$k]) && !isset($controllersWithModules[$k]['menu'])) {
                    unset($controllersWithModules[$k]);
                    unset($collector[$key]);
                }
            }
        }

        $ac = $this->authorizationChecker;
        $processedMenu = array();
        foreach ($controllersWithModules as $m) {
            $granted = $ac->isGranted(new Expression($m['security']->getExpression()));
            if (!$granted) {
                continue;
            }

            $groupe = $m['menu']->getGroupe();
            if ($groupe) {
                if (!isset($processedMenu[$groupe])) {
                    $processedMenu[$groupe] = array('children' => array());
                }

                if ($m['menu']->getOrder() >= 0) {
                    $order = $m['menu']->getOrder();
                    if (!isset($processedMenu[$groupe]['children'][$order])) {
                        $processedMenu[$groupe]['children'][(integer)$order] = $m;
                    } else {
                        $processedMenu[$groupe]['children'][] = $m;
                    }
                } else {
                    $processedMenu[$groupe]['children'][] = $m;
                }

                ksort($processedMenu[$groupe]['children']);
            } else {
                $processedMenu[] = $m;
            }
        }
        ksort($processedMenu);

        // RE-SORT ELEMENTS
        foreach ($processedMenu as &$group) {
            $group['children'] = $this->sortMenu($group['children']);
        }

        return $processedMenu;
    }

    /**
     * @param array $array
     * @param $offset
     * @param $type
     * @param $annotation
     * @return array
     */
    public function addAnnotation($array = [], $offset, $type, $annotation)
    {
        if (!isset($array[$offset])) {
            $array[$offset] = array();
        }
        $array[$offset][$type] = $annotation;

        return $array;
    }

    /**
     * @param $children
     * @return array
     */
    public function sortMenu($children)
    {
        $arrayTmp = array();

        foreach ($children as $child) {
            $arrayTmp[] = $child;
        }

        return $arrayTmp;
    }
}

<?php

namespace Tigreboite\FunkylabBundle\Annotation\Driver;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Tigreboite\FunkylabBundle\Annotation\Menu;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\ExpressionLanguage\Expression;

class MenuConverter
{
    private $reader;
    private $container;
    private $user;

    public function __construct(Reader $reader,ContainerInterface $container)
    {
        $this->reader = $reader;
        $this->container = $container;
        $this->user = $this->getLoggedUser();
    }

    public function getLoggedUser()
    {
        $token = $this->container->get('security.token_storage')->getToken();
        if($token)
        {
            $user = $token->getUser();
            return $user && $user!='anon.' ? $user : false;
        }else{
            return false;
        }
    }

    public function onKernel(FilterControllerEvent $event) {
        $processedMenu = array();
        $menu = $this->getControllersWithAnnotationModules();
        return $menu;
    }


    /**
     * All Controller with annotation @Menu
     * @return array
     */
    public function getControllersWithAnnotationModules()
    {
        $allAnnotations = new AnnotationReader();

        $controllers = array();
        foreach ($this->container->get('router')->getRouteCollection()->all() as $route)
        {
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
        foreach($controllers as $ka=>$controller){

            $reflectionClass = new \ReflectionClass($controller);
            $methods = $reflectionClass->getMethods();
            foreach($methods as $m)
            {
                $method = new \ReflectionMethod($controller, $m->getName());
                if (!$annotations = $this->reader->getMethodAnnotations($method)) {
                    continue;
                }

                foreach($annotations as $annotation){
                    $k=$ka.'\\'.$m->getName();
                    if($annotation instanceof Route && !isset($controllersWithModules[$k]['route'])) {
                        $controllersWithModules=$this->addAnnotation($controllersWithModules,$k,'route',$annotation->getName());
                    }
                    if($annotation instanceof Security && !isset($controllersWithModules[$k]['security'])) {
                        $controllersWithModules=$this->addAnnotation($controllersWithModules,$k,'security',$annotation);
                    }
                    if($annotation instanceof Menu && !isset($controllersWithModules[$k]['menu'])) {
                        $controllersWithModules=$this->addAnnotation($controllersWithModules,$k,'menu',$annotation);
                    }
                }

                if(isset($controllersWithModules[$k]) && !isset($controllersWithModules[$k]['menu']))
                {
                    unset($controllersWithModules[$k]);
                }
            }
        }

        $ac = $this->container->get('security.authorization_checker');
        $processedMenu = array();
        foreach($controllersWithModules as $m)
        {
            $granted = $ac->isGranted(new Expression($m['security']->getExpression()));
            if(!$granted)
                continue;

            $groupe = $m['menu']->getGroupe();
            if($groupe)
            {
                if(!isset($processedMenu[$groupe]))
                    $processedMenu[$groupe]=array('children'=>array());

                $processedMenu[$groupe]['children'][]=$m;

            }else{
                $processedMenu[]=$m;
            }
        }
        ksort($processedMenu);

        return $processedMenu;

    }

    function addAnnotation($array,$offset,$type,$annotation)
    {
        if(!isset($array[$offset]))
        {
            $array[$offset]=array();
        }
        $array[$offset][$type]=$annotation;
        return $array;
    }
}
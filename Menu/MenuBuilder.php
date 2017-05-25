<?php

namespace Tigreboite\FunkylabBundle\Menu;

use Doctrine\Common\Annotations\Reader;
use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Tigreboite\FunkylabBundle\Annotation\Driver\MenuConverter;

class MenuBuilder
{
    private $factory;
    private $config;

    /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function createMainMenu(RequestStack $requestStack, Reader $reader, ContainerInterface $container)
    {
        $request = $requestStack->getCurrentRequest();
        $menuConverter = new MenuConverter($reader, $container);
        $this->config = $menuConverter->getFunkylabConfiguration();
        $list = $menuConverter->getControllersWithAnnotationModules();

        $menu = $this->factory->createItem('root', array(
          'childrenAttributes' => array('class' => 'sidebar-menu'),
        ));

        foreach ($list as $k => $l) {
            if (isset($l['children'])) {
                if ($l['children'][0]['route'] == 'admin_activity' && !$this->config['activity']) {
                    continue;
                }
                if ($l['children'][0]['route'] == 'admin_user'     && !$this->config['user']) {
                    continue;
                }
                if ($l['children'][0]['route'] == 'admin_blog'     && !$this->config['blog']) {
                    continue;
                }
                if ($l['children'][0]['route'] == 'admin_language' && !$this->config['language']) {
                    continue;
                }
                if ($l['children'][0]['route'] == 'admin_pays'     && !$this->config['country']) {
                    continue;
                }
                if ($l['children'][0]['route'] == 'admin_page'     && !$this->config['page']) {
                    continue;
                }

                $i = $menu->addChild($k, array(
                  'route' => $l['children'][0]['route'],
                  'extras' => array('treeview' => true, 'class_icon' => 'fa '.$l['children'][0]['menu']->getIcon()),
                  'attributes' => array('class' => 'treeview'),
                  'childrenAttributes' => array('class' => 'treeview-menu'),
                ));
                foreach ($l['children'] as $m) {
                    if ($request->get('_route') == $m['route']) {
                        $i->setCurrent(true);
                    }

                    if ($m['route'] == 'admin_activity' && !$this->config['activity']) {
                        continue;
                    }
                    if ($m['route'] == 'admin_user'     && !$this->config['user']) {
                        continue;
                    }
                    if ($m['route'] == 'admin_blog'     && !$this->config['blog']) {
                        continue;
                    }
                    if ($m['route'] == 'admin_language' && !$this->config['language']) {
                        continue;
                    }
                    if ($m['route'] == 'admin_pays'     && !$this->config['country']) {
                        continue;
                    }
                    if ($m['route'] == 'admin_page'     && !$this->config['page']) {
                        continue;
                    }

                    $i->addChild($m['menu']->getPropertyName(), array(
                      'route' => $m['route'],
                      'extras' => array('class_icon' => 'fa '.$m['menu']->getIcon()),
                    ));
                }

                if ($k == 'CMS') {
                    if ($this->config['translator']) {
                        if ($request->get('_route') == 'lexik_translation_grid') {
                            $i->setCurrent(true);
                        }

                        $i->addChild('Translator', array(
                          'route' => 'lexik_translation_grid',
                          'extras' => array('class_icon' => 'fa fa-globe'),

                        ));
                        $i->addChild('Translator download', array(
                          'route' => 'admin_translator_download',
                          'extras' => array('class_icon' => 'fa fa-download'),
                        ));
                    }
                }
            } else {
                $menu->addChild($l['menu']->getName(), array(
                  'route' => $l['route'],
                  'extras' => array('class_icon' => 'fa '.$l['menu']->getIcon()),
                ));
            }
        }

        return $menu;
    }

    public function createBreadcrumbMenu(RequestStack $requestStack, Reader $reader, ContainerInterface $container)
    {
        $request = $requestStack->getCurrentRequest();
        $menu = $this->factory->createItem('root', array(
          'childrenAttributes' => array('class' => 'breadcrumb'),
        ));

        $mainSection = 'Dashboard';
        $mainSectionIcon = 'fa fa-dashboard';
        $subSection = '';

        $route_root = explode('_', $request->get('_route'));
        $section = $route_root[1];
        $action = (!empty($route_root[2]) ? $route_root[2] : '');

        $menuConverter = new MenuConverter($reader, $container);

        $list = $menuConverter->getControllersWithAnnotationModules();

        foreach ($list as $k => $l) {
            if (isset($l['children'])) {
                foreach ($l['children'] as $m) {
                    $menu_route_root = explode('_', $m['route']);
                    $menu_section = $menu_route_root[1];

                    if ($section == $menu_section) {
                        $mainSectionIcon = 'fa '.$m['menu']->getIcon();
                        $mainSection = $m['menu']->getPropertyName();
                        $subSection = 'list';
                        $menu->addChild($k, array(
                          'route' => $m['route'],
                          'extras' => array('class_icon' => 'fa '.$l['children'][0]['menu']->getIcon()),
                        ));
                        if ($request->get('_route') == $m['route']) {
                            $menu->addChild($mainSection, array(
                            'route' => $m['route'],
                            'extras' => array('class_icon' => 'fa '.$m['menu']->getIcon()),
                          ));
                        }
                    }
                }
            } else {
                $menu_route_root = explode('_', $m['route']);
                $menu_section = $menu_route_root[1];
                if ($section == $menu_section) {
                    $mainSectionIcon = 'fa '.$m['menu']->getIcon();
                    $mainSection = $m['menu']->getPropertyName();
                    $subSection = 'list';
                    $menu->addChild($k, array(
                      'route' => $m['route'],
                      'extras' => array('class_icon' => 'fa '.$l['children'][0]['menu']->getIcon()),
                    ));
                    if ($request->get('_route') == $m['route']) {
                        $menu->addChild($mainSection, array(
                        'route' => $m['route'],
                        'extras' => array('class_icon' => 'fa '.$m['menu']->getIcon()),
                      ));
                    }
                }
            }
        }

        switch ($action) {
            case 'new':     $menu->addChild('Add');     break;
            case 'show':    $menu->addChild('Show');    break;
            case 'edit':    $menu->addChild('Edit');    break;
        }

        if (!empty($action)) {
            $subSection = $action;
        }

        $menu->setExtras(array(
          'mainSection' => $mainSection,
          'mainSectionIcon' => $mainSectionIcon,
          'subSection' => $subSection,
          'title' => $mainSection.' - '.$subSection,
        ));

        return $menu;
    }
}

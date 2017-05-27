<?php

namespace Tigreboite\FunkylabBundle\Generator;

use Symfony\Component\VarDumper\VarDumper;

class GeneratorCrud
{
    private $files = array();

    public function __construct($entity, $bundle, $type)
    {

        VarDumper::dump($entity);
        VarDumper::dump($bundle);
        VarDumper::dump($type);
        exit;


        $type = UCFirst(strtolower($type));
        $this->bundle = $bundle;
        $this->entityName = explode('\\', $entity);
        $this->entityName = end($this->entityName);

        $basePath = dirname($this->get('kernel')->getRootDir()."/../");
        $path = $basePath.'/src/'.$bundle.'/';

        $this->files = array();
        $className = 'Tigreboite\\FunkylabBundle\\Generator\\'.$type.'Formater';
        if (class_exists($className)) {
            $formater = new $className($bundle, $entity);

            //Process Controller
            $code_controller = $formater->getController($type);
            $filename = $path.'Controller/'.$this->entityName.'Controller.php';
            file_put_contents($filename, $code_controller);
            $this->addFile($filename);

            //Process EntityType
            $code_type = $formater->getFormType($type);
            if (!is_dir($path.'Form')) {
                mkdir($path.'Form');
            }
            $filename = $path.'Form/'.$this->entityName.'Type.php';
            file_put_contents($filename, $code_type);
            $this->addFile($filename);

            //Process Views
            $code_views = $formater->getViews($type);
            if (is_array($code_views)) {
                foreach ($code_views as $filename => $body) {
                    if (!is_dir($path.'Resources/views/'.$this->entityName)) {
                        mkdir($path.'Resources/views/'.$this->entityName);
                    }
                    file_put_contents($path.'Resources/views/'.$this->entityName.'/'.$filename, $body);
                    $this->addFile($path.'Resources/views/'.$this->entityName.'/'.$filename);
                }
            }
        } else {
            throw new \RuntimeException($type." format doesn't exist");
        }
    }

    public function addFile($filename)
    {
        $filename = explode($this->bundle.'/', $filename);
        $this->files[] = end($filename);
    }

    public function getFiles()
    {
        return $this->files;
    }
}

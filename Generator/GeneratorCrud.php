<?php

namespace Tigreboite\FunkylabBundle\Generator;

use Tigreboite\FunkylabBundle\Generator\Field\FieldChain;

class GeneratorCrud
{
    private $files = array();
    private $fieldChain;
    private $formaterChain;

    /**
     * @param FieldChain $fieldChain
     * @param FormaterChain $formaterChain
     */
    public function __construct(FieldChain $fieldChain, FormaterChain $formaterChain)
    {
        $this->fieldChain = $fieldChain;
        $this->formaterChain = $formaterChain;
    }

    public function generate($entity, $bundle, $type)
    {
        $type = UCFirst(strtolower($type));
        $this->bundle = $bundle;
        $this->entityName = explode('\\', $entity);
        $this->entityName = end($this->entityName);

        $basePath = getcwd();
        $path = $basePath.'/src/'.$bundle.'/';

        $this->files = array();
        $formater = $this->getFormaterChain()->getFormater($type);

        if (is_object($formater)) {
            $formater->config($bundle, $entity, $this->getFieldChain());

            //Process Controller
            $code_controller = $formater->getController($type);

            $filename = $path.'Controller/'.$this->entityName.'Controller.php';
            file_put_contents($filename, $code_controller);
            $this->addFile($filename);

            //Process EntityType
            $code_type = $formater->getFormType($type);
            if (!is_dir($path.'Form')) {
                mkdir($path.'Form');
                if (!is_dir($path.'Form/Type')) {
                    mkdir($path.'Form/Type');
                }
            }
            $filename = $path.'Form/Type/'.$this->entityName.'Type.php';
            file_put_contents($filename, $code_type);
            $this->addFile($filename);

            //Process Views
            $code_views = $formater->getViews($type);
            if (is_array($code_views)) {
                if (!is_dir($path.'Resources')) {
                    mkdir($path.'Resources');
                    if (!is_dir($path.'Resources/views')) {
                        mkdir($path.'Resources/views');
                    }
                }

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

    public function getFormaterChain()
    {
        return $this->formaterChain;
    }

    public function getFieldChain()
    {
        return $this->fieldChain;
    }
}

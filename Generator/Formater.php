<?php

namespace Tigreboite\FunkylabBundle\Generator;

use Doctrine\Common\Annotations\AnnotationReader;
use Tigreboite\FunkylabBundle\Generator\Field\Field;

abstract class Formater
{
    protected $bundle;
    protected $entity;
    protected $type;
    protected $classController;
    protected $path;

    public function __construct($bundle, $entity)
    {
        $this->path = getcwd() . "/vendor/tigreboite/funkylab-bundle/Generator";
        $this->bundle = $bundle;
        $this->entity = $entity;
        $this->entityName = explode('\\', $entity);
        $this->entityName = end($this->entityName);
        $this->annotations = $this->processAnnotation();
        $this->classController = 'Tigreboite\\FunkylabBundle\\Generator\\Controller\\' . $this->type . 'Controller';
    }

    private function processAnnotation()
    {
        $annotations = array(
            'variables' => array(),
            'variables_annotations' => array(),
            'methodes' => array(),
            'methodes_annotations' => array(),
        );

        $annotationReader = new AnnotationReader();
        $reflectionClass = new \ReflectionClass($this->entity);

        // fields Annotations
        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            $variable = new \ReflectionProperty($this->entity, $reflectionProperty->getName());
            if (!empty($variable)) {
                $annotations['variables'][] = $variable;
            }
            $variable_annotations = $annotationReader->getPropertyAnnotations($variable);
            if (!empty($variable_annotations)) {
                $annotations['variables_annotations'][] = $variable_annotations;
            }
        }

        // Methods Annotations
        foreach ($reflectionClass->getMethods() as $reflectionMethod) {
            $method = new \ReflectionMethod($this->entity, $reflectionMethod->getName());
            if (!empty($method)) {
                $annotations['methodes'][] = $method;
            }
            $method_annotations = $annotationReader->getMethodAnnotations($method);
            if (!empty($method_annotations)) {
                $annotations['methodes_annotations'][] = $method_annotations;
            }
        }

        return $annotations;
    }

    /**
     * @return mixed|string
     */
    public function getController()
    {
        $code = file_get_contents($this->path . '/Controller/' . $this->type . 'Controller.php');
        $code = $this->cleanController($code);

        return $code;
    }

    /**
     * @param $code
     *
     * @return mixed
     */
    public function cleanController($code)
    {
        $repoBundle = $this->getBundleName();
        $code = str_replace('TigreboiteFunkylabBundle', $repoBundle, $code);
        $code = str_replace('Tigreboite\FunkylabBundle\Generator', $this->bundle, $code);
        $code = str_replace('_' . strtolower($this->type), '_' . strtolower($this->entityName), $code);
        $code = str_replace('/admin/' . strtolower($this->type), '/admin/' . strtolower($this->entityName), $code);
        $code = str_replace('%entity_name%', $this->entityName, $code);
        $code = str_replace('%class_name%', strtolower($this->entityName), $code);
        $code = str_replace('%bundle_name%', $this->bundle, $code);
        $code = str_replace('%entity_path_file%', strtolower($this->entityName), $code);
        $code = str_replace('%security_roles%', '@Sensio\Bundle\FrameworkExtraBundle\Configuration\Security("has_role(\'ROLE_SUPER_ADMIN\')")', $code);
        $code = str_replace('\\Controller\\' . $this->type . 'Controller', '#tmpController#', $code);
        $code = str_replace($this->type, $this->entityName, $code);
        $code = str_replace('#tmpController#', '\\Controller\\' . $this->type . 'Controller', $code);

        $code = str_replace('Tigreboite\\FunkylabBundle\\Form', $this->bundle . '\\Form', $code);
        $code = str_replace('Tigreboite\\FunkylabBundle\\Entity', $this->bundle . '\\Entity', $code);

        return $code;
    }

    /**
     * @return array|string
     */
    public function getBundleName()
    {
        $repoBundle = explode('\\', $this->entity);
        unset($repoBundle[count($repoBundle) - 1]);
        unset($repoBundle[count($repoBundle) - 1]);
        $repoBundle = implode('', $repoBundle);

        return $repoBundle;
    }

    /**
     * @return mixed|string
     */
    public function getFormType()
    {
        $code = file_get_contents($this->path . '/Form/Type/DataType.php');
        $code = $this->cleanFormType($code);
        $usedType = [];
        $fields = '';
        foreach ($this->getFields() as $field) {
            if ($field['editable']) {
                $OBjField = new Field($field['dataType'], $field['name'], $field['varname'], array('path' => 'admin_' . strtolower($this->entityName)));
                $fields .= $OBjField->getBuilder();
                $usedType[]= $OBjField->getUseType();
            }
        }

        $code = str_replace('$builder->add(\'\');', $fields, $code);

        $code = str_replace('/*use*/', implode("\n",$usedType), $code);

        return $code;
    }

    /**
     * @param $code
     *
     * @return mixed
     */
    public function cleanFormType($code)
    {
        $code = str_replace('%entity_name%', UCFirst(strtolower($this->entityName)), $code);
        $code = str_replace('%bundle_name%', $this->bundle, $code);
        $code = str_replace('%bundle_name_entity_name%', strtolower($this->bundle . '_' . $this->entityName), $code);
        $code = str_replace('DataType', UCFirst(strtolower($this->entityName)) . 'Type', $code);
        $code = str_replace('Tigreboite\FunkylabBundle\Generator', $this->bundle, $code);

        return $code;
    }

    public function getFields()
    {
        $fields = array();
        foreach ($this->annotations['variables_annotations'] as $k => $annotations) {
            $field = array(
                'name' => '',
                'fieldname' => '',
                'type' => '',
                'editable' => true,
                'visible' => true,
                'sortable' => false,
                'dataType' => '',
                'searchable' => false,
            );

            foreach ($annotations as $annotation) {
                if (get_class($annotation) == 'Tigreboite\\FunkylabBundle\\Annotation\\Crud') {
                    $field['name'] = $annotation->getPropertyName();
                    $field['visible'] = $annotation->getVisible();
                    $field['sortable'] = $annotation->getSortable();
                    $field['editable'] = $annotation->getEditable();
                    $field['searchable'] = $annotation->getSearchable();
                    $field['dataType'] = $annotation->getDataType() == "string" ? "text" : $annotation->getDataType();
                }
                if (get_class($annotation) == 'Doctrine\\ORM\\Mapping\\Column') {
                    $field['varname'] = $this->annotations['variables'][$k]->name;
                    $field['fieldname'] = $annotation->name;
                    $field['type'] = $annotation->type;
                }

                if (get_class($annotation) == 'Doctrine\\ORM\\Mapping\\GeneratedValue') {
                    $field['generatedValue'] = true;
                }
            }

            if ($field['name'] == '') {
                $field['name'] = $field['fieldname'];
            }

            if (isset($field['generatedValue'])) {
                $field['editable'] = false;
            }

            if (!empty($field['name'])) {
                $fields[] = $field;
            }
        }

        return $fields;
    }

    public function getViews()
    {
        $EditableFields = array();
        $EditableJS = array();

        foreach ($this->getFields() as $field) {
            if ($field['editable']) {
                $OBjField = new Field($field['dataType'], $field['name'], $field['varname'], array('path' => 'admin_' . strtolower($this->entityName)));
                if ($OBjField->getHTML()) {
                    $EditableFields[] = $OBjField->getHTML();
                }
                if ($OBjField->getJS()) {
                    $EditableJS[] = $OBjField->getJS();
                }
            }
        }

        $files = array();

        $path = $this->path . '/Resources/views/' . $this->type;

        foreach (glob($path . '/*.twig') as $filename) {
            $body = '{# Generated by Tigreboite\\FunkylabBundle ' . date('Y-m-d h:i:s') . " #}\n";
            $body .= file_get_contents($filename);
            $body = str_replace('%admin_entity_path%', 'admin_' . strtolower($this->entityName), $body);
            $body = str_replace('%admin_entity_name%', UCFirst(strtolower($this->entityName)), $body);
            $body = str_replace('%editable_fields%', implode("\n", $EditableFields), $body);
            $body = str_replace('%javascript%', implode("\n", $EditableJS), $body);
            $files[basename($filename)] = $body;
        }

        return $files;
    }

    public function getType()
    {
        return $this->type;
    }
}

<?php

namespace Tigreboite\FunkylabBundle\Generator\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/*use*/

class DataType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => '%bundle_name%\Entity\%entity_name%',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return '%bundle_name_entity_name%';
    }
}

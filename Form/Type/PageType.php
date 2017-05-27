<?php

namespace Tigreboite\FunkylabBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('content')
            ->add('parent')
            ->add('user')
            ->add('language')
            ->add('image', HiddenType::class)
        ;
    }

    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Tigreboite\FunkylabBundle\Entity\Page',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'tigreboite_funkylabbundle_page';
    }
}

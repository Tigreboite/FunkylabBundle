<?php

namespace Tigreboite\FunkylabBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BlogType extends AbstractType
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
            ->add('type')
            ->add('image', 'hidden')
            ->add('status', 'choice', array(
                'choices' => array(
                    '0' => 'Dépublié',
                    '1' => 'Publié',
                    '2' => 'Archivé',
                ),
            ))
            ->add('datePublished', 'date', array(
                'required' => false,
                'widget' => 'single_text',
                'input' => 'datetime',
                'format' => 'dd/MM/yyyy',
            ))
            ->add('dateUnpublished', 'date', array(
                'required' => false,
                'widget' => 'single_text',
                'input' => 'datetime',
                'format' => 'dd/MM/yyyy',
            ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Tigreboite\FunkylabBundle\Entity\Blog',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'tigreboite_funkylabbundle_blog';
    }
}

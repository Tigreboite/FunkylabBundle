<?php

namespace Tigreboite\FunkylabBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
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
            ->add('image', HiddenType::class)
            ->add('status', ChoiceType::class, array(
                'choices' => array(
                    '0' => 'Dépublié',
                    '1' => 'Publié',
                    '2' => 'Archivé',
                ),
            ))
            ->add('datePublished', DateType::class, array(
                'required' => false,
                'widget' => 'single_text',
                'input' => 'datetime',
                'format' => 'dd/MM/yyyy',
            ))
            ->add('dateUnpublished', DateType::class, array(
                'required' => false,
                'widget' => 'single_text',
                'input' => 'datetime',
                'format' => 'dd/MM/yyyy',
            ))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function setDefaultOptions(OptionsResolver $resolver)
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

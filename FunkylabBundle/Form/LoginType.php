<?php

namespace Tigreboite\FunkylabBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Tigreboite\FunkylabBundle\Entity\User;

class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->remove('username');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
          'data_class' => 'ES\SamsungCSBundle\Entity\User',
        ));
    }

    public function getName()
    {
        return 'tigreboite_funkylabbundle_admin';
    }
}

<?php
/**
 * Code by Cyril Pereira, Julien Hay
 * Extreme-Sensio 2015.
 */

namespace AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use AppBundle\Entity\Bloc;

class BlocType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $bloc = new Bloc();

        $builder->add('title')
            ->add('body')
            ->add('layout', 'choice', array(
              'choices' => $bloc->layouts,
            ))
            ->add('onsidebar', 'checkbox', array(
                'label' => 'Greffé à la sticky ?',
                'required' => false,
            ))
            ->add('file', 'hidden')
            ->add('type', 'hidden')
            ->add('actuality', 'hidden_entity', array(
                'class' => 'AppBundle\\Entity\\Actuality',
            ))
            ->add('rh', 'hidden_entity', array(
                'class' => 'AppBundle\\Entity\\Rh',
            ))
            ->add('advice', 'hidden_entity', array(
                'class' => 'AppBundle\\Entity\\Advice',
            ))
            ->add('page', 'hidden_entity', array(
                'class' => 'AppBundle\\Entity\\Page',
            ))
            ->add('ordre', 'hidden');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
          'data_class' => 'AppBundle\Entity\Bloc',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'adminbundle_bloc';
    }
}

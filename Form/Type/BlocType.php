<?php
/**
 * Code by Cyril Pereira, Julien Hay
 * Extreme-Sensio 2015.
 */

namespace Tigreboite\FunkylabBundle\Form\Type;

use Shapecode\Bundle\HiddenEntityTypeBundle\Form\Type\HiddenEntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tigreboite\FunkylabBundle\Entity\Bloc;

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
            ->add('layout', ChoiceType::class, array(
              'choices' => $bloc->layouts,
            ))
            ->add('file', HiddenType::class)
            ->add('type', HiddenType::class)
            ->add('actuality', HiddenEntityType::class, array(
                'class' => 'Tigreboite\\FunkylabBundle\\Entity\\Actuality',
            ))
            ->add('page', HiddenEntityType::class, array(
                'class' => 'Tigreboite\\FunkylabBundle\\Entity\\Page',
            ))
            ->add('position', HiddenType::class);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Tigreboite\FunkylabBundle\Entity\Bloc',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'tigreboite_funkylabbundle_bloc';
    }
}

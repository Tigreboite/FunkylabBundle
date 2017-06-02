<?php
/**
 * Code by Cyril Pereira, Julien Hay
 * Extreme-Sensio 2015.
 */

namespace AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use AppBundle\Entity\Actuality;

class ActualityType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $actuality = new Actuality();
        $builder->add('title');
        $builder->add('summary');
        $builder->add('metaTitle');
        $builder->add('metaSummary');
        $builder->add('metaKeywords');
        $builder->add('tags');
        $builder->add('published', 'choice', [
          'choices' => [
              'Non',
              'Oui',
          ],
          'expanded' => false,
          'multiple' => false,
        ]);
        $builder->add('mea', 'choice', [
          'choices' => [
              'Non',
              'Oui',
          ],
          'expanded' => false,
          'multiple' => false,
        ]);
        $builder->add('dateStart', 'date', array(
          'required' => false,
          'widget' => 'single_text',
          'input' => 'datetime',
          'format' => 'dd/MM/yyyy', )
        );
        $builder->add('dateEnd', 'date', array(
          'required' => false,
          'widget' => 'single_text',
          'input' => 'datetime',
          'format' => 'dd/MM/yyyy', )
        );
        $builder->add('cta');
        $builder->add('image', 'hidden');

        $builder->add('category', 'choice', [
          'choices' => $actuality->categories,
          'expanded' => false,
          'multiple' => false,
        ]);

        //Manager
        $builder->add('managerImage', 'hidden');
        $builder->add('managerName');
        $builder->add('managerPhone');
        $builder->add('managerEmail');
        $builder->add('managerPosition');
        $builder->add('managerCta');
        $builder->add('managerForm', 'choice', [
            'choices' => $actuality->managerForms,
            'expanded' => false,
            'multiple' => false,
        ]);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
          'data_class' => 'AppBundle\Entity\Actuality',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'adminbundle_actuality';
    }
}

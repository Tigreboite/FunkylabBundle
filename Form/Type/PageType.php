<?php
/**
 * Code by Cyril Pereira, Julien Hay
 * Extreme-Sensio 2015.
 */

namespace AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title');
        $builder->add('summary');
        $builder->add('image', 'hidden');
        $builder->add('metaTitle');
        $builder->add('metaSummary');
        $builder->add('metaKeywords');
        $builder->add('slug', 'choice', [
            'choices' => [
                'homepage' => 'Home',
                'mosaic' => 'Mosaic',
                'search' => 'Search',
                'actuality' => 'Landing-actualités',
                'appbundle_rh_index' => 'Landing-RH',
                'simulateur' => 'Simulateur',
                'appbundle_account_index' => 'Compte utilisateur',
                'mentions' => 'Mentions',
                'conditions' => 'Conditions',
                'presse' => 'Presse',
                'nous_connaitre' => 'Nous connaitre',
                'devenez_partenaire' => 'Devenez partenaire',
                'charte' => 'Charte d’utilisation des données personnelles',
          ],
        ]);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
          'data_class' => 'AppBundle\Entity\Page',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'adminbundle_page';
    }
}

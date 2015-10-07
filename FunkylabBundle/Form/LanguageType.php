<?php

namespace Tigreboite\FunkylabBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityManager;

class LanguageType extends AbstractType
{

    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choice_countries = array();
        if($this->em)
        {
            $pays = $this->em->getRepository('TigreboiteFunkylabBundle:Pays')->findAll();
            foreach($pays as $p)
            {
                $choice_countries[strtolower($p->getCode())]=$p->getName()." (".$p->getCode().")";
            }
        }

        $builder
            ->add('name')
            ->add('code')
            ->add('flag','choice', array(
              'choices' =>$choice_countries,
              )
            )
            ->add('pdf_payment','hidden')
            ->add('image','hidden')
            ->add('isenable')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Tigreboite\FunkylabBundle\Entity\Language'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'tigreboite_funkylabbundle_language';
    }
}

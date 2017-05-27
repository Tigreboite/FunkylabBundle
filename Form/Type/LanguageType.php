<?php

namespace Tigreboite\FunkylabBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\VarDumper\VarDumper;


class LanguageType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choice_countries = array();
        if ($options['entityManager']) {
            $pays = $options['entityManager']->getRepository('TigreboiteFunkylabBundle:Pays')->findAll();
            foreach ($pays as $p) {
                $choice_countries[strtolower($p->getCode())] = $p->getName() . ' (' . $p->getCode() . ')';
            }
        }

        $builder
            ->add('name', TextType::class)
            ->add('code', TextType::class)
            ->add('flag', ChoiceType::class, array(
                    'choices' => $choice_countries,
                )
            )
            ->add('image', HiddenType::class)
            ->add('isenable', CheckboxType::class);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Tigreboite\FunkylabBundle\Entity\Language',
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Tigreboite\FunkylabBundle\Entity\Language',
            'entityManager' => null,
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'tigreboite_funkylabbundle_language';
    }
}

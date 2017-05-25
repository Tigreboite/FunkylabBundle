<?php

namespace Tigreboite\FunkylabBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Tigreboite\FunkylabBundle\Entity\User;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', null, array(
                'attr' => array(
                    'class' => 'form-control',
                ),
                'constraints' => array(
                    new \Symfony\Component\Validator\Constraints\Email(),
                    new \Symfony\Component\Validator\Constraints\NotBlank(),
                ),
            ))
            ->add('roles', 'choice', array(
                'choices' => array(
                    User::ROLE_BRAND => User::ROLE_BRAND,
                    User::ROLE_MODERATOR => User::ROLE_MODERATOR,
                    User::ROLE_SUPER_ADMIN => User::ROLE_SUPER_ADMIN,
                    User::ROLE_ADMIN => User::ROLE_ADMIN,
                ),
                'multiple' => true,
                'expanded' => true,
                'required' => false,
                'empty_value' => false,
                'attr' => array(
                    'class' => 'form-control',
                ),
            ))
            ->add('dob', 'date', array(
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'attr' => array('class' => 'date form-control'),
                'label' => 'Date of birth',
            ))
            ->add('civility', 'choice', array(
                'choices' => array(
                    'Mlle' => 'Mlle',
                    'Mme' => 'Mme',
                    'Mr' => 'Mr',
                ),
                'multiple' => false,
                'expanded' => false,
                'required' => false,
                'empty_value' => false,
                'attr' => array(
                    'class' => 'form-control',
                ),
            ))
            ->add('lastname', null, array(
                'attr' => array(
                    'class' => 'form-control',
                ),
            ))
            ->add('firstname', null, array(
                'attr' => array(
                    'class' => 'form-control',
                ),
            ))
            ->add('plainpassword', 'password', array(
                'required' => false,
                'attr' => array(
                    'class' => 'form-control',
                ),
                'label' => 'Password',
            ))
            ->add('adresse', null, array(
                'attr' => array(
                    'class' => 'form-control',
                ),
            ))
            ->add('adresse2', null, array(
                'attr' => array(
                    'class' => 'form-control',
                ),
            ))
            ->add('zipcode', null, array(
                'attr' => array(
                    'class' => 'form-control',
                ),
            ))
            ->add('city', null, array(
                'attr' => array(
                    'class' => 'form-control',
                ),
            ))
            ->add('country', null, array(
                'attr' => array(
                    'class' => 'form-control',
                ),
            ))
            ->add('language', null, array(
                'query_builder' => function (EntityRepository $er) {
                    return $this->getOrderLanguageList($er);
                },
                'attr' => array(
                    'class' => 'form-control',
                ),
            ))
            ->add('image', 'hidden');
    }

    private function getOrderLanguageList($er)
    {
        $languages = $er->createQueryBuilder('u')
            ->where('u.isenable = 1')
            ->orderBy('u.name', 'ASC');

        return $languages;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Tigreboite\FunkylabBundle\Entity\User',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'tigreboite_funkylabbundle_user';
    }
}

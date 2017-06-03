<?php

namespace Tigreboite\FunkylabBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
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
            ->add('roles', ChoiceType::class, array(
                'choices' => array(
                    User::ROLE_BRAND => User::ROLE_BRAND,
                    User::ROLE_MODERATOR => User::ROLE_MODERATOR,
                    User::ROLE_SUPER_ADMIN => User::ROLE_SUPER_ADMIN,
                    User::ROLE_ADMIN => User::ROLE_ADMIN,
                ),
                'multiple' => true,
                'expanded' => true,
                'required' => false,
                'empty_data' => false,
                'attr' => array(
                    'class' => 'form-control',
                ),
            ))
            ->add('dob', DateType::class, array(
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'attr' => array('class' => 'date form-control'),
                'label' => 'Date of birth',
            ))
            ->add('civility', ChoiceType::class, array(
                'choices' => array(
                    'Mlle' => 'Mlle',
                    'Mme' => 'Mme',
                    'Mr' => 'Mr',
                ),
                'multiple' => false,
                'expanded' => false,
                'required' => false,
                'empty_data' => false,
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
            ->add('plainpassword', PasswordType::class, array(
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
            ->add('image', HiddenType::class);
    }



    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'entityManager' => null,
            'data_class' => 'Tigreboite\FunkylabBundle\Entity\User',
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'tigreboite_funkylabbundle_user';
    }
}

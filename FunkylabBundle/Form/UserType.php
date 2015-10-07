<?php

namespace Tigreboite\FunkylabBundle\Form;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Tigreboite\FunkylabBundle\Entity\User;

class UserType extends AbstractType
{
    private $front;
    private $container;

    public function __construct(ContainerInterface $container, $front = false)
    {
        $this->container = $container;
        $this->front = $front;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder
          ->add('email', null, array(
            'attr' => array(
              'class' => 'form-control'
            ),
            'constraints' => array(
              new \Symfony\Component\Validator\Constraints\Email(),
              new \Symfony\Component\Validator\Constraints\NotBlank()
            )
          ))
          ->add('lastname', null, array(
            'attr' => array(
              'class' => 'form-control'
            )
          ))
          ->add('firstname', null, array(
            'attr' => array(
              'class' => 'form-control'
            )
          ))
          ->add('plainpassword',"password", array(
            'required' => false,
            'attr' => array(
              'class' => 'form-control'
            ),
            'label' => 'Password'
          ))
          ->add('adresse', null, array(
            'attr' => array(
              'class' => 'form-control'
            )
          ))
          ->add('adresse2', null, array(
            'attr' => array(
              'class' => 'form-control'
            )
          ))
          ->add('zipcode', null, array(
            'attr' => array(
              'class' => 'form-control'
            )
          ))
          ->add('city', null, array(
            'attr' => array(
              'class' => 'form-control'
            )
          ))
          ->add('country', null, array(
            'attr' => array(
              'class' => 'form-control'
            )
          ))
          ->add('language', null, array(
            'query_builder' => function(EntityRepository $er) {
                return $this->getOrderLanguageList($er);
            },
            'attr' => array(
              'class' => 'form-control'
            )
          ))
          ->add('description', null, array(
            'attr' => array(
              'class' => 'form-control'
            )
          ))
          ->add('file')
        ;

        $builder->add('favSport1', 'entity', array(
          'class' => 'TigreboiteFunkylabBundle:Sport',
          'query_builder' => function(EntityRepository $er) {
              return $this->getOrderedSportsList($er);
          },
          'attr' => array( 'class' => 'form-control' ),
          'empty_data'  => null,
          'required' => false
        ));
        $builder->add('favSport2', 'entity', array(
          'class' => 'TigreboiteFunkylabBundle:Sport',
          'query_builder' => function(EntityRepository $er) {
              return $this->getOrderedSportsList($er);
          },
          'attr' => array( 'class' => 'form-control' ),
          'empty_data'  => null,
          'required' => false
        ));
        $builder->add('favSport3', 'entity', array(
          'class' => 'TigreboiteFunkylabBundle:Sport',
          'query_builder' => function(EntityRepository $er) {
              return $this->getOrderedSportsList($er);
          },
          'attr' => array( 'class' => 'form-control' ),
          'empty_data'  => null,
          'required' => false
        ));


        if (!$this->front) {
            $builder
              // ->add('username', null, array(
              //     'attr' => array(
              //         'class' => 'form-control'
              //     )
              // ))
              ->add('decathlonCardId', null, array(
                'attr' => array(
                  'class' => 'form-control'
                )
              ))
              ->add('idFacebook', null, array(
                'attr' => array(
                  'class' => 'form-control'
                )
              ))
              ->add('idTwitter', null, array(
                'attr' => array(
                  'class' => 'form-control'
                )
              ))
              ->add('idGoogleplus', null, array(
                'attr' => array(
                  'class' => 'form-control'
                )
              ))
              ->add('twitterOauth', null, array(
                'attr' => array(
                  'class' => 'form-control'
                )
              ))
              ->add('twitterOauthSecret', null, array(
                'attr' => array(
                  'class' => 'form-control'
                )
              ))
              ->add('nbPointsCurrent', null, array(
                'attr' => array(
                  'class' => 'form-control'
                )
              ))
              ->add('nbPointsTotal', null, array(
                'attr' => array(
                  'class' => 'form-control'
                )
              ))
              ->add('newsletter', 'choice', array(
                'attr' => array(
                  'class' => 'form-control'
                )
              ))
              ->add('newsletterPartner', null, array(
                'attr' => array(
                  'class' => 'form-control'
                )
              ))
              ->add('language', null, array(
                'attr' => array(
                  'class' => 'form-control'
                )
              ))
              ->add('howItWorkDisplay', null, array(
                'attr' => array(
                  'class' => 'form-control'
                )
              ))
              ->add('dob', 'date',array(
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'attr' => array('class' => 'date form-control'),
                'label' => 'Date of birth'
              ))
              ->add('civility', 'choice', array(
                'choices' => array(
                  'Mrs' => 'Mrs',
                  'Mr' => 'Mr'
                ),
                'multiple'  => false,
                'expanded' => false,
                'required'  => false,
                'empty_value' => false,
                'attr' => array(
                  'class' => 'form-control'
                )
              ))
              ->add('roles', 'choice', array(
                'choices' => array(
                  User::ROLE_BRAND => User::ROLE_BRAND,
                  User::ROLE_MODERATOR => User::ROLE_MODERATOR,
                  User::ROLE_SUPER_ADMIN => User::ROLE_SUPER_ADMIN,
                  User::ROLE_ADMIN => User::ROLE_ADMIN,
                ),
                'multiple'  => true,
                'expanded' => true,
                'required'  => false,
                'empty_value' => false,
                'attr' => array(
                  'class' => 'form-control'
                )
              ))
            ;
        }
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
          'data_class' => 'Tigreboite\FunkylabBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_user';
    }

    private function getOrderLanguageList($er)
    {
        $languages = $er->createQueryBuilder('u')
          ->where('u.isenable = 1')
          ->orderBy('u.name', 'ASC');

        return $languages;
    }

    private function getOrderedSportsList($er)
    {
        $sports = $er->createQueryBuilder('u')
          ->orderBy('u.name', 'ASC');

        $t = $this->container->get('translator');
        $sort = array();
        foreach($sports->getQuery()->getResult() as $s)
        {
            $sort[$t->trans($s->getName())]=$s;
        }
        ksort($sort);

        $ids = array();
        foreach ($sort as $sport) {
            $ids[] = $sport->getId();
        }

        $sports = $er->createQueryBuilder('u')
          ->addSelect('FIELD(u.id,'.implode(',', $ids).') as HIDDEN field')
          ->where('u.id IN ('.implode(',',$ids).')')
          ->orderBy('field');

        return $sports;
    }
}

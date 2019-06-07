<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class UsersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prenom')
            ->add('nom')
            ->add('email', EmailType::class)
                /*->add('photo', FileType::class, array('label' => 'Users (jpeg, jpg, png image)','data_class' => null))*/
            ->add(
                'plainPassword', RepeatedType::class, array(
                    'type' => PasswordType::class,
                    'first_options' => array('label' => 'Mot de passe'),
                    'second_options' => array('label' => 'Confirmation du mot de passe'),
                )
            )
                /* ->add('password', PasswordType::class, array('label' => 'Confirmation du mot de passe'))
                /*->add('submit', SubmitType::class, ['label'=>'Envoyer', 'attr'=>['class'=>'btn-primary btn-block']])*/
        ;
    }



    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
            'data_class' => User::class,
            ]
        );
    }
}

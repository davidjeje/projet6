<?php

namespace App\Form;

use App\Entity\Tricks;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class VideoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            
            ->add(
                $options['video'],
                TextType::class,
                ['attr' => ['class' => 'form-control']]
            );
    }




    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
            'data_class' => Tricks::class,
            'video' => null,
            ]
        );
    }
}

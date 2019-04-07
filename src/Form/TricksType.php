<?php

namespace App\Form;

use App\Entity\Tricks;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;



class TricksType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('level')
            ->add('groupname')
            ->add('image', FileType::class, array('label' => 'Tricks (jpeg, jpg, png image)','data_class' => null))
            ->add('video', UrlType::class, array('label' => 'Video (AVI, MP4, MKV…)'))

        
        ;
    }

   /* public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('level')
            ->add('groupname')
            ->add('image', FileType::class, array('label' => 'Tricks (jpeg, jpg, png image)','data_class' => null))
            ->add('video', CollectionType::class, [
                'entry_type' =>UrlType::class,
                'entry_options' => ['label' => true],
                'allow_add' => true,
            ]);
    }*/


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tricks::class,
            //'empty_data ' => getImage(),
        ]);
    }

    
}

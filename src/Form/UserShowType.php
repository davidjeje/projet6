<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class UserShowType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder)
    {
        $builder
                ->add('commentaire', TextareaType::class);
    }
}

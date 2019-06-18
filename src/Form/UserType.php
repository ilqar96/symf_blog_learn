<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email' ,null ,[
                'attr'=>['class'=>'form-control' ,'placeholder'=>'enter post content' ],
            ])
            ->add('roles',null ,[
                'attr'=>['class'=>'form-control' ,'placeholder'=>'enter post content' ],
            ])
            ->add('password',null ,[
                'attr'=>['class'=>'form-control' ,'placeholder'=>'enter post content' ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}

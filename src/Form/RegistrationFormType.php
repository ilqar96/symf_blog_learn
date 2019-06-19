<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class , [
                'attr'=>[
                    'class'=>'form-control form-control-user',
                    'placeholder'=>'Email Address',
                ],
                    'label' => false
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
                'invalid_message' => 'The password fields must match.',
                'required' => true,
                'first_options'  => [ 'label'=>false ,'attr'=>[ 'class' => 'form-control form-control-user', 'placeholder'=>'Repeat Password'] ],
                'second_options' => [  'label'=>false ,'attr'=>[ 'class' => 'form-control form-control-user', 'placeholder'=>'Password'] ],
            ])
//            ->add('termsAccepted', CheckboxType::class, [
//                'mapped' => false,
//                'constraints' => new IsTrue(),
//                'attr'=>[
//                    'style'=>'width:20px; display:inline',
//                    'class'=>'form-control',
//                ],
//                'label' => false
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

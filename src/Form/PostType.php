<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\Tag;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',null,['attr'=>['class'=>'form-control' ,'placeholder'=>'enter post title' ],'label'=>'Title'])
            ->add('content',null,['attr'=>['class'=>'form-control' ,'placeholder'=>'enter post content' ],'label'=>'Content'])
            ->add('image',FileType::class ,[
                'data_class' => null ,
                'attr'=>['class'=>'form-control' ,'placeholder'=>'enter post content' ],
                'label'=>'Image' ,'required'=>''
            ])
            ->add('category',EntityType::class,[
                'class' => Category::class,
                'choice_label' => 'name',
                'attr'=>['class'=>'form-control' ,'placeholder'=>'enter post category' ],
                'label'=>'Category',
            ]);
            $builder->add('tags', CollectionType::class, [
                'entry_type' => TagType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }

    public function getBlockPrefix()
    {
        return "";
    }
}

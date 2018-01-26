<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('description', TextType::class)
            ->add('price', NumberType::class)
            ->add('vegetables', EntityType::class, [
                'class'=>'App\Entity\Ingredient',
                'required'=>false,
                'multiple'=>true
            ])
            ->add('breads', EntityType::class, [
                'class'=>'App\Entity\Ingredient',
                'required'=>false,
                'multiple'=>true
            ])
            ->add('breads', EntityType::class, [
                'class'=>'App\Entity\Ingredient',
                'required'=>false,
                'multiple'=>true
            ])
            ->add('types', EntityType::class, [
                'class'=>'App\Entity\Type',
                'required'=>true,
                'multiple'=>true
            ])
            ->add('isActive', CheckboxType::class)
            ->add('images', CollectionType::class,[
                'entry_type'=>ImageProductType::class,
                'allow_add'  => true,
                'allow_delete'  => true,
                'by_reference' => false,
                'label' => 'Fichier(s) :',
                'prototype' => true
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}

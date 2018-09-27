<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Provider;
use App\Repository\CityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\ImagesProviderType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProviderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('streetName',TextType::class)
            ->add('streetNr',TextType::class)
            ->add('email',EmailType::class)
            ->add('phone', TextType::class)
            ->add('city', EntityType::class,[
                'class'=> City::class,
                'query_builder' => function (CityRepository $r) {
                    return $r->createQueryBuilder('c')
                        ->orderBy('c.zip', 'ASC');
                }
            ])
            ->add('imageFile',VichImageType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Provider::class,
        ]);
    }
}

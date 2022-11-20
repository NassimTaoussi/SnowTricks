<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Trick;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du trick :',
            ])
            ->add('description', TextType::class, [
                'label' => 'Description :',
            ])
            ->add('category', EntityType::class, [
                'label' => 'Categorie :',
                'class' => Category::class,
            ])
            ->add('photos', CollectionType::class , [
                'mapped' => false,
                'entry_type' => UrlType::class,
            ])
            ->add('videos', CollectionType::class , [
                'mapped' => false,
                'entry_type' => UrlType::class,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}

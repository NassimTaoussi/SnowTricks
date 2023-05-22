<?php

namespace App\Form;

use App\Entity\Photo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class PhotoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $photo = $event->getData();
            $form = $event->getForm();

            if (null === $photo || null === $photo->getId()) {
            }
        })
        ->add('file', FileType::class, [
            'mapped' => true,
            'required' => false,
            'label' => false,
        ])
        ->add('cover', CheckboxType::class, [
            'required' => false,
            'label' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Photo::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Band;
use App\Entity\Member;
use App\Entity\Picture;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class MemberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Prénom'
            ])
            ->add('job', TextType::class, [
                'label' => 'Activité'
            ])
            ->add('birthDate', DateType::class, [
                'widget' => 'choice',
                'format' => 'dd / MM / yyyy',
                'label' => 'Date de naissance'
            ])
            ->add('picture', FileType::class, [
                'label' => 'Photo',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'Veuillez choisir une image png ou jpeg.',
                    ])
                ],

                'attr' => [
                    'placeholder' => 'Choisir un fichier'

                ]
            ])
            ->add('band', EntityType::class, [
                'class' => Band::class,
                'choice_label' => 'name',
                'label' => 'Groupe'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Member::class,
        ]);
    }
}

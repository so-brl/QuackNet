<?php

namespace App\Form;

use App\Entity\Duck;
use Doctrine\DBAL\Types\ArrayType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class DuckType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('uploadFileName', FileType::class, [
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                            'image/gif',
                            'image/x-icon',
                            'image/svg + xml'
                        ],
                        'mimeTypesMessage' => 'Merci de choisir une image moins lourde',
                    ])
                ]
            ])
            ->add('firstname',TextType::class)
            ->add('lastname',TextType::class)
            ->add('duckname',TextType::class)
            ->add('email',EmailType::class)
            ->add('password',RepeatedType::class, [
        'type' => PasswordType::class,
        'first_options' => ['label' => 'Password'],
        'second_options' => ['label' => 'Confirm Password']
    ])
           // ->add('roles',ArrayType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Duck::class,
        ]);
    }
}

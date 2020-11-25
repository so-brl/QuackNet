<?php

namespace App\Form;

use App\Entity\Quack;
use App\Entity\Tag;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\TextType;



class QuackType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('content', TextType::class)
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
            ->add('tags')
//            ->add('tags', CollectionType::class, [
////                'entry_type' => TagType::class,
////                'entry_options' => ['label' => true],
////                'allow_add' => true,
////            ])
          ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Quack::class,
        ]);
    }

}

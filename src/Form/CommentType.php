<?php

namespace App\Form;

use App\Entity\Quack;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', TextareaType::class, [
                'attr' => [
                    'class' => 'comment-textarea col-auto',
                    'placeholder' => 'Ajouter un commentair à ce Quack',
                    'rows' => '1',
                    'cols' => '60',
                ],
            ])
        ;

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Quack::class,
        ]);
    }
}

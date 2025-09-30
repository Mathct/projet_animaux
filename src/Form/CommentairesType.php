<?php

namespace App\Form;

use App\Entity\Articles;
use App\Entity\Commentaires;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentairesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => 'Nom',
            ])
            ->add('comment', null, [
                'label' => 'Commentaires',
            ])
            ->add('notation', ChoiceType::class, [
                'label'=>'note',
                'choices' => [
                0 => 0,
                1 => 1,
                2 => 2,
                3 => 3,
                4 => 4,
                5 => 5,
            ],
            ])
            ->add('article', EntityType::class, [
                'class' => Articles::class,
                'choice_label' => 'id',
                'attr' => [
                    'style' => 'display:none',
                ],
                'label' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commentaires::class,
        ]);
    }
}

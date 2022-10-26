<?php

namespace App\Form;

use App\Entity\Rank;
use App\Entity\SubCategory;
use phpDocumentor\Reflection\Types\Collection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AsinFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('asin', TextType::class, [
                'attr' => ['placeholder' => 'ASIN']
            ])
            ->add('Rank',EntityType::class, [
                'class' => Rank::class,
                'choice_label' => 'title',
                'label' => 'Rang'
            ])
            ->add('SubCategory',EntityType::class, [
                'class' => SubCategory::class,
                'choice_label' => 'title',
                'label' => 'Sous catégorie'
            ])
            ->add('pathProduct', TextType::class, [
                'attr' => ['placeholder' => 'Path product']
            ])
            ->add('add', SubmitType::class, ['label' => 'Ajouter'])
            ->add('update', SubmitType::class, ['label' => 'Mettre à jour'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}

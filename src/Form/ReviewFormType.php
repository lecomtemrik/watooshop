<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Review;
use Doctrine\DBAL\Types\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReviewFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => ['placeholder' => 'Title']
            ])
            ->add('body', TextType::class, [
                'attr' => ['placeholder' => 'body']
            ])
            ->add('rating', NumberType::class, [
                'attr' => ['placeholder' => 'rating']
            ])
            ->add('date', TextType::class, [
                'attr' => ['placeholder' => 'date']
            ])
            ->add('profileName', TextType::class, [
                'attr' => ['placeholder' => 'Profile name']
            ])
            ->add('profilePicture', TextType::class, [
                'attr' => ['placeholder' => 'Profile picture']
            ])
            ->add('country', TextType::class, [
                'attr' => ['placeholder' => 'Country']
            ])
            ->add('product', EntityType::class, [
                'class' => Product::class,
                'choice_label' => 'title',
                'label' => 'Product'
            ])
            ->add('add', SubmitType::class, ['label' => 'Ajouter'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
        ]);
    }
}

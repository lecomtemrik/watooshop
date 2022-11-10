<?php

namespace App\Form;

use App\Entity\Attribute;
use App\Entity\Product;
use Cassandra\Tinyint;
use Doctrine\DBAL\Types\BooleanType;
use phpDocumentor\Reflection\Types\Boolean;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AttributeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => ['placeholder' => 'Name']
            ])
            ->add('value', TextType::class, [
                'attr' => ['placeholder' => 'Value']
            ])
            ->add('state', ChoiceType::class, [
                'choices' => [
                'true' => '1',
                'false' => '0'],
                'attr' => ['placeholder' => 'State']
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
            'data_class' => Attribute::class,
        ]);
    }
}

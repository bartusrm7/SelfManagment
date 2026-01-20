<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use function PHPSTORM_META\type;

class BudgetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('budget',  TextType::class, [
                'label' => 'New budget',
                'attr' => ['class' => 'form-control']
            ])
            ->add('date',  DateType::class, [
                'label' => 'Date',
                'data' => new \DateTime(),
                'attr' => ['class' => 'form-control']
            ])
            ->add('saveBudget', SubmitType::class, [
                'label' => 'Save budget',
                'attr' => ['class' => 'w-100 btn btn-dark mt-3 fw-bold border-light']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}

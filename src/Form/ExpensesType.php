<?php

namespace App\Form;

use App\Entity\Expenses;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExpensesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Expense name',
                'attr' => ['class' => 'form-control']
            ])
            ->add('amount', TextType::class, [
                'label' => 'Expense amount',
                'attr' => ['class' => 'form-control']
            ])
            ->add('date', DateType::class, [
                'label' => 'Expense date',
                'data' => new \DateTime(),
                'attr' => ['class' => 'form-control']
            ])
            ->add('saveExpense', SubmitType::class, [
                'label' => 'Save expense',
                'attr' => ['class' => 'w-100 btn btn-dark mt-3 fw-bold border-light']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Expenses::class,
        ]);
    }
}

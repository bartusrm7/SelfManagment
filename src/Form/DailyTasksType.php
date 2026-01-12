<?php

namespace App\Form;

use App\Entity\DailyTasks;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType as TypeDateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DailyTasksType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('taskName', TextType::class, [
                'label' => 'Task name',
                'attr' => ['class' => 'form-control']
            ])
            ->add('taskDescription', TextType::class, [
                'label' => 'Task description',
                'attr' => ['class' => 'form-control'],
                'required' => false
            ])
            ->add('saveTask', SubmitType::class, [
                'label' => 'Save task',
                'attr' => ['class' => 'w-100 btn btn-dark mt-3 fw-bold border-light']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DailyTasks::class,
        ]);
    }
}

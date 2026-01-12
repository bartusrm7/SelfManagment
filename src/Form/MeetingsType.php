<?php

namespace App\Form;

use App\Entity\Meetings;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MeetingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Meeting name',
                'label_attr' => ['class' => 'ms-1'],
                'attr' => ['class' => 'mb-2 form-control', 'id' => 'meetingName']
            ])
            ->add('description', TextType::class, [
                'label' => 'Meeting description',
                'label_attr' => ['class' => 'ms-1'],
                'attr' => ['class' => 'mb-2 form-control', 'id' => 'meetingDescription'],
                'required' => false
            ])
            ->add('startDate', DateTimeType::class, [
                'label' => 'Start date',
                'label_attr' => ['class' => 'ms-1'],
                'attr' => ['class' => 'mb-2 form-control', 'id' => 'meetingStartDate'],
                'data' => new \DateTime(),
                'minutes' => [0, 30]
            ])
            ->add('endDate', DateTimeType::class, [
                'label' => 'End date',
                'label_attr' => ['class' => 'ms-1'],
                'attr' => ['class' => 'mb-3 form-control', 'id' => 'meetingEndDate'],
                'data' => new \DateTime(),
                'minutes' => [0, 30]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Meetings::class,
        ]);
    }
}

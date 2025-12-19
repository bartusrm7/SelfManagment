<?php

namespace App\Form;

use App\Entity\Notes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NotesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('noteName', TextType::class, [
                'label' => 'Note name',
                'attr' => ['class' => 'form-control']
            ])
            ->add('noteDescription', TextType::class, [
                'label' => 'Note description',
                'attr' => ['class' => 'form-control']
            ])
            ->add('saveNote', SubmitType::class, [
                'label' => 'Save note',
                'attr' => ['class' => 'w-100 btn btn-dark mt-3 fw-bold border-light']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Notes::class,
        ]);
    }
}

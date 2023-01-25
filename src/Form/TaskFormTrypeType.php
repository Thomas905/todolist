<?php

namespace App\Form;

use App\Entity\Task;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Dropzone\Form\DropzoneType;

class TaskFormTrypeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la tâche',
                'attr' => [
                    'placeholder' => 'Nom de la tâche',
                    'class' => 'form-control',
                ],
            ])
            ->add('description', TextType::class, [
                'label' => 'Description de la tâche',
                'attr' => [
                    'placeholder' => 'Description de la tâche',
                    'class' => 'form-control',
                ],
            ])
            ->add('photo', DropzoneType::class, [
                'label' => 'Photo de la tâche',
                'attr' => [
                    'placeholder' => 'Photo de la tâche',
                    'class' => 'form-control',
                ],
                'required' => false,
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Statut de la tâche',
                'attr' => [
                    'placeholder' => 'Statut de la tâche',
                    'class' => 'form-control',
                ],
                'choices' => [
                    'A faire' => 'A faire',
                    'En cours' => 'En cours',
                    'Terminée' => 'Terminée',
                ],
            ])
            ->add('endDate', DateType::class, [
                'label' => 'Date de fin de la tâche',
                'attr' => [
                    'placeholder' => 'Date de fin de la tâche',
                    'class' => 'form-control',
                ],
                'widget' => 'single_text',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}

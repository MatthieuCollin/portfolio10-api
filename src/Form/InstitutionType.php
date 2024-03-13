<?php

namespace App\Form;

use App\Entity\task;
use App\Entity\Institution;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class InstitutionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('beginDate', null, [
                'widget' => 'single_text',
            ])
            ->add('endDate', null, [
                'widget' => 'single_text',
            ])
            ->add('type')
            ->add('link')
            ->add('type', ChoiceType::class, [
                'label' => 'Veuillez choisir un type',
                'choices' => [
                    'École' => 'school',
                    'Travail' => 'work',
                ],
            ])
            ->add('task', EntityType::class, [
                'class' => task::class,
                'choice_label' => 'name',
                'multiple' => true,
                "required"=> false
            ])
            ->add('image', FileType::class, [
                'label' => 'File',
                'required' => true,
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Institution::class,
        ]);
    }
}

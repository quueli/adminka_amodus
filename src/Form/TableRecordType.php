<?php

namespace App\Form;

use App\Entity\TableRecord;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TableRecordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('field1', TextType::class, [
                'label' => 'Field 1',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter field 1 value'
                ],
                'required' => true,
            ])
            ->add('field2', TextType::class, [
                'label' => 'Field 2',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter field 2 value'
                ],
                'required' => true,
            ])
            ->add('field3', TextType::class, [
                'label' => 'Field 3',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter field 3 value'
                ],
                'required' => false,
            ])
            ->add('field4', TextType::class, [
                'label' => 'Field 4',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter field 4 value'
                ],
                'required' => false,
            ])
            ->add('field5', TextType::class, [
                'label' => 'Field 5',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter field 5 value'
                ],
                'required' => false,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save',
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TableRecord::class,
        ]);
    }
}

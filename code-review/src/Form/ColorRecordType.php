<?php

namespace App\Form;

use App\Entity\ColorRecord;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ColorRecordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('color', TextType::class, [
                'label' => 'color',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'enter_color'
                ],
                'required' => true,
            ])
            ->add('hexColorNumber', TextType::class, [
                'label' => 'hex_color_number',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'enter_hex_color'
                ],
                'required' => true,
            ])
            ->add('saturation', TextType::class, [
                'label' => 'saturation',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'enter_saturation'
                ],
                'required' => true,
            ])
            ->add('palette', TextType::class, [
                'label' => 'palette',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'enter_palette'
                ],
                'required' => true,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'save',
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ColorRecord::class,
        ]);
    }
}

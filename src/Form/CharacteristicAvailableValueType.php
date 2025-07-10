<?php

namespace App\Form;

use App\Entity\Characteristic;
use App\Entity\CharacteristicAvailableValue;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CharacteristicAvailableValueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('characteristic', EntityType::class, [
                'class' => Characteristic::class,
                'choice_label' => 'name',
                'label' => 'characteristic',
                'attr' => [
                    'class' => 'form-select'
                ],
                'placeholder' => 'select_characteristic',
                'required' => true,
            ])
            ->add('value', TextType::class, [
                'label' => 'available_value',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'enter_available_value'
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
            'data_class' => CharacteristicAvailableValue::class,
        ]);
    }
}

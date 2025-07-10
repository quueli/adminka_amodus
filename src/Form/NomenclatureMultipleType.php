<?php

namespace App\Form;

use App\Entity\Nomenclature;
use App\Entity\Characteristic;
use App\Entity\CharacteristicAvailableValue;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NomenclatureMultipleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomenclatureName', TextType::class, [
                'label' => 'nomenclature_name',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'enter_nomenclature_name'
                ],
                'required' => true,
            ])
            ->add('characteristicAvailableValues', EntityType::class, [
                'class' => CharacteristicAvailableValue::class,
                'choice_label' => function(CharacteristicAvailableValue $value) {
                    return $value->getCharacteristic()->getName() . ': ' . $value->getValue();
                },
                'label' => 'available_values',
                'attr' => [
                    'class' => 'form-select',
                    'id' => 'nomenclature_available_values',
                    'multiple' => true,
                    'size' => 10
                ],
                'multiple' => true,
                'expanded' => false,
                'required' => true,
                'mapped' => false,
                'help' => 'select_multiple_values_help'
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
            'data_class' => Nomenclature::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Characteristic;
use App\Entity\CharacteristicAvailableValue;
use App\Entity\Nomenclature;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NomenclatureType extends AbstractType
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
            ->add('characteristic', EntityType::class, [
                'class' => Characteristic::class,
                'choice_label' => 'name',
                'label' => 'characteristic',
                'attr' => [
                    'class' => 'form-select',
                    'id' => 'nomenclature_characteristic'
                ],
                'placeholder' => 'select_characteristic',
                'required' => true,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'save',
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ]);

        $formModifier = function (FormInterface $form, Characteristic $characteristic = null) {
            $availableValues = null === $characteristic ? [] : $characteristic->getAvailableValues();

            $form->add('characteristicAvailableValue', EntityType::class, [
                'class' => CharacteristicAvailableValue::class,
                'choice_label' => 'value',
                'label' => 'available_value',
                'attr' => [
                    'class' => 'form-select',
                    'id' => 'nomenclature_available_value'
                ],
                'placeholder' => 'select_available_value',
                'choices' => $availableValues,
                'required' => true,
            ]);
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier) {
                $data = $event->getData();
                $formModifier($event->getForm(), $data?->getCharacteristic());
            }
        );

        $builder->get('characteristic')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                $characteristic = $event->getForm()->getData();
                $formModifier($event->getForm()->getParent(), $characteristic);
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Nomenclature::class,
        ]);
    }
}

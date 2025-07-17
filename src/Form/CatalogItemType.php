<?php

namespace App\Form;

use App\Entity\CatalogItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CatalogItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'catalog_item_name',
                'attr' => [
                    'placeholder' => 'enter_catalog_item_name',
                    'class' => 'form-control'
                ]
            ])
            ->add('baseType', ChoiceType::class, [
                'label' => 'base_type',
                'required' => false,
                'choices' => [
                    'Фасон' => 'Фасон',
                    'Модель' => 'Модель',
                ],
                'placeholder' => 'select_base_type',
                'attr' => ['class' => 'form-select']
            ])
            ->add('item', TextType::class, [
                'label' => 'item_category',
                'required' => false,
                'attr' => [
                    'placeholder' => 'enter_item_category',
                    'class' => 'form-control'
                ]
            ])
            ->add('location', TextType::class, [
                'label' => 'location',
                'required' => false,
                'attr' => [
                    'placeholder' => 'enter_location',
                    'class' => 'form-control'
                ]
            ])
            ->add('mainItem', TextType::class, [
                'label' => 'main_item',
                'required' => false,
                'attr' => [
                    'placeholder' => 'enter_main_item',
                    'class' => 'form-control'
                ]
            ])
            ->add('itemName', TextType::class, [
                'label' => 'item_name',
                'required' => false,
                'attr' => [
                    'placeholder' => 'enter_item_name',
                    'class' => 'form-control'
                ]
            ])
            ->add('layer', TextType::class, [
                'label' => 'layer',
                'required' => false,
                'attr' => [
                    'placeholder' => 'enter_layer',
                    'class' => 'form-control',
                    'maxlength' => 10
                ]
            ])
            ->add('synonym', TextareaType::class, [
                'label' => 'synonym',
                'required' => false,
                'attr' => [
                    'placeholder' => 'enter_synonym',
                    'class' => 'form-control',
                    'rows' => 3
                ]
            ])
            ->add('constructionDetails', TextType::class, [
                'label' => 'construction_details',
                'required' => false,
                'attr' => [
                    'placeholder' => 'enter_construction_details',
                    'class' => 'form-control'
                ]
            ]);

        // Сезоны для основной одежды
        $builder
            ->add('warmSummer', CheckboxType::class, [
                'label' => 'warm_summer',
                'required' => false,
                'attr' => ['class' => 'form-check-input']
            ])
            ->add('coolSummerWarmSpringAutumn', CheckboxType::class, [
                'label' => 'cool_summer_warm_spring_autumn',
                'required' => false,
                'attr' => ['class' => 'form-check-input']
            ])
            ->add('coolSpringAutumnWarmWinter', CheckboxType::class, [
                'label' => 'cool_spring_autumn_warm_winter',
                'required' => false,
                'attr' => ['class' => 'form-check-input']
            ])
            ->add('coldWinter', CheckboxType::class, [
                'label' => 'cold_winter',
                'required' => false,
                'attr' => ['class' => 'form-check-input']
            ]);

        // Сезоны для верхней одежды
        $builder
            ->add('outerWarmSummer', CheckboxType::class, [
                'label' => 'outer_warm_summer',
                'required' => false,
                'attr' => ['class' => 'form-check-input']
            ])
            ->add('outerCoolSummerWarmSpringAutumn', CheckboxType::class, [
                'label' => 'outer_cool_summer_warm_spring_autumn',
                'required' => false,
                'attr' => ['class' => 'form-check-input']
            ])
            ->add('outerCoolSpringAutumnWarmWinter', CheckboxType::class, [
                'label' => 'outer_cool_spring_autumn_warm_winter',
                'required' => false,
                'attr' => ['class' => 'form-check-input']
            ])
            ->add('outerColdWinter', CheckboxType::class, [
                'label' => 'outer_cold_winter',
                'required' => false,
                'attr' => ['class' => 'form-check-input']
            ]);

        $builder->add('save', SubmitType::class, [
            'label' => 'save',
            'attr' => ['class' => 'btn btn-success']
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CatalogItem::class,
        ]);
    }
}

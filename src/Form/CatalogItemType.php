<?php

namespace App\Form;

use App\Entity\CatalogItem;
use App\Repository\CatalogItemRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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
            ->add('parent', EntityType::class, [
                'class' => CatalogItem::class,
                'choice_label' => function (CatalogItem $item) {
                    $level = $item->getLevel();
                    $prefix = str_repeat('— ', $level);
                    return $prefix . $item->getName();
                },
                'placeholder' => 'select_parent_item',
                'required' => false,
                'label' => 'parent_item',
                'attr' => ['class' => 'form-select'],
                'query_builder' => function (CatalogItemRepository $repository) use ($options) {
                    $qb = $repository->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                    
                    // Исключаем текущий элемент и его потомков при редактировании
                    if ($options['data'] && $options['data']->getId()) {
                        $qb->where('c.id != :currentId')
                           ->setParameter('currentId', $options['data']->getId());
                    }
                    
                    return $qb;
                }
            ])
            ->add('baseType', TextType::class, [
                'label' => 'base_type',
                'required' => false,
                'attr' => [
                    'placeholder' => 'enter_base_type',
                    'class' => 'form-control'
                ]
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
            ])
            ->add('sortOrder', IntegerType::class, [
                'label' => 'sort_order',
                'required' => false,
                'attr' => [
                    'placeholder' => 'enter_sort_order',
                    'class' => 'form-control',
                    'min' => 0
                ]
            ]);

        // Добавляем сезонные поля
        for ($i = 1; $i <= 8; $i++) {
            $builder->add('season' . $i, CheckboxType::class, [
                'label' => 'season_' . $i,
                'required' => false,
                'attr' => ['class' => 'form-check-input']
            ]);
        }

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

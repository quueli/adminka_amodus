<?php

namespace App\Form;

use App\Entity\Nomenclature;
use App\Entity\Characteristic;
use App\Entity\CharacteristicAvailableValue;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NomenclatureMultipleType extends AbstractType
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

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
            ]);

        // Получаем все характеристики с их доступными значениями
        $characteristics = $this->entityManager
            ->getRepository(Characteristic::class)
            ->createQueryBuilder('c')
            ->leftJoin('c.availableValues', 'av')
            ->addSelect('av')
            ->orderBy('c.name', 'ASC')
            ->addOrderBy('av.value', 'ASC')
            ->getQuery()
            ->getResult();

        // Создаем поля для каждой характеристики
        foreach ($characteristics as $characteristic) {
            $availableValues = $characteristic->getAvailableValues();

            if ($availableValues->count() > 0) {
                $choices = [];
                foreach ($availableValues as $value) {
                    $choices[$value->getValue()] = $value->getId();
                }

                $builder->add('characteristic_' . $characteristic->getId(), ChoiceType::class, [
                    'label' => $characteristic->getName(),
                    'choices' => $choices,
                    'multiple' => true,
                    'expanded' => true,
                    'required' => false,
                    'mapped' => false,
                    'attr' => [
                        'class' => 'characteristic-group',
                        'data-characteristic-id' => $characteristic->getId(),
                        'data-characteristic-name' => $characteristic->getName(),
                        'data-available-count' => $availableValues->count()
                    ]
                ]);
            }
        }

        $builder->add('save', SubmitType::class, [
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

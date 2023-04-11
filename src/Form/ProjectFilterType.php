<?php

namespace App\Form;

use App\Dto\ProjectFilter;
use App\Entity\Company;
use App\Entity\ProjectType;
use App\Entity\Sector;
use App\Form\Constraints\Between;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectFilterType extends AppBaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = [
            $this->trans('and') => 0,
            $this->trans('or') => 1
        ];

        $this->setBuilder($builder);
        $this
            ->add('type', EntityType::class, [
                'class' => ProjectType::class,
                'required' => false
            ])
            ->add('sector', EntityType::class, [
                'class' => Sector::class,
                'required' => false
            ])
            ->add('sectorOr', CheckboxType::class, [
                'label' => $this->trans('or'),
                'required' => false
            ])
            ->add('company', EntityType::class, [
                'class' => Company::class,
                'required' => false
            ])
            ->add('companyOr', CheckboxType::class, [
                'label' => $this->trans('or'),
                'required' => false
            ])
            ->add('amountLessThan', IntegerType::class, [
                'required' => false,
                'label' => 'amountMoreThan',
                'constraints' => [
                    new Between(
                        'amountMoreThan',
                        $this->trans('this field must be lower than %value%')
                    )
                ]
            ])
            ->add('amountLessThanOr', CheckboxType::class, [
                'label' => $this->trans('or'),
                'required' => false
            ])
            ->add('amountMoreThan', IntegerType::class, [
                'required' => false,
                'label' => 'amountLessThan',
                'constraints' => [
                    new Between(
                        'amountLessThan',
                        $this->trans('this field must be upper than %value%'),
                        Between::UPPER
                    )
                ]
            ])
            ->add('amountMoreThanOr', CheckboxType::class, [
                'label' => $this->trans('or'),
                'required' => false
            ])
            // ->add('startBefore', DateType::class, [
            //     'required' => false,
            //     'label' => 'startBefore',
            //     'widget' => 'single_text',
            //     'constraints' => [
            //         new DateLimit(
            //             'startAfter',
            //             $this->trans('this field must be lower than %value%')
            //         )
            //     ]
            // ])
            // ->add('startBeforeOr', CheckboxType::class, [
            //     'label' => $this->trans('or'),
            //     'required' => false
            // ])
            ->add('startAfter', DateType::class, [
                'required' => false,
                'label' => 'startAfter',
                'widget' => 'single_text',
                'constraints' => [
                    // new DateLimit(
                    //     'startBefore',
                    //     $this->trans('this field must be upper than %value%'),
                    //     DateLimit::UPPER
                    // )
                ]
            ])
            ->add('startAfterOr', CheckboxType::class, [
                'label' => $this->trans('or'),
                'required' => false
            ])
            ->add('endBefore', DateType::class, [
                'required' => false,
                'label' => 'endBefore',
                'widget' => 'single_text',
                'constraints' => [
                    // new DateLimit(
                    //     'endAfter',
                    //     $this->trans('this field must be lower than %value%')
                    // )
                ]
            ])
            ->add('endBeforeOr', CheckboxType::class, [
                'label' => $this->trans('or'),
                'required' => false
            ])
            // ->add('endAfter', DateType::class, [
            //     'required' => false,
            //     'label' => 'endAfter',
            //     'widget' => 'single_text',
            //     'constraints' => [
            //         new DateLimit(
            //             'endBefore',
            //             $this->trans('this field must be upper than %value%'),
            //             DateLimit::UPPER
            //         )
            //     ]
            // ])
            // ->add('endAfterOr', CheckboxType::class, [
            //     'label' => $this->trans('or'),
            //     'required' => false
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => ProjectFilter::class,
            ]
        );
    }
}

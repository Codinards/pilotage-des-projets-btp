<?php

namespace App\Form;

use App\Entity\Project;
use App\Form\Constraints\DateLimit;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectEditType extends AppBaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->setBuilder($builder);
        $this
            ->add('name')
            ->add('cost')
            ->add('startAt', DateType::class, [
                'widget' => 'single_text',
                'required' => false
            ])
            ->add('type')
            ->add('company')
            ->add('sector')
            ->add('presentedAt', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('endAt', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}

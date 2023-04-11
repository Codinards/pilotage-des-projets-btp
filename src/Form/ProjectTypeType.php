<?php

namespace App\Form;

use App\Entity\ProjectType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectTypeType extends AppBaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->setBuilder($builder);
        $this
            ->add('name');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProjectType::class,
        ]);
    }
}

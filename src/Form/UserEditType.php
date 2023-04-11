<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserEditType extends AppBaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->setBuilder($builder);
        $this
            ->add('pseudo')
            ->add('role')
            ->add('name');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

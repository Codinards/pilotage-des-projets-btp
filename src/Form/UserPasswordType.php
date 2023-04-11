<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserPasswordType extends AppBaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->setBuilder($builder);
        $this
            ->add('passwordEdit', RepeatedType::class, [
                'first_options' => [
                    'label' => $this->trans('password')
                ],
                'second_options' => [
                    'label' => $this->trans('password') . ' (Confirmation)'
                ],
                'type' => PasswordType::class
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

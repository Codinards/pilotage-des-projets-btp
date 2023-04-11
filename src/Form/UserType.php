<?php

namespace App\Form;

use App\Entity\User;
use App\Form\Transformers\ArrayTransfomer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserType extends AppBaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->setBuilder($builder);
        $this
            ->add('pseudo')
            ->add('role')
            ->add('password', RepeatedType::class, [
                'first_options' => [
                    'label' => $this->trans('password')
                ],
                'second_options' => [
                    'label' => $this->trans('password') . ' (Confirmation)'
                ],
                'type' => PasswordType::class
            ])
            ->add('name');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

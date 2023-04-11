<?php

namespace App\Form;

use App\Entity\Company;
use App\Twig\TranslatorTwigExtension;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyType extends AppBaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->setBuilder($builder);
        $this
            ->add('name')
            ->add('address')
            ->add('phoneNumber')
            ->add('description');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
        ]);
    }
}

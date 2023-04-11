<?php

namespace App\Form;

use App\Twig\TranslatorTwigExtension;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


class AppBaseType extends AbstractType
{
    private ?FormBuilderInterface $builder = null;

    public function __construct(
        private TranslatorTwigExtension $translator
    ) {
    }

    public function setBuilder(FormBuilderInterface $builder): self
    {
        $this->builder = $builder;

        return $this;
    }

    public function add(string|FormBuilderInterface $child, ?string $type = null, array $options = []): self
    {
        $label = is_string($child) ? $child : $child->getName();

        $this->builder->add($child, $type, $options = array_merge($options, [
            'label' => ($options['label'] ?? $this->translator->__u($label))
        ]));

        return $this;
    }

    protected function trans(string $id, $parameters = [], ?string $domain = null, ?string $locale = null): string
    {
        return $this->translator->__u($id, $parameters, $domain, $locale);
    }
}

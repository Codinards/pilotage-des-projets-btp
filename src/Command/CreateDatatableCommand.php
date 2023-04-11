<?php

namespace App\Command;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\InflectorFactory;
use Doctrine\ORM\Mapping\Annotation;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\OneToMany;
use Exception;
use ReflectionClass;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;

#[AsCommand(
    name: 'make:app:datatable',
    description: 'Make datatable from doctrine entity',
)]
class CreateDatatableCommand extends Command
{

    static ?Inflector $inflector = null;
    private string $header = '';
    private string $targetEntityActionsBlock = '';
    private string $propertyActionsBlock = '';

    public static function getCommandName(): string
    {
        return 'make:app:datatable';
    }

    public static function getCommandDescription(): string
    {
        return 'Make datatable from doctrine entity';
    }

    protected function configure(): void
    {
        $this
            ->addArgument('entity', InputArgument::REQUIRED, 'Entity of component class');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $entityArgument = $input->getArgument('entity');
        $data = [];
        if ($entityArgument) {
            $io->note(sprintf('You passed an argument: %s', $entityArgument));
            if (class_exists($entity = ('App\Entity\\' . $entityArgument))) {

                $reflectionEntity = new \ReflectionClass($entity);
                $ormClassAttribute = $reflectionEntity->getAttributes(Entity::class)[0] ?? null;
                if (!empty($ormClassAttribute)) {
                    $propertiesBlock = '';
                    $constructorBlock  = '';
                    $this->header = $this->buildHeaderBlock(AsLiveComponent::class, 'App\Components\\' . $entityArgument . 'Component');
                    $this->buildHeaderBlock(LiveProp::class);
                    $this->buildHeaderBlock(LiveArg::class);
                    $this->buildHeaderBlock(LiveAction::class);
                    $this->buildHeaderBlock(\Symfony\UX\LiveComponent\DefaultActionTrait::class);
                    $ormEntity = $ormClassAttribute->newInstance();
                    $entityRepository = $ormEntity->repositoryClass;
                    $properties = $reflectionEntity->getProperties();
                    $propertiesBlock = $this->buildPropertiesBlock(lcfirst($this->pluralize(basename($entityArgument))), $entity, [], $propertiesBlock);
                    $propertiesBlock = $this->buildPropertiesBlock(lcfirst($this->pluralize(basename($entityArgument))) . 'Count', 'int', [], $propertiesBlock);
                    $constructorBlock = $this->buildConstructor(lcfirst($this->pluralize(basename($entityArgument))), $entity, [], $constructorBlock);
                    $count = count($properties);
                    $i = 1;
                    foreach ($properties as $property) {
                        $mappedAttributes = $this->mapReflectionAttribute($property->getAttributes(Annotation::class, \ReflectionAttribute::IS_INSTANCEOF));
                        if (in_array(Id::class, array_keys($mappedAttributes)) && in_array(GeneratedValue::class, array_keys($mappedAttributes))) {
                            if ($mappedAttributes[GeneratedValue::class]->strategy === "AUTO") {
                                continue;
                            }
                        }
                        $type = $this->getEntityPropertyTypes($property->getType());
                        $data[$entity][$property->getName()] = [
                            'type' => $type,
                            'attributes' => $mappedAttributes
                        ];
                        $propertiesBlock = $this->buildPropertiesBlock($property->getName(), $type, $mappedAttributes, $propertiesBlock);
                        $constructorBlock = $this->buildConstructor($property->getName(), $type, $mappedAttributes, $constructorBlock);
                        $i++;
                        $targetEntityActionsBlock = $this->buildTargetEntityActionsBlock($property->getName(), $type, $entity, $i === $count);
                        $this->buildPropertyActionsBlock($property->getName(), $type, $entity);
                    }
                    $data[$entity]['entity_repository'] = $entityRepository;
                    $constructorBlock = str_replace(['{{injection}}', '{{records}}'], '', $constructorBlock);
                    $class = ($this->header . "\n\n#[AsLiveComponent(\"" . Str::asSnakeCase($entityArgument)
                        . "\")]\nclass " . Str::asClassName($entityArgument)
                        . "Component {\n\n" . $propertiesBlock . $constructorBlock . "\n\n"
                        .  $targetEntityActionsBlock . "\n\n" . $this->buildOrderFieldAction($entity)
                        . "\n\n" . $this->propertyActionsBlock . "\n\n}");
                    file_put_contents(dirname(__DIR__) . "\Components\\" . Str::asClassName($entityArgument)
                        . "Component.php", $class);
                    $io->success($entityRepository);
                    $this->initializeProps();
                    return Command::SUCCESS;
                }
                $io->error('Class ' . $entity . ' is not a doctrine Entity class.');
                return Command::FAILURE;
            }
        }

        $io->error('Missing class ' . $entity . '.');
        return Command::FAILURE;
    }

    private function buildHeaderBlock(string $useString, ?string $namespace = null)
    {
        if ($this->header === '') {
            return $this->header .= "<?php\nnamespace " . $namespace . ";\n\nuse " . $useString . ";\n";
        }
        return $this->header .=  'use ' . $useString . ";\n";
    }

    private function getEntityPropertyTypes(\ReflectionNamedType|\ReflectionUnionType/*|\ReflectionIntersectionType*/|null $reflectionType): string|array
    {
        if ($reflectionType instanceof \ReflectionNamedType) {
            return $reflectionType->getName();
        }
        return array_map(fn (\ReflectionNamedType $t) => $t->getName(), $reflectionType->getTypes());
    }



    public function buildPropertiesBlock(String $field, string $type, array $attributes, string $previousBlock = ''): string
    {
        if ($previousBlock === '') {
            $previousBlock .= "\t#[LiveProp(true)]\n\tpublic string \$orderField = \"id\";\n\n";
            $previousBlock .= "\t#[LiveProp(true)]\n\tpublic string \$orderDirection = \">=\";\n\n";
        }
        if (is_array($type)) {
            dd($type);
        } else {
            $type = $this->getTypeFromCollection($type, $attributes);
            if (in_array(
                $type,
                ['int', 'float', 'string', 'bool', DateTime::class, DateTimeInterface::class, DateTimeImmutable::class]
            ) || class_exists($type) || $type === null) {
                $type = $type === null ? 'string' : $type;
                if (in_array($type, ['int', 'float', DateTime::class, DateTimeInterface::class, DateTimeImmutable::class])) {
                    $previousBlock .= "\t#[LiveProp(true)]\n\tpublic ?" . (!$this->isNumber($type) ? "string" :  $type) . " $" . $field . " = null;\n\n";
                    $previousBlock .= "\t#[LiveProp(true)]\n\tpublic string $" . $field . "FilterDirection = \">=\";\n\n";
                    if (
                        in_array($type, [DateTime::class, DateTimeInterface::class, DateTimeImmutable::class])
                        && !str_contains($this->header, 'use ' . $type . ';')
                    ) {

                        $this->buildHeaderBlock($type);
                    }
                } elseif (class_exists($type)) {
                    $previousBlock .= "\tpublic array $" . (substr($field, strlen($field) - 1, strlen($field)) === 's' ? $field : $this->pluralize($field)) . " = [];\n\n";
                    $previousBlock .= "\t#[LiveProp(true)]\n\tpublic ?int $" . $this->singularize($field) . " = null;\n\n";
                } elseif ($type === 'string') {
                    $previousBlock .= "\t#[LiveProp(true)]\n\tpublic ?string $" . $field . "Search = null;\n\n";
                } elseif ($type === 'bool') {
                    $previousBlock .= "\t#[LiveProp(true)]\n\tpublic ?" . $type . " $" . $field . " = false;\n\n";
                }
            }
        }
        return $previousBlock;
    }

    private function buildConstructor(String $field, string $type, array $attribute, string $previousBlock = ''): string
    {
        if ($previousBlock === '') {
            $previousBlock = "\tuse DefaultActionTrait;\n\n\t" . 'public function __construct(' . "{{injection}}" . ")\n\t{\n\t{{records}}\n\t}";
        }
        $type = $this->getTypeFromCollection($type, $attribute);

        if (class_exists($type)) {
            if ($this->isDoctrineEntity($type)) {
                $previousBlock = str_replace(
                    '{{injection}}',
                    'private ' . ucfirst(basename($type)) . 'Repository $' . lcfirst(basename($type)) . "Repository,\n\t\t{{injection}}",
                    $previousBlock
                );
                $previousBlock = str_replace(
                    '{{records}}',
                    "\t" . 'if(empty($this->' . $field . ")){\n\t\t\t\$this->" . $field . ' = $this->' . lcfirst(basename($type)) . 'Repository->findAll();' . "\n\t\t" . '}{{records}}',
                    $previousBlock
                );
                $this->buildHeaderBlock(str_replace("Entity", "Repository", $type) . 'Repository', $this->header);
            }
        }
        return $previousBlock;
    }

    private function isNumber(string $type): bool
    {
        return in_array($type, ['int', 'float']);
    }

    private function isDateType(string $type): bool
    {
        return in_array($type, [DateTimeInterface::class, DateTime::class, DateTimeImmutable::class]);
    }

    private function buildTargetEntityActionsBlock(string $field, ?string $type, string $entity, bool $close = false): string
    {
        if ($this->targetEntityActionsBlock === '') {
            $this->targetEntityActionsBlock = "\tprivate function get" . ucfirst($this->pluralize(basename($entity))) . "Count(): void{\n\t\t\$this->"
                . $this->pluralize(lcfirst(basename($entity)))
                . "Count = count(\$this->" . lcfirst($this->pluralize(basename($entity))) . ");\n\t}\n\n";
            $this->targetEntityActionsBlock .= "\tprivate function actualize" . ucfirst($this->pluralize(basename($entity))) . "(): void{\n\t\t"
                . "\$this->get" . $this->pluralize(lcfirst(basename($entity))) . "();\n\t\t"
                . " \$this->get" . $this->pluralize(lcfirst(basename($entity))) . "Count();\n\t}\n\n";
            $this->targetEntityActionsBlock .= "\tprivate function get" . ucfirst($this->pluralize(basename($entity))) . "(): array{\n\t\t\$criterias = [];";
        } else {
            if (in_array($type, ['int', 'float', DateTimeInterface::class, DateTime::class, DateTimeImmutable::class])) {
                $this->targetEntityActionsBlock .= "\n\tif(\$this->" . $field . " !== null){\n\t\t\$criterias[\"" . $field . "\"] = "
                    . '[' . ($this->isNumber($type) ? "\$this->" . $field : 'new ' . $type . '($this->' . $field . ")")
                    . ', $this->' . $field . 'FilterDirection];' . "\n\t}";
            } elseif ($type === 'string') {
                $this->targetEntityActionsBlock .= "\n\tif(\$this->" . $field . "Search !== null){\n\t\t\$criterias[\"" . $field . "\"] = "
                    .  "\"%\" . \$this->" . $field .  "Search . \"%\";\n\t}";
            } else {
                $this->targetEntityActionsBlock .= "\n\tif(\$this->" . $field . " !== null){\n\t\t\$criterias[\"" . $field . "\"] = "
                    .  "\$this->" . $field . ";\n\t}";
            }
        }
        if ($close) {
            $this->targetEntityActionsBlock .= "\n\t\t\$this->" . $this->pluralize(lcfirst(basename($entity))) . ' = '
                . '$this->' . lcfirst(basename($entity)) . "Repository->findFromComponent(\$criterias, [\$this->orderField, \$this->orderDirection]);"
                . "\n\t\treturn \$this->" . $this->pluralize(lcfirst(basename($entity))) . ";\n\t}";
        }
        return $this->targetEntityActionsBlock;
    }

    private function buildOrderFieldAction(string $entity)
    {
        return "\t#[LiveAction]\n\tpublic function setOrderField(#[LiveArg('name')] string \$name)
        {
            if (\$this->orderField === \$name) {
                \$this->orderDirection = \$this->orderDirection === 'ASC' ? 'DESC' : 'ASC';
            } else {
                \$this->orderField = \$name;
                \$this->orderDirection = 'ASC';
            }
            \$this->actualize" . ucfirst($this->pluralize(basename($entity))) . "();\n"
            . "\t\treturn \$this->orderField;
        }";
    }

    private function buildPropertyActionsBlock(string $field, string $type, string $entity)
    {
        $this->propertyActionsBlock .= "\n\n\t#[LiveAction]\n\tpublic function " . $field . "Filter(){\n\t\t"
            . (($this->isNumber($type) || in_array($type, ['string', 'bool']) || $this->isDateType($type))
                ? ('$this->actualize' . ucfirst($this->pluralize(basename($entity))) . "();\n\t\t" .
                    'return $this->' . $field . ($type === 'string' ? "Search" : '')) : '')
            . ";\n\t}";
    }

    private function getTypeFromCollection(string $type, array $attributes)
    {
        if ($type === Collection::class) {
            return $attributes[ManyToMany::class]->targetEntity ?? $attributes[OneToMany::class]->targetEntity;
        }
        return $type;
    }

    private function isDoctrineEntity(string|ReflectionClass $entity): bool
    {
        if (is_string($entity)) {
            $entity = new ReflectionClass($entity);
        }
        return !empty($entity->getAttributes(Entity::class));
    }


    private function mapReflectionAttribute(array $attributes): array
    {
        $records = [];
        /** @var \ReflectionAttribute[] $attributes */
        foreach ($attributes as $attribute) {
            $records[$attribute->getName()] = $attribute->newInstance();
        }

        return $records;
    }

    private static function getInflector(): Inflector
    {
        if (null === static::$inflector) {
            static::$inflector = InflectorFactory::create()->build();
        }

        return static::$inflector;
    }

    private static function singularize(string $word): string
    {
        return static::getInflector()->singularize($word);
    }

    private static function pluralize(string $word): string
    {
        return static::getInflector()->pluralize($word);
    }

    private function initializeProps()
    {
        $this->header = '';
        $this->targetEntityActionsBlock = '';
        $this->propertyActionsBlock = '';
    }
}

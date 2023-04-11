<?= $helper->getHeadPrintCode("{{ __t('" .  $entity_class_name . "') }} {{ __u('index') }}"); ?>

{% block body %}
<h1>{{ __t('<?= $entity_class_name ?>') }} {{ __('index') }}</h1>

<table class="table table-bordered">
    <thead class="bg-secondary">
        <tr>
            <?php foreach ($entity_fields as $field) : ?>
                <th>{{ __u('<?= ucfirst($field['fieldName']) ?>') }}</th>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
        {% for <?= $entity_twig_var_singular ?> in <?= $entity_twig_var_plural ?> %}
        <tr>
            <?php foreach ($entity_fields as $field) : ?>
                <td>{{ <?= $helper->getEntityFieldPrintCode($entity_twig_var_singular, $field) ?> }}</td>
            <?php endforeach; ?>
        </tr>
        {% endfor %}
    </tbody>
</table>
{% endblock %}
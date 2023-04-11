<?= $helper->getHeadPrintCode("{{ __t('" .  $entity_class_name . "') }} {{ __u('index') }}"); ?>

{% block body %}
<h1>{{ __t('<?= $entity_class_name ?>') }} {{ __('index') }}</h1>

<div class="bg-dark">
    <hr>
    <hr>
</div>

<div class="row">
    <div class="col text-right">
        <a href="{{ path('<?= $route_name ?>_new', {_locale: app.request.locale ?? app.request.defaultLocale}) }}" class="btn btn-primary">{{ __u('Create new') }}</a>
        <a href="{{ path('<?= $route_name ?>_index', {_locale: app.request.locale ?? app.request.defaultLocale, pdf: true}) }}" class="btn btn-info" style="min-width: 100px;">{{ __u('to PDF')  }}</a>
    </div>
</div>
<hr>
<table class="table">
    <thead class="bg-secondary">
        <tr>
            <?php foreach ($entity_fields as $field) : ?>
                <th>{{ __u('<?= ucfirst($field['fieldName']) ?>') }}</th>
            <?php endforeach; ?>
            <th>{{ __('actions') }}</th>
        </tr>
    </thead>
    <tbody>
        {% for <?= $entity_twig_var_singular ?> in <?= $entity_twig_var_plural ?> %}
        <tr>
            <?php foreach ($entity_fields as $field) : ?>
                <td>{{ <?= $helper->getEntityFieldPrintCode($entity_twig_var_singular, $field) ?> }}</td>
            <?php endforeach; ?>
            <td>
                <a href="{{ path('<?= $route_name ?>_show', {'<?= $entity_identifier ?>': <?= $entity_twig_var_singular ?>.<?= $entity_identifier ?>, _locale: app.request.locale ?? app.request.defaultLocale}) }}" class="btn btn-info">{{ __('show') }}</a>
                <a href="{{ path('<?= $route_name ?>_edit', {'<?= $entity_identifier ?>': <?= $entity_twig_var_singular ?>.<?= $entity_identifier ?>, _locale: app.request.locale ?? app.request.defaultLocale}) }}" class="btn btn-success">{{ __('edit') }}</a>
            </td>
        </tr>
        {% endfor %}
    </tbody>
</table>
{% endblock %}
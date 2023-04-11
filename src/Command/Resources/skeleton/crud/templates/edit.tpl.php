<?= $helper->getHeadPrintCode("{{ __u('Edit') }} {{ __t('" .  $entity_class_name . "') }}") ?>

{% block body %}
<h1>{{ __u('Edit') }} {{ __t('<?= $entity_class_name ?>') }}</h1>
<div class="bg-dark">
    <hr>
    <hr>
</div>
<a href="{{ path('<?= $route_name ?>_index', {_locale: app.request.locale ?? app.request.defaultLocale}) }}" class="btn btn-info">{{ __('back to list') }}</a>
<hr>
{{ include('<?= $templates_path ?>/_form.html.twig', {'button_label': 'Update'}) }}

{{ include('<?= $templates_path ?>/_delete_form.html.twig') }}
{% endblock %}
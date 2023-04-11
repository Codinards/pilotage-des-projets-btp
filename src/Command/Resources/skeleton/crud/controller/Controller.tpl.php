<?= "<?php\n" ?>
namespace <?= $namespace ?>;

<?= $use_statements; ?>

#[Route('<?= $route_path ?>')]
class <?= $class_name ?> extends AbstractController
{
<?= $generator->generateRouteForControllerMethod('/{_locale}', sprintf('%s_index', $route_name), ['GET'], requirements: ['_locale' => 'fr|en|es']) ?>
<?php if (isset($repository_full_class_name)) : ?>
    public function index(Request $request, <?= $repository_class_name ?> $<?= $repository_var ?>): Response
    {
    $toPDF = $request->query->get('pdf');
    $templatePath = '<?= $templates_path ?>/' . ($toPDF ? 'pdf' : 'index' ). '.html.twig';

    return $this->render($templatePath, [
    '<?= $entity_twig_var_plural ?>' => $<?= $repository_var ?>->findAll(),
    ]);
    }
<?php else : ?>
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
    $toPDF = $request->query->get('pdf');
    $<?= $entity_var_plural ?> = $entityManager
    ->getRepository(<?= $entity_class_name ?>::class)
    ->findAll();

    $templatePath = '<?= $templates_path ?>/' . ($toPDF ? 'pdf' : 'index' ). '.html.twig';
    return $this->render($templatePath, [
    '<?= $entity_twig_var_plural ?>' => $<?= $entity_var_plural ?>,
    ]);
    }
<?php endif ?>

<?= $generator->generateRouteForControllerMethod('/{_locale}/new', sprintf('%s_new', $route_name), ['GET', 'POST']) ?>
<?php if (isset($repository_full_class_name) && $generator->repositoryHasAddRemoveMethods($repository_full_class_name)) { ?>
    public function new(Request $request, <?= $repository_class_name ?> $<?= $repository_var ?>): Response
<?php } else { ?>
    public function new(Request $request, EntityManagerInterface $entityManager): Response
<?php } ?>
{
$<?= $entity_var_singular ?> = new <?= $entity_class_name ?>();
$form = $this->createForm(<?= $form_class_name ?>::class, $<?= $entity_var_singular ?>);
$form->handleRequest($request);

<?php if (isset($repository_full_class_name) && $generator->repositoryHasAddRemoveMethods($repository_full_class_name)) { ?>
    if ($form->isSubmitted() && $form->isValid()) {
    $<?= $repository_var ?>->add($<?= $entity_var_singular ?>, true);
    $this->addFlash('success', 'the entity has been successfully created.');

    return $this->redirectToRoute('<?= $route_name ?>_index', [], Response::HTTP_SEE_OTHER);
    }
<?php } else { ?>
    if ($form->isSubmitted() && $form->isValid()) {
    $entityManager->persist($<?= $entity_var_singular ?>);
    $entityManager->flush();
    $this->addFlash('success', 'the entity has been successfully created.');

    return $this->redirectToRoute('<?= $route_name ?>_index', [], Response::HTTP_SEE_OTHER);
    }
<?php } ?>

<?php if ($use_render_form) { ?>
    return $this->renderForm('<?= $templates_path ?>/new.html.twig', [
    '<?= $entity_twig_var_singular ?>' => $<?= $entity_var_singular ?>,
    'form' => $form,
    ]);
<?php } else { ?>
    return $this->render('<?= $templates_path ?>/new.html.twig', [
    '<?= $entity_twig_var_singular ?>' => $<?= $entity_var_singular ?>,
    'form' => $form->createView(),
    ]);
<?php } ?>
}

<?= $generator->generateRouteForControllerMethod(sprintf('/{_locale}/{%s}', $entity_identifier), sprintf('%s_show', $route_name), ['GET']) ?>
public function show(<?= $entity_class_name ?> $<?= $entity_var_singular ?>): Response
{
return $this->render('<?= $templates_path ?>/show.html.twig', [
'<?= $entity_twig_var_singular ?>' => $<?= $entity_var_singular ?>,
]);
}

<?= $generator->generateRouteForControllerMethod(sprintf('/{_locale}/{%s}/edit', $entity_identifier), sprintf('%s_edit', $route_name), ['GET', 'POST']) ?>
<?php if (isset($repository_full_class_name) && $generator->repositoryHasAddRemoveMethods($repository_full_class_name)) { ?>
    public function edit(Request $request, <?= $entity_class_name ?> $<?= $entity_var_singular ?>, <?= $repository_class_name ?> $<?= $repository_var ?>): Response
<?php } else { ?>
    public function edit(Request $request, <?= $entity_class_name ?> $<?= $entity_var_singular ?>, EntityManagerInterface $entityManager): Response
<?php } ?>
{
$form = $this->createForm(<?= $form_class_name ?>::class, $<?= $entity_var_singular ?>);
$form->handleRequest($request);

<?php if (isset($repository_full_class_name) && $generator->repositoryHasAddRemoveMethods($repository_full_class_name)) { ?>
    if ($form->isSubmitted() && $form->isValid()) {
    $<?= $repository_var ?>->add($<?= $entity_var_singular ?>, true);
    $this->addFlash('success', 'the entity has been successfully updated.');

    return $this->redirectToRoute('<?= $route_name ?>_index', [], Response::HTTP_SEE_OTHER);
    }
<?php } else { ?>
    if ($form->isSubmitted() && $form->isValid()) {
    $entityManager->flush();
    $this->addFlash('success', 'the entity has been successfully updated.');

    return $this->redirectToRoute('<?= $route_name ?>_index', [], Response::HTTP_SEE_OTHER);
    }
<?php } ?>

<?php if ($use_render_form) { ?>
    return $this->renderForm('<?= $templates_path ?>/edit.html.twig', [
    '<?= $entity_twig_var_singular ?>' => $<?= $entity_var_singular ?>,
    'form' => $form,
    ]);
<?php } else { ?>
    return $this->render('<?= $templates_path ?>/edit.html.twig', [
    '<?= $entity_twig_var_singular ?>' => $<?= $entity_var_singular ?>,
    'form' => $form->createView(),
    ]);
<?php } ?>
}

<?= $generator->generateRouteForControllerMethod(sprintf('/{_locale}/{%s}', $entity_identifier), sprintf('%s_delete', $route_name), ['POST']) ?>
<?php if (isset($repository_full_class_name) && $generator->repositoryHasAddRemoveMethods($repository_full_class_name)) { ?>
    public function delete(Request $request, <?= $entity_class_name ?> $<?= $entity_var_singular ?>, <?= $repository_class_name ?> $<?= $repository_var ?>): Response
<?php } else { ?>
    public function delete(Request $request, <?= $entity_class_name ?> $<?= $entity_var_singular ?>, EntityManagerInterface $entityManager): Response
<?php } ?>
{
<?php if (isset($repository_full_class_name) && $generator->repositoryHasAddRemoveMethods($repository_full_class_name)) { ?>
    if ($this->isCsrfTokenValid('delete'.$<?= $entity_var_singular ?>->get<?= ucfirst($entity_identifier) ?>(), $request->request->get('_token'))) {
    $<?= $repository_var ?>->remove($<?= $entity_var_singular ?>, true);
    $this->addFlash('success', 'the entity has been successfully removed.');
    }
<?php } else { ?>
    if ($this->isCsrfTokenValid('delete'.$<?= $entity_var_singular ?>->get<?= ucfirst($entity_identifier) ?>(), $request->request->get('_token'))) {
    $entityManager->remove($<?= $entity_var_singular ?>);
    $entityManager->flush();
    $this->addFlash('success', 'the entity has been successfully removed.');
    }
<?php } ?>

return $this->redirectToRoute('<?= $route_name ?>_index', [], Response::HTTP_SEE_OTHER);
}
}
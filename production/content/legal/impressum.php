<?php
require_once('../../config/dbAccess.php');
$pageTitle       = 'Impressum';
$pageDescription = 'Impressum – weil das Gesetz es so will, aber wir würden lieber unsere Zeit mit dem Verstecken von Schätzen verbringen.';
require_once('../../includes/header.php');
require_once('../../includes/nav.php');
?>

<main id="impressum" role="main" class="impressum container py-5">
  <div class="content-box mx-auto">
    <h2 class="visually-hidden">Impressum</h2>
    <h1 class="mb-4">Impressum</h1>
    <h2 class="mt-4">Keller & Knilche</h2>
    <p class="lead">Kostenloses Idle Clicker-Spiel</p>
    <section class="mt-5">
      <h4>Verantwortlich für den Inhalt</h4>
      <address>
        <p class="mb-1">Max Mustermann</p>
        <p class="mb-1">[PLZ, Ort]</p>
        <p class="mb-1">Österreich</p>
        <p class="mb-1"><a href="mailto:mail@keller-und-knilche.at">mail@keller-und-knilche.at</a></p>
      </address>
    </section>
    <p class="mt-5"><strong>Diese Website dient ausschließlich der kostenlosen Unterhaltung und verfolgt keine kommerziellen Zwecke.</strong></p>
  </div>
</main>
<?php require_once('../../includes/footer.php'); ?>

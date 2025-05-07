<?php
require_once('../../config/dbAccess.php');
$pageTitle       = 'Bedingungen akzeptieren';
$pageDescription = 'Sprich: "Akzeptiert" und tritt ein.';

if (!isset($_SESSION['user'])) {
    header("Location: " . getBaseUrl() . "/login");
    exit();
}
$userId = $_SESSION['user']['id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['terms'])) {
    if (setAcceptedTerms($db, $userId)) {
        header("Location: " . getBaseUrl() . "/profil");
        exit();
    } else {
        echo "<div class='error' style='display: block;'> <p>Technisches Missgeschick: Der Zustimmungs-Zauber ging ins Leere. Bitte nochmal bestätigen.</p> </div>";
    }
}
require_once('../../includes/header.php');
require_once('../../includes/nav.php');
?>

<main id="akzeptieren" role="main" class="akzeptieren container py-5">
  <div class="content-box mx-auto">
    <h2 class="visually-hidden">Bedingungen akzeptieren</h2>
    <h1 class="mb-4">Bedingungen akzeptieren</h1>
    <p>Bevor du weitermachst: Wirf einen Blick auf unsere 
       <a href="<?php echo getBaseUrl(); ?>/nutzungsbedingungen">Nutzungsbedingungen</a> 
       und 
       <a href="<?php echo getBaseUrl(); ?>/datenschutz">Datenschutzbestimmungen</a>  
       – und sprich den Zustimmungszauber aus, um fortzufahren.</p>
    <form method="post">
            <div class="d-grid">
        <button class="btn btn-primary" id="terms" name="terms" type="submit">
          Akzeptieren und weiter
        </button>
      </div>
    </form>
  </div>
</main>
<?php require_once('../../includes/footer.php'); ?>

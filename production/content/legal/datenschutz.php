<?php
require_once('../../config/dbAccess.php');
$pageTitle       = 'Datenschutz';
$pageDescription = 'Datenschutzerklärung – versprochen, wir verscharren deine Daten nicht in unseren finsteren Verliesen.';
require_once('../../includes/header.php');
require_once('../../includes/nav.php');
?>

<main id="datenschutz" role="main" class="datenschutz container py-5">
  <div class="content-box mx-auto">
    <h2 class="visually-hidden">Datenschutz</h2>
    <h1 class="mb-4">Datenschutz</h1>

    <section class="mb-5">
      <h2>Erklärung zur Informationspflicht</h2>
      <h3>Datenschutzerklärung</h3>
      <p>In folgender Datenschutzerklärung informieren wir Sie über die wichtigsten Aspekte der Datenverarbeitung im Rahmen unserer Webseite. Wir erheben und verarbeiten personenbezogene Daten nur auf Grundlage der gesetzlichen Bestimmungen (Datenschutzgrundverordnung, Telekommunikationsgesetz 2003).</p>
      <p>Sobald Sie als Benutzer auf unsere Webseite zugreifen oder diese besuchen, wird Ihre IP-Adresse sowie Beginn und Ende der Sitzung erfasst. Dies ist technisch bedingt und stellt ein berechtigtes Interesse iSv Art 6 Abs 1 lit f DSGVO dar.</p>
    </section>

    <section class="mb-5">
      <h3>Kontakt mit uns</h3>
      <p>Wenn Sie uns entweder über unser Kontaktformular oder per E-Mail kontaktieren, werden die von Ihnen übermittelten Daten zwecks Bearbeitung Ihrer Anfrage oder für Anschlussfragen sechs Monate lang gespeichert. Es erfolgt keine Weitergabe ohne Ihre Einwilligung.</p>
    </section>

    <section class="mb-5">
      <h3>Cookies</h3>
      <p>Unsere Website verwendet sogenannte Cookies – kleine Textdateien, die auf Ihrem Gerät gespeichert werden. Sie ermöglichen es, unsere Angebote nutzerfreundlicher zu gestalten. Einige Cookies bleiben gespeichert, bis Sie sie löschen. Sie können Ihren Browser so einstellen, dass Sie über das Setzen von Cookies informiert werden und dies nur im Einzelfall erlauben. Die Deaktivierung von Cookies kann die Funktionalität der Website einschränken.</p>
    </section>

    <!-- noch nicht relevant
    <h3>Google Fonts</h3>
    <p>Unsere Website verwendet Schriftarten von „Google Fonts“, bereitgestellt durch:</p>
    <ul>
        <li>Google Ireland Limited, Gordon House, Barrow Street, Dublin 4, Ireland</li>
    </ul>
    <p>Tel: +353 1 543 1000</p>
    <p>Beim Laden unserer Website lädt Ihr Browser die Schriftarten und speichert sie im Cache. Google kann in diesem Zusammenhang Cookies setzen oder analysieren. Die Nutzung dient der einheitlichen Darstellung und Optimierung unserer Inhalte gemäß Art. 6 Abs. 1 lit. f DSGVO.</p>
    <p>Mehr Informationen:</p>
    <ul>
        <li><a href="https://developers.google.com/fonts/faq">Google Fonts FAQ</a></li>
        <li><a href="https://policies.google.com/privacy?hl=de">Google Datenschutzerklärung</a></li>
        <li><a href="https://www.privacyshield.gov/EU-US-Framework">EU-US Privacy Shield</a></li>
    </ul>
    -->

    <h3>Server-Log-Files</h3>
    <p>Unser Webserver speichert automatisch Informationen in sogenannten „Server-Log Files“:</p>
    <ul>
        <li>IP-Adresse oder Hostname</li>
        <li>Verwendeter Browser</li>
        <li>Aufenthaltsdauer, Datum und Uhrzeit</li>
        <li>Besuchte Seiten</li>
        <li>Sprache und Betriebssystem</li>
        <li>Verweisende Seite („Leaving-Page“)</li>
        <li>ISP (Internet Service Provider)</li>
    </ul>
    <p>Diese Daten sind nicht personenbezogen und werden nicht mit anderen Daten verknüpft. Eine Überprüfung erfolgt nur bei Verdacht auf rechtswidrige Nutzung.</p>

    <h3>Ihre Rechte</h3>
    <p>Sie haben das Recht auf:</p>
    <ul>
        <li>Auskunft</li>
        <li>Löschung</li>
        <li>Berichtigung</li>
        <li>Datenübertragbarkeit</li>
        <li>Widerruf und Widerspruch</li>
        <li>Einschränkung der Verarbeitung</li>
    </ul>
    <p>Bei Datenschutzverstößen kontaktieren Sie uns unter [E-Mail] oder wenden Sie sich an die Datenschutzbehörde.</p>

    <h3>Kontakt</h3>
    <p><strong>Webseitenbetreiber:</strong> [Name]<br>
    <strong>Telefon:</strong> [Telefonnummer]<br>
    <strong>E-Mail:</strong> [E-Mail]</p>
  </div>
</main>

<?php require_once('../../includes/footer.php'); ?>

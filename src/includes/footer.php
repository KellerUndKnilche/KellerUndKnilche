<footer class="container-fluid bg-dark text-light mt-5">
  <?php if (isset($_SESSION['user'])) { ?>
  <div id="currency-display" class="nav-item d-flex p-0 justify-content-center">
      <span id="currency">0.00</span>
      <span id="proSekunde" class="ms-1"></span>
  </div>
  <?php } ?>
<div class="d-lg-flex justify-content-center d-flex flex-wrap align-items-center">  
  <ul class="nav justify-content-center">
    <li class="nav-item">
      <a class="nav-link text-light" href="/impressum">Impressum</a>
    </li>
    <li class="nav-item">
      <a class="nav-link text-light" href="/datenschutz">Datenschutz</a>
    </li>
    <li class="nav-item">
      <a class="nav-link text-light" href="/nutzungsbedingungen">Nutzungsbedingungen</a>
    </li>
  </ul>
</div>
  
</footer>
<script src="/assets/js/fontSwitcher.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="/assets/js/sidePanels.js"></script>
<script src="/assets/js/script.js"></script>
</body>
</html>
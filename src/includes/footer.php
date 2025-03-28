<footer class="container-fluid bg-dark text-light mt-5">
  <ul class="nav justify-content-center">
    <li class="nav-item">
      <a class="nav-link text-light" href="/index.php">Spiel</a>
    </li>
    <li class="nav-item">
      <a class="nav-link text-light" href="/content/user/statistics.php">Statistiken</a>
    </li>
    <?php if (isset($_SESSION['user'])) { 
          if ($_SESSION['user']['isAdmin'] == 1) { ?>
            <li class="nav-item">
              <a class="nav-link text-light" href="/content/user/adminDashboard.php">Admin-Dashboard</a>
            </li>
          <?php } ?>
          <li class="nav-item">
            <a class="nav-link text-light" href="/content/user/profile.php">Profil</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-light" href="/content/user/logout.php">Logout</a>
          </li>
    <?php } else { ?>
          <li class="nav-item">
            <a class="nav-link text-light" href="/content/user/registration.php">Registrierung</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-light" href="/content/user/login.php">Login</a>
          </li>
    <?php } ?>
  </ul>
</footer>
<script src="/assets/js/fontSwitcher.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
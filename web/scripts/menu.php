<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="pl-15 pr-15 collapse navbar-collapse" id="navbarTogglerDemo01">
    <a class="navbar-brand" href="http://timber2602.beget.tech/">Месседжер</a>
    <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
		<? if (isset($_SESSION['logged_user'])): ?>
		<li class="nav-item">
			<a class="nav-link" href="http://timber2602.beget.tech/pages/profil.php">Редактирование профиля</a>
		</li>
      <li class="nav-item">
        <a class="nav-link" href="http://timber2602.beget.tech/scripts/logout.php">Выход</a>
      </li>
		<? endif; ?>
    </ul>
  </div>
</nav>
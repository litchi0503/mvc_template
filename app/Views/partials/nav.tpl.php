<nav class="navbar navbar-expand-lg navbar-dark bg-dark">

	<div class="container-fluid">

		<a class="navbar-brand" href="<?= $router->generate('main-home'); ?>">oShop</a>

		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav me-auto mb-2 mb-lg-0">

				<li class="nav-item">
					<a class="nav-link <?= str_contains($viewName, 'main') ? 'active' : ''; ?>" href="<?= $router->generate('main-home') ?>">Accueil
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link <?= str_contains($viewName, 'truck') ? 'active' : ''; ?>" href="<?= $router->generate('truck-list') ?>">Catégories
					</a>
				</li>


				<li class="nav-item">
					<a class="nav-link <?= str_contains($viewName, 'user') ? 'active' : ''; ?>" href="<?= $router->generate('user-list') ?>">Utilisateurs</a>
				</li>
			</ul>

			<div class="d-flex">
				<ul class="navbar-nav mr-auto">
					<span class="navbar-brand"> </span>

					<?php if (isset($_SESSION['user'])) : ?>
						<li class="nav-item">
							<a class="nav-link" href="<?= $router->generate('session-logout') ?>">Logout</a>
						</li>
					<?php else : ?>
						<li class="nav-item">
							<a class="nav-link" href="<?= $router->generate('session-login') ?>">Login</a>
						</li>
					<?php endif; ?>
					
				</ul>
			</div>
		</div>
	</div>
</nav>
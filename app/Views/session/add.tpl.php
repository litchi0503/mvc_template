<div class="container my-4">
    <a href="<?= $router->generate('session-list'); ?>" class="btn btn-success float-end">Retour</a>
    <h2>Ajouter un utilisateur</h2>

    <?php require __DIR__ . '/../partials/errorMessages.tpl.php'; ?>

    <form action="" method="POST" class="mt-5">
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="text" class="form-control" id="email" name="email" placeholder="Mettre l'email">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="text" class="form-control" id="password" name="password" placeholder="Mettre le mot de passe" aria-describedby="subtitleHelpBlock">
            <small id="subtitleHelpBlock" class="form-text text-muted">
            </small>
        </div>
        <div class="mb-3">
            <label for="firstname" class="form-label">Prénom</label>
            <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Mettre le prénom" aria-describedby="pictureHelpBlock">
            <small id="pictureHelpBlock" class="form-text text-muted">
            </small>
        </div>
        <div class="mb-3">
            <label for="lastname" class="form-label">Nom</label>
            <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Mettre le nom" aria-describedby="pictureHelpBlock">
            <small id="pictureHelpBlock" class="form-text text-muted">
            </small>
        </div>
        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select class="form-control" id="role" name="role">
                <option value="">Sélectionnez un rôle</option>
                <option value="admin">Admin</option>
                <option value="catalog-manager">Catalog-manager</option>
                <!-- Ajoutez autant d'options que nécessaire -->
            </select>
            <small id="roleHelpBlock" class="form-text text-muted">
            </small>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Satus</label>
            <select class="form-control" id="status" name="status">
                <option value="">Sélectionnez un status</option>
                <option value="-">-</option>
                <option value="0">désactivé</option>
                <option value="1">actif</option>
                <!-- Ajoutez autant d'options que nécessaire -->
            </select>
            <small id="roleHelpBlock" class="form-text text-muted">
            </small>
        </div>
        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary mt-5">Valider</button>
        </div>
    </form>
</div>
</div>
<div class="container h-100">
    <div class="d-flex row justify-content-center align-items-center vh-100">
        <div class="col-6">
            <?php require __DIR__ . '/../partials/errorMessages.tpl.php'; ?>
            <div class="card">
                <div class="card-body">
                    <h2 class="text-center mb-4">Se connecter</h2>
                    <form action="" method="post">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" placeholder="email" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="password">Mot de passe</label>
                            <input type="password" name="password" id="password" placeholder="password" class="form-control">
                        </div>
                        <div class="form-group d-flex justify-content-center align-items-center mt-3">
                            <input type="submit" value="Se connecter" class="btn btn-primary">
                        </div>

                    </form>
                </div>
            </div>
            <div class="text-center mt-3">
                <a href="" class="btn btn-link">S'inscrire</a>
                <a href="" class="btn btn-link">Se déconnecter</a>
            </div>
        </div>
    </div>
</div>
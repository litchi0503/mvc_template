<div class="container my-4">
    <a href="" class="btn btn-success float-end">Ajouter</a>
    <h2>Liste des catégories</h2>
    <table class="table table-striped table-hover mt-4">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Marque</th>
                <th scope="col">Type</th>
                <th scope="col">Poids</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            <!-- Categorie -->
            <?php foreach ($truckList as $truck) : ?>
                <tr>
                    <?php dump($truck);?>
                    <th scope="row"><?= $truck->getId(); ?></th>
                    <td><?= $truck->getBrand(); ?></td>
                    <td><?= $truck->getType(); ?></td>
                    <td><?= $truck->getPods()/1000; ?>T</td>
                    <td class="text-end">
                        <a href="" class="btn btn-sm btn-warning">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        </a>
                        <!-- Example single danger button -->
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="">Oui, je veux supprimer</a>
                                <a class="dropdown-item" href="#" data-toggle="dropdown">Oups !</a>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            <!-- Categorie -->
        </tbody>
    </table>
</div>
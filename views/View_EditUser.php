<?php

// Chargement des traductions si non déjà chargées
if (!isset($t)) {
    $translations = include 'lang.php';
    $lang = $_SESSION['lang'] ?? 'fr';
    $t = $translations[$lang];
}

// Message de succès d'ajout
$ajoutReussi = isset($_GET['ajout']) && $_GET['ajout'] === 'succeed' ? $t['saveSuccess'] : '';

?>

<div class="container p-5">
    <!-- Titre de la page -->
    <h1 class="text-center mb-4 text-light"><?= $t['editUsers'] ?></h1>
    <?php if (isset($error)): ?>
        <!-- Affichage des messages d'erreur -->
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($error); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="<?= $t['close'] ?>"></button>
        </div>
    <?php endif; ?>
    <?php if (!empty($ajoutReussi)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($ajoutReussi); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="<?= $t['close'] ?>"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow">
        <div class="card-body">
            <!-- Formulaire pour éditer les utilisateurs -->
            <form method="POST" action="">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle text-center" id="usersTable">
                        <thead class="table-dark">
                        <tr>
                            <th><?= $t['ID'] ?></th>
                            <th><?= $t['identifiant'] ?></th>
                            <th><?= $t['email'] ?></th>
                            <th><?= $t['admin'] ?></th>
                            <th><?= $t['action'] ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td>
                                    <input type="hidden" name="id[]" value="<?= htmlspecialchars($user->getId()) ?>">
                                    <?= htmlspecialchars($user->getId()) ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($user->getIdentifiant()) ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($user->getEmail()) ?>
                                </td>
                                <td class="admin-cell" style="background-color: <?= $user->isAdmin() ? '#d4edda' : '#f8d7da' ?>;">
                                    <div class="d-flex justify-content-center">
                                        <div class="form-check form-switch">
                                            <input type="hidden" name="admin[<?= $user->getId() ?>]" value="0">
                                            <input class="form-check-input admin-toggle" type="checkbox" name="admin[<?= $user->getId() ?>]" value="1" <?= $user->isAdmin() ? 'checked' : '' ?>>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm remove-user">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Boutons d'actions -->
                <div class="d-flex justify-content-end mt-3">
                    <!-- Bouton pour enregistrer le formulaire -->
                    <button type="submit" class="btn btn-primary" id="saveButton"><?= $t['save'] ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const usersTable = document.getElementById('usersTable');

        // Changement du statut administrateur
        usersTable.addEventListener('change', (event) => {
            if (event.target.classList.contains('admin-toggle')) {
                const checkbox = event.target;
                const row = checkbox.closest('tr');
                const adminCell = row.querySelector('.admin-cell');
                const isAdmin = checkbox.checked;

                // Changement visuel
                adminCell.style.transition = 'background-color 0.5s ease';
                adminCell.style.backgroundColor = isAdmin ? '#d4edda' : '#f8d7da';
            }
        });

        // Supprimer une ligne
        usersTable.addEventListener('click', (event) => {
            if (event.target.closest('.remove-user')) {
                event.target.closest('tr').remove();
            }
        });
    });
</script>

<style>
    .admin-cell {
        transition: background-color 0.5s ease;
    }
</style>
<main class="container my-auto">
    <div class="form-container pt-5 pb-5">
        <div class="card shadow">
            <div class="card-body">
                <h2 class="card-title text-center mb-4">Créer un compte</h2>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($error); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="prenom" class="form-label">Prénom : <span class="fw-bold">*</span></label>
                        <input type="text" name="prenom" id="prenom" class="form-control" placeholder="ex : Jean" value="<?php echo isset($_POST['prenom']) ? htmlspecialchars($_POST['prenom']) : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom : <span class="fw-bold">*</span></label>
                        <input type="text" name="nom" id="nom" class="form-control" placeholder="ex : Dupond" value="<?php echo isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email : <span class="fw-bold">*</span></label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="ex : dupondjean@gmail.com" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe : <span class="fw-bold">*</span></label>
                        <!-- info box -->
                        <span class="text-end">
                            <button type="button" class="btn mb-1" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="black" class="bi bi-info-circle" viewBox="0 0 16 16">
                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                    <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0"/>
                                </svg>
                            </button>
                        </span>
                        <div class="modal fade text-black" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                             aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-light">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Caractéristiques du mot de passe</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body bg-white text-start">
                                        <ul>
                                            <li>
                                                Votre mot de passe doit contenir au moins 8 caractères
                                            </li>
                                            <li>
                                                Il doit contenir au minimum :
                                                <ul>
                                                    <li>une minuscule</li>
                                                    <li>une majuscule</li>
                                                    <li>un chiffre</li>
                                                    <li>un caractère spécial</li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="password" name="password" id="password" class="form-control" placeholder="ex : secret" required>
                    </div>

                    <div class="mb-3">
                        <label for="passwordconf" class="form-label">Confirmation du mot de passe : <span class="fw-bold">*</span></label>
                        <input type="password" name="passwordconf" id="passwordconf" class="form-control" placeholder="ex : secret" required>
                    </div>

                    <div class="mb-3">
                        <p><span class="fw-bold">*</span> Champs obligatoires</p>
                    </div>

                    <div class="d-grid pt-3">
                        <button type="submit" class="btn btn-primary">S'inscrire</button>
                    </div>
                </form>
                <div class="pt-3">
                    <p><span class="me-1">Vous possédez déjà un compte ?</span><a href="/connexion" class="link-underline-info text-black fw-semibold mt-5">Connectez vous !</a></p>
                </div>
            </div>
        </div>
    </div>
</main>
<?php
declare(strict_types=1);

namespace iutnc\deefy\action;

use iutnc\deefy\exception\AuthnException;
use iutnc\deefy\auth\AuthnProvider;

class AddUserAction extends Action {
    public function get():string {
        return <<<HTML
        <div class="container my-4">
            <div class="card p-4 shadow-sm">
                <h2 class="text-center mb-4">Inscription</h2>
                <form method="post" action="?action=add-user">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required placeholder="Entrez votre email">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" id="password" name="password" required placeholder="Entrez votre mot de passe">
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirmez le mot de passe</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required placeholder="Confirmez votre mot de passe">
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-success w-100">S'inscrire</button>
                    </div>
                </form>
            </div>
        </div>
        HTML;
    }

    protected function post():string {
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');
        $passwordConfirmation = htmlspecialchars($_POST['password_confirmation'], ENT_QUOTES, 'UTF-8');
        try {
            AuthnProvider::register($email, $password, $passwordConfirmation);
            return "<div class='container my-4'>
                        <div class='alert alert-success text-center fw-bold' role='alert'>
                            Votre inscription a bien été enregistrée
                        </div>
                    </div>";
        } catch (AuthnException $e) {
            return "Erreur d'inscription : " . $e->getMessage();
        }
    }
}
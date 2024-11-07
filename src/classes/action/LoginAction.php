<?php
declare(strict_types=1);

namespace iutnc\deefy\action;

use iutnc\deefy\exception\AuthnException;
use iutnc\deefy\auth\AuthnProvider;

class LoginAction extends Action {

    public function get():string {
        return <<<HTML
        <div class="container my-4">
            <div class="card p-4 shadow-sm">
                <h2 class="text-center mb-4">Connexion</h2>
                <form method="POST" action="?action=sign-in">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required placeholder="Entrez votre email">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" id="password" name="password" required placeholder="Entrez votre mot de passe">
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary w-100">Se Connecter</button>
                    </div>
                </form>
            </div>
        </div>
        HTML;
    }

    protected function post():string {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];
        try {
            AuthnProvider::signin($email, $password);
            return "<div class='container d-flex justify-content-center align-items-top vh-100'>
                        <div class='text-center'>
                            <h1 class='display-4 text-success fw-bold'>Connexion réussie</h1>
                        </div>
                    </div>";
        } catch (AuthnException $e) {
            return "Erreur de login : " . $e->getMessage();
        }
    }
}

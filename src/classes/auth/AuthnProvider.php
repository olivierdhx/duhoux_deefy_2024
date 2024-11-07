<?php
declare(strict_types=1);
namespace iutnc\deefy\auth;

use iutnc\deefy\exception\AuthnException;
use iutnc\deefy\repository\DeefyRepository;

class AuthnProvider {

    public static function signin(string $email, string $mdp) {
        $repository = DeefyRepository::getInstance();
        $user = $repository->findInfos($email);
        if (!$user) {
            throw new AuthnException("Email invalide");
        }
        if (!password_verify($mdp, $user->passwd)) {
            throw new AuthnException("mdp invalide");
        }

        $_SESSION['user'] = [
            'id' => $user->id,
            'email' => $user->email,
            'role' => $user->role
        ];
    }

    public static function getSignedInUser():array {
        if (!isset($_SESSION['user'])) {
            throw new AuthnException("Pas connecté.");
        }
        return $_SESSION['user'];
    }

    public static function register(string $email, string $mdp, string $mdpConfirmation) {
        if (strlen($password) < 10) {
            throw new AuthnException("Le mot de passe doit faire au moins 10 caractères.");
        }
        if ($mdp !== $mdpConfirmation) {
            throw new AuthnException("Mots de passes différents");
        }
        $repository = DeefyRepository::getInstance();        
        $existingUser = $repository->findInfos($email);

        if ($existingUser) {
            throw new AuthnException("Cet email est déjà utilisé");
        }
        $hash = password_hash($password, PASSWORD_BCRYPT);

        $repo->addUser($email, $hash, 1);
    } 
}

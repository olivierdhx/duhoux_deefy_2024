<?php
declare(strict_types=1);

namespace iutnc\deefy\auth;

use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\exception\AuthnException;
class Authz {

    public static function checkRole(int $role):void {
        $user = AuthnProvider::getSignedInUser();
        if ($user['role'] !== $role) {
            throw new AuthnException("Accès refusé");
        }
    }

    public static function checkPlaylistOwner(int $playlistId):void {
        $user = AuthnProvider::getSignedInUser();
        $repo = DeefyRepository::getInstance();

        $ownerId = $repo->findPlaylistOwner($playlistId);
        if ($user['id'] != $ownerId && $user['role'] != 100) {
            throw new AuthnException("Vous n'avez pas la permission de voir cette playlist");
        }
    }
}

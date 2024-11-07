<?php

namespace iutnc\deefy\action;

use iutnc\deefy\audio\lists\Playlist;
use iutnc\deefy\render\AudioListRenderer;
use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\auth\Authz;
use iutnc\deefy\auth\AuthnProvider;
use iutnc\deefy\exception\AuthnException;

class DisplayPlaylistAction extends Action {
    
    public function execute():string {
        $id = $_GET['id'] ?? null;
        $repo = DeefyRepository::getInstance();
    
        try {
            $user = AuthnProvider::getSignedInUser();
            if ($id) {
                Authz::checkPlaylistOwner($id);
                $playlist = $repo->findPlaylistById($id);
    
                if ($playlist === null) {
                    return "<div class='container my-4'>
                                <p class='alert alert-warning text-center fw-bold'>Aucune playlist trouvée.</p>
                            </div>";
                }

                $_SESSION['playlist'] = serialize($playlist);
                $renderer = new AudioListRenderer($playlist);
                $playlistHtml = $renderer->render(1);
                return $playlistHtml . '<div class="container my-4 text-center">
                                            <a href="?action=add-track" class="btn btn-success">Ajouter une musique</a>
                                        </div>';
            } else {
                $playlists = $repo->findAllAccessiblePlaylists($user['id'], $user['role']);
                if (empty($playlists)) {
                    return "<p>Aucune playlist accessible.</p>";
                }

                $html = "<div class='container my-4 w-50'>
                            <h2 class='text-center mb-4'>Playlists Disponibles</h2>
                            <ul class='list-group'>";

                foreach ($playlists as $pl) {
                    $html = $html . "
                    <li class='list-group-item'>
                        <a href='?action=displayPlaylist&id={$pl['id']}' class='text-decoration-none text-dark fw-bold'>{$pl['nom']}</a>
                    </li>";
                }
                return $html . "</ul>\n</div>";
            }
        } catch (AuthnException $e) {
            return "<p>" . $e->getMessage() . "</p>";
        }
    }
}
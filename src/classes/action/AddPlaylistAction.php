<?php
declare(strict_types=1);

namespace iutnc\deefy\action;

use iutnc\deefy\audio\lists\Playlist;
use iutnc\deefy\render\AudioListRenderer;
use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\auth\AuthnProvider;

class AddPlaylistAction extends Action {
    protected function get():string {
        return <<<HTML
        <p></p>
        <div class="container my-4">
            <div class="card p-4 shadow-sm">
                <h2 class="text-center mb-4">Créer une Playlist</h2>
                <form method="post" action="?action=add-playlist">
                    <div class="mb-3">
                        <label for="playlist-name" class="form-label">Nom de la playlist</label>
                        <input type="text" class="form-control" id="playlist-name" name="playlist_name" required placeholder="Entrez le nom de la playlist">
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary w-100">Créer la playlist</button>
                    </div>
                </form>
            </div>
        </div>
        HTML;
    }

    protected function post():string {
        $nomPlaylist = filter_var($_POST['playlist_name'], FILTER_SANITIZE_SPECIAL_CHARS);
        $user = AuthnProvider::getSignedInUser();
        $repo = DeefyRepository::getInstance();
        $repo->addPlaylist($user['id'], $nomPlaylist);

        $playlist = new Playlist($nomPlaylist);

        $_SESSION['playlist'] = serialize($playlist);

        $renderer = new AudioListRenderer($playlist);
        $playlist_html = $renderer->render(1);

        return $playlist_html . '<a href="?action=add-track">Ajouter une piste</a>';
    }
}

<?php
declare(strict_types=1);

namespace iutnc\deefy\action;

use iutnc\deefy\audio\lists\Playlist;
use iutnc\deefy\audio\tracks\PodcastTrack;
use iutnc\deefy\render\AudioListRenderer;
use iutnc\deefy\repository\DeefyRepository;

class AddPodcastTrackAction extends Action{
    public function get():string {
        return <<<HTML
        <div class="container my-4">
            <div class="card p-4 shadow-sm">
                <h2 class="text-center mb-4">Ajouter une musique</h2>
                <form method="post" action="?action=add-track" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="track-name" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="track-name" name="track_name" required placeholder="Entrez le nom de la musique">
                    </div>
                    <div class="mb-3">
                        <label for="track-author" class="form-label">Auteur</label>
                        <input type="text" class="form-control" id="track-author" name="track_author" required placeholder="Entrez le nom de l'auteur">
                    </div>
                    <div class="mb-3">
                        <label for="track-date" class="form-label">Date</label>
                        <input type="text" class="form-control" id="track-date" name="track_date" required placeholder="Entrez la date">
                    </div>
                    <div class="mb-3">
                        <label for="track-genre" class="form-label">Genre</label>
                        <input type="text" class="form-control" id="track-genre" name="track_genre" required placeholder="Entrez le genre">
                    </div>
                    <div class="mb-3">
                        <label for="track-file" class="form-label">Fichier audio (MP3)</label>
                        <input type="file" class="form-control" id="track-file" name="track_file" accept=".mp3" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary w-100">Créer le track</button>
                    </div>
                </form>
            </div>
        </div>
        HTML;
    }

    protected function post(): string
    {
        $track_name = htmlspecialchars(filter_var($_POST['track_name'], FILTER_SANITIZE_SPECIAL_CHARS), ENT_QUOTES, 'UTF-8');
        $track_author = htmlspecialchars(filter_var($_POST['track_author'], FILTER_SANITIZE_SPECIAL_CHARS), ENT_QUOTES, 'UTF-8');
        $track_date = htmlspecialchars(filter_var($_POST['track_date'], FILTER_SANITIZE_SPECIAL_CHARS), ENT_QUOTES, 'UTF-8');
        $track_genre = htmlspecialchars(filter_var($_POST['track_genre'], FILTER_SANITIZE_SPECIAL_CHARS), ENT_QUOTES, 'UTF-8');

        $destination_dir = realpath(__DIR__ . '/../../../audio') . '/';
        $nom_random = uniqid('track_', true) . '.mp3'; // normalement ça donne un nom unique donc pas de probleme
        $destination_path = $destination_dir . $nom_random;

        if (move_uploaded_file($_FILES['track_file']['tmp_name'], $destination_path)) {
            $chemin_audio = 'audio/' . $nom_random;

            $podcast_track = new PodcastTrack($track_name, $chemin_audio, 100);
            $podcast_track->setAuteur($track_author);
            $podcast_track->setDate($track_date);
            $podcast_track->setGenre($track_genre);

            if ( isset($_SESSION['playlist']) && is_string($_SESSION['playlist']) ) {
                $PL = unserialize($_SESSION['playlist']);
            } else {
                $PL = new Playlist("Playlist par défaut");
            }
            $PL->ajouterPiste($podcast_track);

            if (is_numeric($track_date)) {
                $track_date = intval($track_date); 
            } 
            else {$track_date = 0;}

            $trackData = [
                'titre' => $track_name,
                'genre' => $track_genre,
                'duree' => 100,  
                'filename' => $chemin_audio,
                'type' => 'A',
                'artiste_album' => $track_author,
                'titre_album' => $PL->nom,
                'annee_album' => $track_date
            ];

            $repo = DeefyRepository::getInstance();
            $playlistId = $repo->findPlaylistIdByName($PL->nom);
            $repo->addTrack($trackData, $playlistId, count($PL->pistes));

            $renderer = new AudioListRenderer($PL);
            $_SESSION['playlist'] = serialize($PL);
            $playlist_html = $renderer->render(1);

            return $playlist_html . '<a href="?action=add-track">Ajouter encore une piste</a>';
        } else {
            return "Erreur : Le fichier n'a pas pu être déplacé.";
        }
    }
}
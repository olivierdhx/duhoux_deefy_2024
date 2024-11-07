<?php
declare(strict_types=1);

namespace iutnc\deefy\dispatch;

use iutnc\deefy\action\DefaultAction;
use iutnc\deefy\action\DisplayPlaylistAction;
use iutnc\deefy\action\AddPlaylistAction;
use iutnc\deefy\action\AddPodcastTrackAction;
use iutnc\deefy\action\AddUserAction;
use iutnc\deefy\action\LoginAction;
use iutnc\deefy\action\LogoutAction;
class Dispatcher {

    private string $action;
    public function __construct(string $a) {
        $this->action = $a;
    }

    public function run():void {
        $html = '';
        switch ($this->action) {
            case 'default':
                $actionInstance = new DefaultAction();
                $html = $actionInstance->execute();
                break;
            case 'displayPlaylist':
                $actionInstance = new DisplayPlaylistAction();
                $html = $actionInstance->execute();
                break;

            case 'add-playlist':
                $actionInstance = new AddPlaylistAction();
                $html = $actionInstance->execute();
                break;

            case 'add-track':
                $actionInstance = new AddPodcastTrackAction();
                $html = $actionInstance->execute();
                break;

            case 'add-user': 
                $actionInstance = new AddUserAction();
                $html = $actionInstance->execute();
                break;
            case 'sign-in':
                $actionInstance = new LoginAction();
                $html = $actionInstance->execute();
                break;
            case 'logout':
                $actionInstance = new LogoutAction();
                $html = $actionInstance->execute();
                break;
            default:
                $actionInstance = new DefaultAction();
                $html = $actionInstance->execute();
                break;
        }
        $this->renderPage($html);
    }

    private function renderPage(string $html): void {
        $connected = isset($_SESSION['user']);

        $nav = <<<HTML
        <div class="container my-4">
            <div class="bg-info text-white rounded p-3">
                <div class="row text-center">
                    <div class="col border-end border-light">
                        <a href="?action=default" class="text-white text-decoration-none fw-bold fs-5">Accueil</a>
                    </div>
        HTML;

        if ($connected) {
            $nav = $nav . <<<HTML
                    <div class="col border-end border-light">
                        <a href="?action=add-playlist" class="text-white text-decoration-none fw-bold fs-5">Créer une Playlist</a>
                    </div>
                    <div class="col border-end border-light">
                        <a href="?action=displayPlaylist" class="text-white text-decoration-none fw-bold fs-5">Voir les Playlists</a>
                    </div>
                    <div class="col">
                        <a href="?action=logout" class="text-white text-decoration-none fw-bold fs-5">Se Déconnecter</a>
                    </div>
            HTML;
        } else {
            $nav = $nav . <<<HTML
                    <div class="col border-end border-light">
                        <a href="?action=add-user" class="text-white text-decoration-none fw-bold fs-5">Inscription</a>
                    </div>
                    <div class="col">
                        <a href="?action=sign-in" class="text-white text-decoration-none fw-bold fs-5">Connexion</a>
                    </div>
                </div>
            HTML;
        }
        $nav = $nav . "\n</div></div></div>";
        
        echo <<<HTML
        <!DOCTYPE html>
        <html lang='fr'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
            <title>Deefy</title>
        </head>
        <body>
            $nav
            <main>
                <p></p> <!-- espace -->
                $html
            </main>
        </body>
        </html>
        HTML;
    }
    
    
}

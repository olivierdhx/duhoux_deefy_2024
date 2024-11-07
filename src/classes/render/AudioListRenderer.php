<?php
declare(strict_types=1);

namespace iutnc\deefy\render;

use iutnc\deefy\audio\lists as lists;
use iutnc\deefy\audio\tracks as tracks;
class AudioListRenderer implements Renderer {

    private lists\AudioList $audioList;

    public function __construct(lists\AudioList $audioList) {
        $this->audioList = $audioList;
    }


    public function render(int $type):string {
        return $this->afficher();
    }


    private function afficher() {
        $html = "<div class='container my-4'>
                    <div class='card p-4 shadow-sm'>";
        $html .= "<h3 class='card-title text-primary'>{$this->audioList->nom} :</h3>";
        
        foreach ($this->audioList->pistes as $piste) {
            if ($piste instanceof tracks\AlbumTrack) {
                $renderer = new AlbumTrackRenderer($piste);
            } elseif ($piste instanceof tracks\PodcastTrack) {
                $renderer = new PodcastRenderer($piste);
            }
            $html .= $renderer->render(Renderer::COMPACT);
        }

        $html .= "<p class='mt-3'><strong>Nombre de pistes :</strong> {$this->audioList->nbPistes}</p>";
        $html .= "<p><strong>Durée totale :</strong> {$this->audioList->dureeTotale} secondes</p>";
        $html .= "</div>\n</div>";
        return $html;
    }
}
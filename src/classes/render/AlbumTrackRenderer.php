<?php
declare(strict_types=1);

namespace iutnc\deefy\render;
use iutnc\deefy\audio\tracks as tracks;

class AlbumTrackRenderer extends AudioTrackRenderer {
    private tracks\AlbumTrack $albumTrack;

    public function __construct(tracks\AlbumTrack $album) {
        $this->albumTrack = $album;
    }


    protected function renderLong():string {
        return "
        <div>
            <h3>{$this->albumTrack->titre} - {$this->albumTrack->artiste}</h3>
            <p><strong>Album :</strong> {$this->albumTrack->album}</p>
            <p><strong>Année :</strong> {$this->albumTrack->annee}</p>
            <p><strong>Numéro de piste :</strong> {$this->albumTrack->numero_piste}</p>
            <p><strong>Genre :</strong> {$this->albumTrack->genre}</p>
            <p><strong>Durée :</strong> {$this->albumTrack->duree} secondes</p>
            <audio controls>
                <source src='{$this->albumTrack->nomFichier}' type='audio/mpeg'>
                Votre navigateur ne supporte pas la balise audio.
            </audio> 
        </div>
        ";
    }

    protected function renderCompact():string {
        return "
        <div class='my-3'>
            <h4 class='fw-bold'>{$this->albumTrack->titre} - {$this->albumTrack->artiste}</h4>
            <audio controls class='w-100 mt-2'>
                <source src='{$this->albumTrack->nomFichier}' type='audio/mpeg'>
                Votre navigateur ne supporte pas la balise audio.
            </audio>
        </div>
        ";
    }
}
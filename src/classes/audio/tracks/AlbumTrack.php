<?php
declare(strict_types=1);

namespace iutnc\deefy\audio\tracks;

class AlbumTrack extends AudioTrack {

    protected string $artiste;
    protected string $album;
    protected int $annee;
    protected int $numero_piste;
    protected string $genre;

    public function __construct(string $titre, string $chemin_fichier, string $album, int $numero_piste, int $duree = 0) {
        parent::__construct($titre, $chemin_fichier, $duree);
        $this->album = $album;
        $this->numero_piste = $numero_piste;
        $this->annee = 0;
        $this->genre = "Inconnu";
        $this->artiste = "Inconnu";
        
    }

    public function setArtiste(string $artiste):void {
        $this->artiste = $artiste;
    }

    public function setAnnee(int $annee):void {
        $this->annee = $annee;
    }

    public function setGenre(string $genre):void {
        $this->genre = $genre;
    }
}

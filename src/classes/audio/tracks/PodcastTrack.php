<?php
declare(strict_types=1);

namespace iutnc\deefy\audio\tracks;

class PodcastTrack extends AudioTrack {
    protected string $auteur;
    protected string $date;
    protected string $genre;

    public function __construct(string $titre, string $chemin, int $duree = 0) {
        parent::__construct($titre, $chemin, $duree);
        $this->auteur = "Inconnu";
        $this->date = "Inconnue";
        $this->genre = "Inconnu";
    }

    public function setAuteur(string $auteur):void {
        $this->auteur = $auteur;
    }

    public function setDate(string $date):void {
        $this->date = $date;
    }

    public function setGenre(string $genre):void {
        $this->genre = $genre;
    }
}

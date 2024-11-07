<?php
declare(strict_types=1);

namespace iutnc\deefy\audio\lists;

class Album extends AudioList {
    private string $artiste;
    private string $dateSortie;

    public function __construct(string $nom, array $pistes) {
        parent::__construct($nom, $pistes);
    }

    public function setArtiste(string $a):void {
        $this->artiste = $a;
    }

    public function setDateSortie(string $dateS):void {
        $this->dateSortie = $dateS;
    }
}
<?php
declare(strict_types=1);

namespace iutnc\deefy\audio\lists;

class Playlist extends AudioList
{
    public function ajouterPiste($piste):void {
        $this->pistes[] = $piste;
        $this->dureeTotale += $piste->duree ?? 0;
        $this->nombrePistes++;
    }

    public function supprimerPiste(int $index):void {
        unset($this->pistes[$index]);
    }

    public function ajouterListePistes(array $pistes):void {
        $this->pistes = array_unique(array_merge($this->pistes, $pistes));
        $this->nombrePistes = count($this->pistes);
        foreach ($this->pistes as $piste) {
            $this->dureeTotale += $piste->duree;
        }
    }
}
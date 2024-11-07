<?php
declare(strict_types=1);

namespace iutnc\deefy\audio\lists;

use iutnc\deefy\exception\InvalidPropertyNameException;

class AudioList {
    protected string $nom;
    protected int $nbPistes = 0;
    protected array $pistes = [];
    protected int $dureeTotale = 0;

    public function __construct(string $nom, array $pistes = []) {
        $this->nom = $nom;
        $this->nbPistes = count($pistes);
        $this->dureeTotale = $this->calculerDureeTotale();
        $this->pistes = $pistes;
    }

    private function calculerDureeTotale():int {
        $duree = 0;
        foreach ($this->pistes as $piste) {
            $duree += $piste->duree;
        }
        return $duree;
    }

    public function __get($name) {
        if(property_exists($this, $name)) {
            return $this->$name;
        } else {
            throw new InvalidPropertyNameException("$name : nom de propriété invalide");
        }
    }
}
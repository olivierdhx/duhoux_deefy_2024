<?php
declare(strict_types=1);

namespace iutnc\deefy\audio\tracks;

use iutnc\deefy\exception\InvalidPropertyNameException;
use iutnc\deefy\exception\InvalidPropertyValueException;

abstract class AudioTrack {
    protected string $titre;
    protected int $duree;
    protected string $nomFichier;

    public function __construct(string $titre, string $filePath, int $duree) {
        $this->titre = $titre;
        $this->nomFichier = $filePath;
        $this->setDuree($duree);
    }
    
    public function setDuree(int $d):void {
        if ($d > 0) {
            $this->duree = $d;
        } else {
            throw new InvalidPropertyValueException("La durée ($this->duree) est inférieure à 0");
        }
    }

    public function __get(string $name):mixed {
        if (property_exists($this, $name)) {
            return $this->$name;
        } else {
            throw new InvalidPropertyNameException("$name : nom de propriété invalide");
        }
    }

    public function __toString():string {
        return json_encode(get_object_vars($this), JSON_PRETTY_PRINT);
    }
}

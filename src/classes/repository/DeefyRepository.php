<?php
declare(strict_types=1);

namespace iutnc\deefy\repository;

use Exception;
use PDO;
use iutnc\deefy\audio\tracks\AlbumTrack;
use iutnc\deefy\audio\lists\Playlist;


class DeefyRepository {

    private PDO $pdo;
    private static ?DeefyRepository $instance = null;
    private static array $config = [];

    private function __construct() {
        try {
            $this->pdo = new PDO(
                self::$config['dsn'],
                self::$config['username'],
                self::$config['password']
            );
        } catch (PDOException $e) {
            echo 'Probleme de connexion à PDO : ' . htmlspecialchars($e->getMessage());
            exit;
        }
    }

    public static function getInstance():DeefyRepository {
        if (is_null(self::$instance)) {
            if (empty(self::$config)) {
                throw new \Exception("Database configuration not set. Use setConfig() before getInstance().");
            }
            self::$instance = new DeefyRepository(self::$config);
        }
        return self::$instance;
    }

    public static function setConfig(string $f):void {
        $conf = parse_ini_file($f);

        if ($conf === false) {
            throw new \Exception("Impossible de lire le fichier de configuration");
        }
        self::$config = $conf;
    }

    public function findInfos(string $email) {
        $stmt = $this->pdo->prepare("SELECT * FROM User WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->fetchObject();
    }

    public function addUser(string $email, string $hashedPassword, int $role):void {
        $stmt = $this->pdo->prepare("INSERT INTO User (email, passwd, role) VALUES (:email, :passwd, :role)");

        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':passwd', $hashedPassword);
        $stmt->bindParam(':role', $role);

        $stmt->execute();
    }


    public function findPlaylistById(int $id): ?Playlist {
        $stmt = $this->pdo->prepare("SELECT * FROM playlist WHERE id = :id");

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetchObject();
    
        if (!$data) {
            return null; 
        }

        $tracks = $this->ListTracks($id);
        return new Playlist($data->nom, $tracks); 
    }
    
    private function ListTracks(int $playListId):array {
        $stmt = $this->pdo->prepare("
            SELECT t.id, t.titre, t.filename, t.titre_album, pt.no_piste_dans_liste, t.duree, t.annee_album, t.genre, t.artiste_album
            FROM track t
            JOIN playlist2track pt ON t.id = pt.id_track
            WHERE pt.id_pl = :playlist_id
        ");

        $stmt->bindParam(':playlist_id', $playListId, PDO::PARAM_INT);
        $stmt->execute();
    
        $tracks = [];

        while ($trackData = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $track = new AlbumTrack(
                $trackData['titre'],
                $trackData['filename'],
                $trackData['titre_album'],
                $trackData['no_piste_dans_liste'],
                $trackData['duree'] 
            );
            $track->setArtiste($trackData['artiste_album'] ?? "Inconnu");
            $track->setAnnee($trackData['annee_album'] ?? 0);
            $track->setGenre($trackData['genre'] ?? "Inconnu");
    
            $tracks[] = $track;
        }
        return $tracks; 
    }


    public function findPlaylistOwner(int $playListId):int {
        $stmt = $this->pdo->prepare("SELECT id_user FROM user2playlist WHERE id_pl = :playlistId");

        $stmt->bindParam(':playlistId', $playlistId, PDO::PARAM_INT);
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function findPlaylistIdByName(string $playlistName): ?int {
        try {
            $stmt = $this->pdo->prepare('SELECT id FROM playlist WHERE nom = :nom');
            $stmt->bindParam(':nom', $playlistName);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result ? (int)$result['id'] : null;
        } catch (Exception $e) {
            return null;
        }
    }

    public function addPlaylist(int $userId, string $nomPlaylist):int {
        
        $stmt = $this->pdo->prepare("INSERT INTO playlist (nom) VALUES (:nom)");
        $stmt->bindParam(':nom', $nomPlaylist);
        $stmt->execute();

        $playlistId = (int)$this->pdo->lastInsertId();

        $stmt = $this->pdo->prepare("INSERT INTO user2playlist (id_user, id_pl) VALUES (:id_user, :id_pl)");
        $stmt->bindParam(':id_user', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':id_pl', $playlistId, PDO::PARAM_INT);
        $stmt->execute();

        return $playlistId;
    }

    public function addTrack(array $trackData, int $playlistId, int $numeroPiste):int {
        $stmt = $this->pdo->prepare("
            INSERT INTO track (titre, genre, duree, filename, type, artiste_album, titre_album ,annee_album)
            VALUES (:titre, :genre, :duree, :filename, :type, :artiste_album,:titre_album, :annee_album)");

        $stmt->execute($trackData);
        $trackId = (int)$this->pdo->lastInsertId();

        $stmt = $this->pdo->prepare("
            INSERT INTO playlist2track (id_pl, id_track, no_piste_dans_liste)
            VALUES (:playlistId, :trackId, :numeroPiste)
        ");

        $stmt->execute([
            'playlistId' => $playlistId,
            'trackId' => $trackId,
            'numeroPiste' => $numeroPiste
        ]);

        return $trackId;
    }
    
    public function findAllAccessiblePlaylists(int $userId, int $role): array {
        if ($role === 100) {
            $stmt = $this->pdo->query("SELECT p.id, p.nom FROM playlist p");
        } else {
            $stmt = $this->pdo->prepare("
                SELECT p.id, p.nom 
                FROM playlist p
                JOIN user2playlist u2p ON p.id = u2p.id_pl
                WHERE u2p.id_user = :userId
            ");
            
            $stmt->execute(['userId' => $userId]);
        }
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
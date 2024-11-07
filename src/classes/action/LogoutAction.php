<?php
declare(strict_types=1);

namespace iutnc\deefy\action;

class LogoutAction extends Action {
    
    public function execute():string {
        $_SESSION = [];
        return "<div class='container d-flex justify-content-center align-items-top vh-100'>
                    <div class='text-center'>
                        <h1 class='display-4 text-success fw-bold'>Déconnexion terminée</h1>
                    </div>
                </div>";
    }
}

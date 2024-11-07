<?php
declare(strict_types=1);

namespace iutnc\deefy\action;

class DefaultAction extends Action {
    
    protected function get():string {   
        return "<div class='container my-5'>
                    <header class='text-center bg-primary text-white py-5 rounded shadow-sm'>
                        <h1 class='display-4 fw-bold'>Bienvenue sur Deefy</h1>
                    </header>
                </div>";
    }
}

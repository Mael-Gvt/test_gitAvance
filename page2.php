<?php
session_start();

if (isset($_SESSION['prenom'])) {
    echo 'Bonjour' . htmlspecialchars($_SESSION['prenom'], ENT_QUOTES, 'UTF-8');
    } else {
        echo 'Bonjour visiteur';
    }
<?php

session_start();

// ajouter des éléments
$_SESSION['theme'];
$_SESSION['panier'] = ['pomme' => 2, 'lait' => 1];

// retirer un élément
unset($_SESSION['panier']['pomme']);

// supprimer toutes les variables de la session
$_SESSION = [];
?>
<?php

session_start();

require_once __DIR__ . '/database.php';

// créer une session pour le panier
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

// ajouter le produit dans le panier
if (isset($_POST['ajouter'])) {
    $id = (int) $_POST['id'];

    // chercher le produit dans la DB
    $stmt = $pdo->prepare('SELECT id, nom, prix FROM produit
    WHERE id = ?'
    );
    $stmt->execute([$id]);
    $produit = $stmt->fetch(PDO::FETCH_ASSOC);

    // vérifier si le produit existe
    if ($produit) {
        // s'il est déjà dans le panier
        if (isset($_SESSION['panier'][$id])) {
            $_SESSION ['panier'][$id]['quantite']++;
        } else {
            // s'il n'est pas encore dans le panier
            $_SESSION ['panier'][$id]=[
                'nom' => $produit['nom'], 'prix' => $produit['prix'], 'quantite' => 1
            ];
        }
    }
}

// supprimer le produit du panier (sans prendre en compte la qte)
if (isset($_POST['supprimer'])) {
    $id = (int) $_POST['id'];
    unset($_SESSION['panier'][$id]);
}

// diminuer la quantité du produit de 1
if (isset($_POST['retirer'])) {
    $id = (int) $_POST['id'];
    if (isset($_SESSION['panier'][$id])) {
        $_SESSION ['panier'][$id]['quantite']--;

        if ($_SESSION ['panier'][$id]['quantite'] <= 0) {
            unset($_SESSION['panier'][$id]);
        }
    }
}

// supprimer tout le panier
if (isset($_POST['vider'])) {
    $_SESSION['panier'] = [];
}

// récuperer les produits de la DB
$produits = $pdo->query(
    'SELECT id, nom, prix FROM produit'
)->fetchAll(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Boutique</h1>
    <?php foreach($produits as $produit):?>
        <p><?=  htmlspecialchars($produit['nom']) ?></p>
        <p>Prix : <?=  htmlspecialchars($produit['prix']) ?>€</p>

        <form method="POST">
            <input type="hidden" name="id" value="<?= $produit['id'] ?>">
            <button type="submit" name="ajouter">Ajouter</button>
        </form>
        <?php endforeach; ?>

        <h2>Panier</h2>
        <?php if (empty($_SESSION['panier'])): ?> 
            <p>Votre panier est vide</p> 
        <?php else: ?>
            <?php $totalPanier = 0; ?>
            <?php foreach($_SESSION['panier'] as $id => $produit): ?>
                <?php $totalProduit = $produit['prix'] * $produit['quantite'];
                $totalPanier += $totalProduit; ?>
            <?php endforeach; ?>
            <p>Total : <?= $totalPanier ?> €</p>
         <?php endif; ?>
</body>
</html>
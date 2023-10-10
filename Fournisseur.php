<?php
session_start();

$message = '';

$host = 'localhost';
$db = 'driss';
$user = 'driss';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// Les fonctions saveData et getAllSuppliers restent inchangées

$suppliers = getAllSuppliers();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = saveData($_POST);
    
    if ($message === true) {
        $suppliers = getAllSuppliers();
    }
}
function saveData($data) {
    global $dsn, $user, $pass, $options;
    
    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
        $sql = "INSERT INTO fournisseur (fournisseur, devise, nom_contact, numero_telephone, entite, delai_livraison, acheteur, telephone) VALUES (:fournisseur, :devise, :nom_contact, :numero_telephone, :entite, :delai_livraison, :acheteur, :telephone)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':fournisseur' => $data['fournisseur'],
            ':devise' => $data['devise'],
            ':nom_contact' => $data['nom_contact'],
            ':numero_telephone' => $data['numero_telephone'],
            ':entite' => $data['entite'],
            ':delai_livraison' => $data['delai_livraison'],
            ':acheteur' => $data['acheteur'],
            ':telephone' => $data['telephone'],
        ]);
        return "Data has been saved successfully!";
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

function getAllSuppliers() {
    global $dsn, $user, $pass, $options;
    
    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
        $stmt = $pdo->query("SELECT * FROM fournisseur");
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fournisseur Page</title>
    <link rel="stylesheet" type="text/css" href="style/fournisseur.css">
<body>
<div>
        <h1>Ajouter un Fournisseur</h1>
        <nav class="navbar">
            <a href="index.html" class="navbar-link">SGLT</a>
            <a href="index.html" class="navbar-link">Home</a>
        </nav>
    </div>
        
    <form method="POST" action="fournisseur.php" id="fournisseurForm">
    <div class="form-field">
            <label class="label" for="fournisseur">Fournisseur:</label>
            <input class="input-field" type="text" id="fournisseur" name="fournisseur" value="<?php echo isset($_SESSION['last_fournisseur']) ? htmlspecialchars($_SESSION['last_fournisseur']) : ''; ?>" />

    </div>

            <div class="form-field">
                <label class="label" for="devise">Devise:</label>
                <input class="input-field" type="text" id="devise" name="devise" />
            </div>

            <div class="form-field">
                <label class="label" for="nom_contact">Nom du contact:</label>
                <input class="input-field" type="text" id="nom_contact" name="nom_contact" />
            </div>

            <div class="form-field">
                <label class="label" for="numero_telephone">Numéro de téléphone:</label>
                <input class="input-field" type="text" id="numero_telephone" name="numero_telephone" />
            </div>

            <div class="form-field">
                <label class="label" for="entite">Entité:</label>
                <input class="input-field" type="text" id="entite" name="entite" />
            </div>

            <div class="form-field">
                <label class="label" for="delai_livraison">Délai de livraison:</label>
                <input class="input-field" type="text" id="delai_livraison" name="delai_livraison" />
            </div>

            <div class="form-field">
                <label class="label" for="acheteur">Acheteur:</label>
                <input class="input-field" type="text" id="acheteur" name="acheteur" />
            </div>

            <div class="form-field">
                <label class="label" for="telephone">Téléphone:</label>
                <input class="input-field" type="text" id="telephone" name="telephone" />
            </div>

            <button class="button" type="submit">Enregistrer</button>
            <button type="button" id="showInfoBtn">Show Information</button>
            <button type="button" id="closeBtn" style="display:none;">Close</button>

    </form>
    
    <table id="suppliersTable" style="display:none;">
    <thead>
        <tr>
            <th>Fournisseur</th>
            <th>Devise</th>
            <th>Nom du Contact</th>
            <th>Numéro de Téléphone</th>
            <th>Entité</th>
            <th>Délai de Livraison</th>
            <th>Acheteur</th>
            <th>Téléphone</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($suppliers as $supplier): ?>
            <tr>
                <td><?php echo htmlspecialchars($supplier['fournisseur']); ?></td>
                <td><?php echo htmlspecialchars($supplier['devise']); ?></td>
                <td><?php echo htmlspecialchars($supplier['nom_contact']); ?></td>
                <td><?php echo htmlspecialchars($supplier['numero_telephone']); ?></td>
                <td><?php echo htmlspecialchars($supplier['entite']); ?></td>
                <td><?php echo htmlspecialchars($supplier['delai_livraison']); ?></td>
                <td><?php echo htmlspecialchars($supplier['acheteur']); ?></td>
                <td><?php echo htmlspecialchars($supplier['telephone']); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>
        document.getElementById('showInfoBtn').addEventListener('click', function() {
            document.getElementById('suppliersTable').style.display = 'table';
            document.getElementById('closeBtn').style.display = 'inline-block';
            this.style.display = 'none';
        });

        document.getElementById('closeBtn').addEventListener('click', function() {
            document.getElementById('suppliersTable').style.display = 'none';
            document.getElementById('showInfoBtn').style.display = 'inline-block';
            this.style.display = 'none';
        });
    </script>
</body>
</html>

<?php
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

try {
    $conn = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $sql = "INSERT INTO demande_intervention (materiel, description, entite, statut, service_maintenance, type, nature, date_evenement, demande_par, priorite, commentaire, documents) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $data = [
        $_POST['materiel'], 
        $_POST['description'], 
        $_POST['entite'],
        $_POST['statut'], 
        $_POST['service_maintenance'],
        $_POST['type'],
        $_POST['nature'],
        $_POST['date_evenement'],
        $_POST['demande_par'],
        $_POST['priorite'],
        $_POST['commentaire'],
        $_POST['documents']
    ];

    try {
        $stmt = $conn->prepare($sql);
        if($stmt->execute($data)){
            echo "Demande d'intervention enregistrée avec succès.";
        } else {
            echo "Erreur lors de l'enregistrement.";
        }
    } catch (PDOException $e) {
        echo "Erreur: " . $e->getMessage();
    }
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demande d'Intervention Page</title>
    <link rel="stylesheet" type="text/css" href="style/d_intervention.css">
</head>
<body>
<div>
        <h1>Demande Intervention</h1>
        <nav class="navbar">
            <a href="index.html" class="navbar-link">SGLT</a>
            <a href="index.html" class="navbar-link">Home</a>
        </nav>
        

  
        <form method="POST" action="demande-intervention.php" id="demande-interventionForm">
            
            <div class="form-field">
                <label for="materiel">Matériel:</label>
                <input type="text" id="materiel" name="materiel" />
            </div>
            
            <div class="form-field">
                <label for="description">Description:</label>
                <input type="text" id="description" name="description" />
            </div>

            <div class="form-field">
                <label for="entite">Entité:</label>
                <input type="text" id="entite" name="entite" />
            </div>

            <div class="form-field">
               <label class="label" for="statut">Statut:</label>
                   <select class="input-field" id="statut" name="statut" required>
                   <option value="          ">         </option>
                   <option value="en_service">En Service</option>
                   <option value="en_fin_de_vie">En fin de vie</option>
                   <option value="sortie">Sortie</option>
                </select>
            </div> 

            <div class="form-field">
               <label for="service_maintenance">Service Maintenance:</label>
               <select id="service_maintenance" name="service_maintenance" required>
                   <option value="service_maintenance_fixe">Service maintenance fixe</option>
                   <option value="service_maintenance_mobile">Service maintenance mobile</option>
                   <option value="service_maintenance_externe_fixe">Service maintenance externe fixe</option>
                   <option value="service_maintenance_externe_mobile">Service maintenance externe mobile</option>
               </select>
            </div>
            <div class="form-field">
                <label for="type">Type:</label>
                <input type="text" id="type" name="type" />
            </div>

            <div class="form-field">
                <label for="nature">Nature:</label>
                <input type="text" id="nature" name="nature" />
            </div>

            <div class="form-field">
                <label for="date_evenement">Date de l'événement:</label>
                <input type="date" id="date_evenement" name="date_evenement" />
            </div>

            <div class="form-field">
                <label for="demande_par">Demandé par:</label>
                <input type="text" id="demande_par" name="demande_par" />
            </div>

            <div class="form-field">
                <label for="priorite">Priorité:</label>
                <input type="text" id="priorite" name="priorite" />
            </div>

            <div class="form-field">
                <label for="commentaire">Commentaire:</label>
                <textarea id="commentaire" name="commentaire" rows="4" cols="50"></textarea>
            </div>

            <div class="form-field">
                <label for="documents">Documents:</label>
                <input type="text" id="documents" name="documents" />
            </div>

            <div class="form-field">
                <label for="photos">Photos:</label>
                <input type="file" id="photos" name="photos[]" multiple />
            </div>

            <button type="submit">Enregistrer</button>
        </form>
    </div>
</body>
</html>

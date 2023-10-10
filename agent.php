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

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    $message = 'Erreur de connexion : ' . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_agent = $_POST['id_agent'] ?? '';
    $agent = $_POST['agent'] ?? '';
    $service_maintenance = $_POST['service_maintenance'] ?? '';
    $entite = $_POST['entite'] ?? '';
    $specialite = $_POST['specialite'] ?? '';
    $superviseur = $_POST['superviseur'] ?? '';
    $date_embauche = $_POST['date_embauche'] ?? '';
    $classe = $_POST['classe'] ?? '';
    $adresse_email = $_POST['adresse_email'] ?? '';
    $date_fin_contrat = $_POST['date_fin_contrat'] ?? '';
    $numero_telephone = $_POST['numero_telephone'] ?? '';

    if (empty($id_agent) || empty($agent) || empty($service_maintenance) || empty($entite) || empty($specialite) || empty($superviseur) || empty($date_embauche) || empty($classe) || empty($adresse_email) || empty($date_fin_contrat) || empty($numero_telephone)) {
        $message = 'All fields are required.';
    } else {
        try {
            $stmt = $pdo->prepare('INSERT INTO agent (id_agent, agent, service_maintenance, entite, specialite, superviseur, date_embauche, classe, adresse_email, date_fin_contrat, numero_telephone) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute([$id_agent, $agent, $service_maintenance, $entite, $specialite, $superviseur, $date_embauche, $classe, $adresse_email, $date_fin_contrat, $numero_telephone]);
    
            $message = "L'agent a été ajouté avec succès.";
        } catch (PDOException $e) {
            $message = "Erreur lors de l'ajout de l'agent: " . $e->getMessage();
        }
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'getAgents') {
    try {
        $stmt = $pdo->query('SELECT * FROM agent');
        $agents = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($agents);
        exit;
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error fetching data']);
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Agent Page</title>
    <link rel="stylesheet" type="text/css" href="style/agent.css">
</head>
<body>
    <div>
        <h1> Ajouter un Agent</h1>
        <nav class="navbar">
            <a href="index.html" class="navbar-link">SGLT</a>
            <a href="index.html" class="navbar-link">Home</a>
        </nav>


    </nav>
    </div>
    <form class="form-container" action="" method="post">

        <div class="form-field">
                <label for="id_agent">ID Agent :</label>
                <input type="text" id="id_agent" name="id_agent" required />
            </div>
            
            <div class="form-field">
                <label for="agent">Agent:</label>
                <input type="text" id="agent" name="agent" />
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
                <label for="entite">Entité:</label>
                <input type="text" id="entite" name="entite" />
            </div>

            <div class="form-field">
                <label for="specialite">Spécialité:</label>
                <input type="text" id="specialite" name="specialite" />
            </div>

            <div class="form-field">
                <label for="superviseur">Superviseur:</label>
                <input type="text" id="superviseur" name="superviseur" />
            </div>

            <div class="form-field">
                <label for="date_embauche">Date d'embauche:</label>
                <input type="date" id="date_embauche" name="date_embauche" />
            </div>

            <div class="form-field">
                <label for="classe">Classe:</label>
                <input type="text" id="classe" name="classe" />
            </div>

            <div class="form-field">
                <label for="adresse_email">Adresse email:</label>
                <input type="email" id="adresse_email" name="adresse_email" />
            </div>

            <div class="form-field">
                <label for="date_fin_contrat">Date de fin de contrat:</label>
                <input type="date" id="date_fin_contrat" name="date_fin_contrat" />
            </div>
            

            <div class="form-field">
                <label for="numero_telephone">Numéro de téléphone :</label>
                <input type="tel" id="numero_telephone" name="numero_telephone" />
            </div>            
                

            <button type="submit">Enregistrer</button>
            <button type="button" onclick="displayData()">Afficher les données</button>
        </form>
        <div id="dataDisplayArea"></div>
        

<script>
function displayData() {
    fetch('agent.php?action=getAgents')
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            document.getElementById('dataDisplayArea').textContent = data.error;
            return;
        }
        let table = `<table border='1'><tr>`;
        for (let key in data[0]) {
            table += `<th>${key.replace('_', ' ')}</th>`;
        }
        table += `</tr>`;
        for (let i = 0; i < data.length; i++) {
            table += `<tr>`;
            for (let key in data[i]) {
                table += `<td>${data[i][key]}</td>`;
            }
            table += `</tr>`;
        }
        table += `</table><button onclick='closeTable()'>Close</button>`;
        document.getElementById('dataDisplayArea').innerHTML = table;
    })
    .catch(error => console.error('Error:', error));
}

function closeTable() {
    document.getElementById('dataDisplayArea').innerHTML = '';
}
</script>

    </div>
    
</body>
</html>

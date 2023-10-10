<?php
session_start();

    $message = '';

    // Database connection details
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

    function saveData($data) {
        global $dsn, $user, $pass, $options;
        $_SESSION['last_fournisseur'] = $_POST['fournisseur'];
        $conn = new PDO($dsn, $user, $pass, $options);
        $query = "INSERT INTO indices (code_indice, Code_dImplementation, libelle, financing_mode, montant_indice, fournisseur, libelle_fournisseur, entite, quantite, entree, sortie, date_debut_comptable, date_reception, date_fin_contrat) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);

        return $stmt->execute([
            $data['code_indice'],
            $data['Code_dImplementation'],
            $data['libelle'],
            $data['financing_mode'],
            $data['montant_indice'],
            $data['fournisseur'],
            $data['libelle_fournisseur'],
            $data['entite'],
            $data['quantite'],
            $data['entree'],
            $data['sortie'],
            $data['date_debut_comptable'],
            $data['date_reception'],
            $data['date_fin_contrat']
        ]);
    }   
    function checkCodeExists($code) {
        global $dsn, $user, $pass, $options;
    
        $conn = new PDO($dsn, $user, $pass, $options);
        $query = "SELECT * FROM indices WHERE code_indice = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$code]);
        return $stmt->fetch();
    }
    

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $_SESSION['indices_data'] = $_POST; // Saving indices data in session
    
        // Check if a record with this code_indice already exists
        if (checkCodeExists($_POST['code_indice'])) {
            $message = 'A record with this code_indice already exists!';
        } else {
            // If not, proceed to save the data
            $result = saveData($_POST);
            
            if ($result) {
                header('Location: fournisseur.php'); // Redirect to fournisseur.php page
                exit;
            } else {
                $message = 'Erreur lors de l\'enregistrement des données!';
            }
        }
    }
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['modifier'])) {
            $_SESSION['edit_mode'] = true;
            header('Location: fiche_materiel.php');
            exit;
        }
    }
    ?>

    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Indices Page</title>
    <link rel="stylesheet" href="style/indices.css">
</head>
<body>
<div>
    <h1>Indices</h1>
    <nav class="navbar">
        <a href="index.html" class="navbar-link">SGLT</a>
        <a href="index.html" class="navbar-link">Home</a>
        
    </nav>
</div>

<?php
if($message) {
    echo '<p style="color:red;">'. $message .'</p>';
}
?>
<form method="POST" action="indices.php" id="indicesForm">
<div class="form-field">
                <label class="label" for="code_indice ">code_indice :</label>
                <input type="text" name="code_indice" placeholder="Enter code indice">

                <div class="form-field">
                    <label class="label" for="Code_dImplementation">Code_dImplementation:</label>
                    <input class="input-field" type="text" id="Code_dImplementation" name="Code_dImplementation" value="<?php echo isset($_SESSION['last_Code_dImplementation']) ? htmlspecialchars($_SESSION['last_Code_dImplementation']) : ''; ?>" required />
                </div>

            <div class="form-field">
                <label class="label" for="libelle">Libellé:</label>
                <input class="input-field" type="text" id="libelle" name="libelle" />
            </div>

            <div class="form-field">
                <label class="label" for="financing_mode">Mode de financement:</label>
                <input class="input-field" type="number" step="1" id="financing_mode" name="financing_mode" />
            </div>

            <div class="form-field">
                <label class="label" for="montant_indice">Montant de l'indice:</label>
                <input class="input-field" type="text" id="montant_indice" name="montant_indice" />
            </div>

            <div class="form-field">
                <label class="label" for="fournisseur">Fournisseur:</label>
                <input class="input-field" type="text" id="fournisseur" name="fournisseur" />
            </div>

            <div class="form-field">
                <label class="label" for="libelle_fournisseur">Libellé fournisseur:</label>
                <input class="input-field" type="text" id="libelle_fournisseur" name="libelle_fournisseur" />
            </div>

            <div class="form-field">
                <label class="label" for="entite">Entité:</label>
                <input class="input-field" type="text" id="entite" name="entite" />
            </div>

            <div class="form-field">
                <label class="label" for="quantite">Quantité:</label>
                <input class="input-field" type="text" id="quantite" name="quantite" />
            </div>

            <div class="form-field">
                <label class="label" for="entree">Entrée:</label>
                <input class="input-field" type="text" id="entree" name="entree" />
            </div>

            <div class="form-field">
                <label class="label" for="sortie">Sortie:</label>
                <input class="input-field" type="text" id="sortie" name="sortie" />
            </div>

            <div class="form-field">
                    <label class="label" for="date_debut_comptable">Date début comptable:</label>
                    <input class="input-field" type="date" id="date_debut_comptable" name="date_debut_comptable" />
            </div>


            <div class="form-field">
                <label class="label" for="date_reception">Date de réception:</label>
                <input class="input-field" type="date" id="date_reception" name="date_reception" />
            </div>

            <div class="form-field">
                <label class="label" for="date_fin_contrat">Date de fin de contrat:</label>
                <input class="input-field" type="date" id="date_fin_contrat" name="date_fin_contrat" />
            </div>
            <div class="button-wrapper">
                <button type="submit" id="suivantButton">Suivant</button>
                <button type="button" onclick="window.location.href='fiche_materiel.php'">Modifié</button>
            </div>
</form>
<script>
    document.getElementById('code_indice').addEventListener('input', function () {
        document.getElementById('suivantButton').disabled = !this.value;
    });

    function submitForm() {
        document.getElementById('indicesForm').submit();
    }
</script>

</body>
</html>


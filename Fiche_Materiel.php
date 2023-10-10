    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    session_start();

    $message = '';
    $lastData = isset($_SESSION['last_data']) ? $_SESSION['last_data'] : [];

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

        $conn = new PDO($dsn, $user, $pass, $options);
        $query = "INSERT INTO materiel (Code_dImplementation, libelle, description, modele, numero_serie, fabricant, statut, date_mise_service, date_sortie, entite, classe) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);

        return $stmt->execute([
            $data['Code_dImplementation'],
            $data['libelle'],
            $data['description'],
            $data['modele'],
            $data['numero_serie'],
            $data['fabricant'],
            $data['statut'],
            $data['date_mise_service'],
            $data['date_sortie'],
            $data['entite'],
            $data['classe']
        ]);
    }

    function checkCodeExists($code) {
        global $dsn, $user, $pass, $options;

        $conn = new PDO($dsn, $user, $pass, $options);
        $query = "SELECT * FROM materiel WHERE Code_dImplementation = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$code]);
        return $stmt->fetch();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['submit_action'])) {
            switch ($_POST['submit_action']) {
                // ... (Other Cases) ...
                case 'Document':
                    // Save data if it hasn't been saved yet
                    if (!isset($_SESSION['data_saved']) || !$_SESSION['data_saved']) {
                        if (checkCodeExists($_POST['Code_dImplementation'])) {
                            $message = 'A row with the same Code_dImplementation already exists!';
                            break;
                        } else {
                            if (saveData($_POST)) {
                                $_SESSION['data_saved'] = true;
                                $_SESSION['last_data'] = $_POST;
                                $message = 'Data has been saved!';
                            } else {
                                $message = 'Failed to save the data!';
                                break;
                            }
                        }
                    }
                    // Generate and download the document
                    $lastData = $_SESSION['last_data'];
                    $documentContent = '';
                    foreach ($lastData as $key => $value) {
                        $documentContent .= '<strong>' . htmlspecialchars($key) . ':</strong> ' . htmlspecialchars($value) . '<br>';
                    }
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename="document.html"');
                    echo $documentContent;
                    exit;
                    break;
                case 'Suivant':
                    header('Location: indices.php');
                    exit;
                    break;
            }
        }
    }


    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Fiche Matériel</title>
        <link rel="stylesheet" type="text/css" href="style/Fiche_Materiel.css">
    </head>
    <body>
    <header>
        <h1> Ajouter un Matériel</h1>
    </header>
    <header>
            <nav class="navbar">
                <a href="index.html" class="navbar-link">SGLT</a>
                <a href="index.html" class="navbar-link">Home</a>
            </nav>
        </header>

    <?php
    if ($message) {
        echo '<div class="alert">' . $message . '</div>';
    }
    ?>

    <form action="fiche_materiel.php" method="POST">
            <!-- ... vos champs de formulaire existants ... -->
            <div class="form-field">
                <label for="Code_dImplementation">Code d'Implementation:</label>
                <input class="input-field" type="text" id="Code_dImplementation" name="Code_dImplementation" value="<?php echo $_SESSION['edit_mode'] ? ($lastData['Code_dImplementation'] ?? '') : ''; ?>" required />
            </div>
            <div class="form-field">
                <label for="libelle">Libellé:</label>
                <input class="input-field" type="text" id="libelle" name="libelle" value="<?php echo $_SESSION['edit_mode'] ? htmlspecialchars($lastData['libelle'] ?? '', ENT_QUOTES) : ''; ?>" required />
            </div>
            <div class="form-field">
                <label for="description">Description:</label>
                <textarea class="input-field" id="description" name="description" required><?php echo $_SESSION['edit_mode'] ? htmlspecialchars($lastData['description'] ?? '', ENT_QUOTES) : ''; ?></textarea>
            </div>
            <div class="form-field">
                <label for="modele">Modèle:</label>
                <input class="input-field" type="text" id="modele" name="modele" value="<?php echo $_SESSION['edit_mode'] ? htmlspecialchars($lastData['modele'] ?? '', ENT_QUOTES) : ''; ?>" required />
            </div>
            <div class="form-field">
                <label for="numero_serie">Numéro de Série:</label>
                <input class="input-field" type="text" id="numero_serie" name="numero_serie" value="<?php echo $_SESSION['edit_mode'] ? htmlspecialchars($lastData['numero_serie'] ?? '', ENT_QUOTES) : ''; ?>" required />
            </div>
            <div class="form-field">
                <label for="fabricant">Fabricant:</label>
                <input class="input-field" type="text" id="fabricant" name="fabricant" value="<?php echo $_SESSION['edit_mode'] ? htmlspecialchars($lastData['fabricant'] ?? '', ENT_QUOTES) : ''; ?>" required />
            </div>
            <div class="form-field">
                <label for="date_mise_service">Date de Mise en Service:</label>
                <input class="input-field" type="date" id="date_mise_service" name="date_mise_service" value="<?php echo $_SESSION['edit_mode'] ? htmlspecialchars($lastData['date_mise_service'] ?? '', ENT_QUOTES) : ''; ?>" required />
            </div>
            <div class="form-field">
                <label for="date_sortie">Date de Sortie:</label>
                <input class="input-field" type="date" id="date_sortie" name="date_sortie" value="<?php echo $_SESSION['edit_mode'] ? htmlspecialchars($lastData['date_sortie'] ?? '', ENT_QUOTES) : ''; ?>" />
            </div>
            <div class="form-field">
                <label for="entite">Entité:</label>
                <input class="input-field" type="text" id="entite" name="entite" value="<?php echo $_SESSION['edit_mode'] ? htmlspecialchars($lastData['entite'] ?? '', ENT_QUOTES) : ''; ?>" required />
            </div>
            <div class="form-field">
                <label for="classe">Classe:</label>
                <input class="input-field" type="text" id="classe" name="classe" value="<?php echo $_SESSION['edit_mode'] ? htmlspecialchars($lastData['classe'] ?? '', ENT_QUOTES) : ''; ?>" required />
            </div>
            <div class="button-wrapper">
                <label for="statut">Statut:</label>
                <select id="statut" name="statut" required>
                    <option value="" <?= $_SESSION['edit_mode'] && empty($lastData['statut']) ? 'selected' : '' ?>></option>
                    <option value="en_service" <?= $_SESSION['edit_mode'] && isset($lastData['statut']) && $lastData['statut'] == 'en_service' ? 'selected' : '' ?>>En Service</option>
                    <option value="en_fin_de_vie" <?= $_SESSION['edit_mode'] && isset($lastData['statut']) && $lastData['statut'] == 'en_fin_de_vie' ? 'selected' : '' ?>>En fin de vie</option>
                    <option value="sortie" <?= $_SESSION['edit_mode'] && isset($lastData['statut']) && $lastData['statut'] == 'sortie' ? 'selected' : '' ?>>Sortie</option>
                </select>
            </div>  

            <div class="button-wrapper buttons-container">
                        <input type="submit" class="button" name="submit_action" value="Document">
                        <?php if (isset($_SESSION['data_saved']) && $_SESSION['data_saved']): ?>
                        <input type="submit" class="button" name="submit_action" value="Suivant">
            <?php endif; ?>
            </div>
   

    </form>

    </body>
    </html>



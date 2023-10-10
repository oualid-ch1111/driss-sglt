<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ordre De Travail</title>
    <link rel="stylesheet" href="path_to_your_stylesheet.css">
</head>
<body>
<div>
        <h1>Ordre De Travail</h1>
        <nav class="navbar">
            <a href="index.html" class="navbar-link">SGLT</a>
            <a href="index.html" class="navbar-link">Home</a>
        </nav>
        <form class="form-container" action="handle_ordre_de_travail.php" method="post">
            <div class="form-field">
                <label for="ordre_travail">Ordre de travail:</label>
                <input type="text" id="ordre_travail" name="ordre_travail" />
            </div>

            <div class="form-field">
                <label for="materiel">Matériel:</label>
                <input type="text" id="materiel" name="materiel" />
            </div>

            <div class="form-field">
                <label for="type">Type:</label>
                <input type="text" id="type" name="type" />
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
                <label for="priorite">Priorité:</label>
                <input type="text" id="priorite" name="priorite" />
            </div>

            <div class="form-field">
                <label for="date_evenement">Date de l'événement:</label>
                <input type="date" id="date_evenement" name="date_evenement" />
            </div>

            <div class="form-field">
                <label for="affecte">Affecté à:</label>
                <input type="text" id="affecte" name="affecte" />
            </div>

            <button type="submit">Enregistrer</button>
        </form>
    </div>
</body>
</html>

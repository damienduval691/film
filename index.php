<!DOCTYPE html> 
<html> 
<head> 
  <title>Accueil</title> 
  <meta charset="utf-8" />
  <link rel="stylesheet" href="style/style.css" asp-append-version="true" />
</head> 
 
<body>
  <div class="siteContainer">
    <?php
      //Inclusion du header et de l'initialisation de la classe database
      include 'model/header.php'; 
      include 'init/init.php';
    ?>
    <div class="siteContentContainer">
      <?php
        //Inclusion de la filtrage des films
        include 'pages/research.php';
      ?>
    <div class="ajouterLigne">
      <?php
        //Inclusion de la page d'ajout de nouveau film
        include 'pages/add.php';
      ?>
    </div>
    <div class="modifierLigne">
      <?php
        // Connexion à la base de données 
        if($db->isConnected() === false){ 
          die("Erreur : Impossible de se connecter. " . mysqli_connect_error()); 
        }

        // Gérer la suppression d'une ligne si un ID est envoyé
        if (isset($_POST['delete_id'])) {
          $deleteId = (int)$_POST['delete_id'];
          if ($db->deleteRecord('dvd_data', $deleteId)) {
            echo "<p class='successText'>Ligne avec ID $deleteId supprimée avec succès.</p>";
          } else {
            echo "<p class='errorText'>Erreur lors de la suppression de la ligne : " . $db->error() . "</p>";
          }
        }

        //Mise a jour de la ligne dans la base de données
        if (isset($_POST['update_entry'])) {
          $updateId = (int)$_POST['update_id'];
          $updateNom = $db->real_escape_string($_POST['update_nom']);
          $updateAnnee = $db->real_escape_string($_POST['update_annee']);
          $updateGenre = $db->real_escape_string($_POST['update_genre']);
          $updateDuree = $db->real_escape_string($_POST['update_duree']);
      
          if ($db->update('dvd_data',$updateNom,$updateAnnee,$updateGenre,$updateDuree,$updateId)) {
              echo "<p class='successText'>Ligne mise à jour avec succès.</p>";
          } else {
              echo "<p class='errorText'>Erreur de mise à jour : " . $db->error() . "</p>";
          }
        }    
        //Ajout d'un formulaire si l'édition d'une ligne est souhaiter
        if (isset($_POST['edit_mode'])) {
          $editId = (int)$_POST['edit_id'];
          $editNom = $_POST['edit_nom'];
          $editAnnee = $_POST['edit_annee'];
          $editGenre = $_POST['edit_genre'];
          $editDuree = $_POST['edit_duree'];
      
          echo "<h3>Modifier l'entrée</h3>";
          echo "<form method='POST' action='' class='formFilm'>";
          echo "<input type='hidden' name='update_id' value='$editId' />";
          echo "<label>Nom du Film:</label> <input type='text' name='update_nom' value='$editNom' /><br>";
          echo "<label>Année:</label> <input type='text' name='update_annee' value='$editAnnee' /><br>";
          // Sélection des genres disponibles
          echo "<label>Genre:</label> <select name='update_genre'>";
          if ($genres = $db->select("dvd_data", "DISTINCT DVD_Genre", '', '')) {
            foreach ($genres as $genre) {
                $selected = ($genre['DVD_Genre'] == $editGenre) ? "selected" : "";
                echo '<option value="' . htmlspecialchars($genre['DVD_Genre']) . '" ' . $selected . '>' . htmlspecialchars($genre['DVD_Genre']) . '</option>';
            }
          }
          echo "</select><br>";
          echo "<label>Durée:</label> <input type='text' name='update_duree' value='$editDuree' /><br>";
          echo "<button class='buttonAddReg' type='submit' name='update_entry'>Enregistrer</button>";
          echo "</form>";
        }    
      ?>
    </div>
    <div class="mainTable">
      <?php 
        // Connexion à la base de données 
        if($db->isConnected() === false){ 
          die("Erreur : Impossible de se connecter. " . mysqli_connect_error()); 
        }

        //Limitation du nombre d'entrées par page
        $entryLimit = 50;

        //Récupérer la page courante depuis POST, par défaut page 1
        $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;

        //Calcul de l'offset
        $indexOffset = ($page-1) * $entryLimit;

        //Récupération du nombre d'entrée
        if($resultat = $db->select('dvd_data', 'COUNT(*) as total', '', ''))
        {
          $totalRow = mysqli_fetch_array($resultat);
          $totalEntries = $totalRow['total'];
          mysqli_free_result($resultat);
        }
        else {
          die("Erreur lors de la récupération dud total des entrées.");
        }

        //Calcul du nombre de pages
        $totalPages = ceil($totalEntries/$entryLimit);

        //Récupération des 50 entrées en fonction de la limite et de l'offset
        if($resultat = $db->select('dvd_data', '*', '', "LIMIT $entryLimit OFFSET $indexOffset"))
        {
          if (mysqli_num_rows($resultat) > 0) {
            echo "<table>";
            echo "<tr>";
            echo "<th>Identificateur</th>";
            echo "<th>Nom du Film</th>";
            echo "<th>Année</th>";
            echo "<th>Genre</th>";
            echo "<th>Durée</th>";
            echo "<th>Supprimer</th>";
            echo "<th>Modifier</th>";
            echo "</tr>";
            while ($row = mysqli_fetch_array($resultat)) 
            {
              echo "<tr>";
              echo "<td>" . $row['ID_DVD'] . "</td>";
              echo "<td>" . $row['DVD_Nom'] . "</td>";
              echo "<td>" . $row['DVD_Annee'] . "</td>";
              echo "<td>" . $row['DVD_Genre'] . "</td>";
              echo "<td>" . $row['DVD_Duree'] . "</td>";
              //Gestion du bouton supprimer
              echo "<td>";
              echo "<form method='POST' action=''>";
              echo "<input type='hidden' name='delete_id' value='" . $row['ID_DVD'] . "' />";
              echo "<button class='buttonDelete' type='submit'><img src='img/bouton-supprimer.png' class='buttonDeleteImage'>Supprimer</button>";
              echo "</form>";
              echo "</td>";
              //Gestion du bouton modifier
              echo "<td>";
              echo "<form method='POST' action=''>";
              echo "<input type='hidden' name='edit_id' value='" . $row['ID_DVD'] . "' />";
              echo "<input type='hidden' name='edit_nom' value='" . $row['DVD_Nom'] . "' />";
              echo "<input type='hidden' name='edit_annee' value='" . $row['DVD_Annee'] . "' />";
              echo "<input type='hidden' name='edit_genre' value='" . $row['DVD_Genre'] . "' />";
              echo "<input type='hidden' name='edit_duree' value='" . $row['DVD_Duree'] . "' />";
              echo "<button class='buttonEdit' type='submit' name='edit_mode'><img src='img/modifier-le-texte.png' class='buttonEditImage'>Modifier</button>";
              echo "</form>";
              echo "</td>";
              echo "</tr>";
            }
            echo "</table>";
  
            // Afficher l'indication de la pagination
            echo "<div class='manageTable'>";
            $start = ($page - 1) * $entryLimit + 1;
            $end = min($start + $entryLimit - 1, $totalEntries);
            echo "<p>Affichage : $start à $end sur $totalEntries entrées</p>";
  
            // Afficher les boutons de pagination avec un formulaire
            echo "<form method='POST' action=''>";
            if ($page > 1) {
              echo "<button class='buttonForm' style='margin:5px;' type='submit' name='page' value='" . ($page - 1) . "'>&laquo; Précédent</button>";
            }
            if ($page < $totalPages) {
              echo "<button class='buttonForm' style='margin:5px;' type='submit' name='page' value='" . ($page + 1) . "'>Suivant &raquo;</button>";
            }
            echo "</form>";
            echo "</div>";
  
            mysqli_free_result($resultat);
          } else {
            echo "Pas d'enregistrement dans la base.";
          }
        }
        else 
        {
          die("Erreur lors de l'exécution de la requête : " . $db->error());
        }

        // Fermeture base 
        $db->disconnect();
      ?> 
    </div>
    </div>
    <?php 
      include 'model/footer.php'; 
    ?>
  </div>
</body> 
 
</html>
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
      include 'model/header.php'; 
      include 'init/init.php';
      include 'pages/research.php';
    ?>
    <?php
      // Connexion à la base de données 
      if($db->isConnected() === false){ 
        die("Erreur : Impossible de se connecter. " . mysqli_connect_error()); 
      }

      // Gérer la suppression d'une ligne si un ID est envoyé
      if (isset($_POST['delete_id'])) {
        $deleteId = (int)$_POST['delete_id'];
        if ($db->deleteRecord('dvd_data', $deleteId)) {
          echo "<p>Ligne avec ID $deleteId supprimée avec succès.</p>";
        } else {
          echo "<p>Erreur lors de la suppression de la ligne : " . mysqli_error($db->connection) . "</p>";
        }
      }
    ?>
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
            echo "<th>Supprimer une ligne</th>";
            echo "</tr>";
            while ($row = mysqli_fetch_array($resultat)) 
            {
              echo "<tr>";
              echo "<td>" . $row['ID_DVD'] . "</td>";
              echo "<td>" . $row['DVD_Nom'] . "</td>";
              echo "<td>" . $row['DVD_Annee'] . "</td>";
              echo "<td>" . $row['DVD_Genre'] . "</td>";
              echo "<td>" . $row['DVD_Duree'] . "</td>";
              echo "<td>";
              echo "<form method='POST' action=''>";
              echo "<input type='hidden' name='delete_id' value='" . $row['ID_DVD'] . "' />";
              echo "<button class='buttonDelete' type='submit'><img src='img/bouton-supprimer.png' class='buttonDeleteImage'>Supprimer</button>";
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
              echo "<button type='submit' name='page' value='" . ($page - 1) . "'>&laquo; Précédent</button>";
            }
            if ($page < $totalPages) {
              echo "<button type='submit' name='page' value='" . ($page + 1) . "'>Suivant &raquo;</button>";
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
          die("Erreur lors de l'exécution de la requête : " . mysqli_error($db->connection));
        }

        // Fermeture base 
        $db->disconnect();
      ?> 
    </div>
    <?php 
      include 'model/footer.php'; 
    ?>
  </div>
</body> 
 
</html>
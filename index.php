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
    <div class="mainTable">
      <?php 
      // Connexion à la base de données 
      if($db->isConnected() === false){ 
        die("Erreur : Impossible de se connecter. " . mysqli_connect_error()); 
      } 
      //$Table = "SELECT * FROM dvd_data";// essai de connexion à la table 
      
      if($resultat = $db->select('dvd_data', '*', '', ''))
      {  
        if(mysqli_num_rows($resultat) > 0)//test le nombre d'enregistrement dans la base 
        { 
          echo "<table>"; 
          echo "<tr>"; 
          echo "<th>Identificateur</th>"; 
          echo "<th>Nom du Film</th>"; 
          echo "<th>Année</th>"; 
          echo "<th>genre</th>"; 
          echo "<th>durée</th>"; 
          echo "</tr>"; 
          while($row = mysqli_fetch_array($resultat))//récupération d’une ligne de résultat 
          { 
            echo "<tr>"; 
            echo "<td>" . $row['ID_DVD'] . "</td>"; 
            echo "<td>" . $row['DVD_Nom'] . "</td>"; 
            echo "<td>" . $row['DVD_Annee'] . "</td>"; 
            echo "<td>" . $row['DVD_Genre'] . "</td>"; 
            echo "<td>" . $row['DVD_Duree'] . "</td>"; 
            echo "</tr>"; 
          } 
          echo "</table>"; 
          // efface le résultat de la recherche précédente 
          mysqli_free_result($resultat); 
        } 
        else 
        { 
          echo "Pas d’enregistrement dans la base."; 
        }    
      }  
      else 
      { 
        echo "ERREUR: on ne peut pas exécuter $Table. " . $db->error(); 
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
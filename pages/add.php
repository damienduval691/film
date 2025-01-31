<h3>Ajouter un nouveau film</h3>
<!--Ajout d'un formulaire qui va gérer l'ajout d'un nouveau film dans la base-->
<form method="POST" action="" class="formFilm">
    <label>Nom du Film:</label> <input type="text" name="new_nom" required /><br>
    <label>Année:</label> <input type="number" name="new_annee" required /><br>
    <label>Genre:</label> 
    <select name="new_genre" required>
        <option value="">Sélectionner un genre</option>
        <?php 
        //Récupération de tous les genres existant dans la base de données
        if ($resultat = $db->select("dvd_data", "DISTINCT DVD_Genre", '', '')) {
            while ($genre = mysqli_fetch_assoc($resultat)) {
                echo '<option value="' . htmlspecialchars($genre['DVD_Genre']) . '">' . htmlspecialchars($genre['DVD_Genre']) . '</option>';
            }
            mysqli_free_result($resultat);
        }
        ?>
    </select><br>
    <label>Durée:</label> <input type="text" name="new_duree" required /><br>
    <button class="buttonAddReg" type="submit" name="add_film">Ajouter</button>
</form>
<?php
    //Si le formulaire est actif, alors on gére la requête d'ajout de film dans la base
    if (isset($_POST['add_film'])) {
        // Récupération et sécurisation des données
        $newNom = $db->real_escape_string($_POST['new_nom']);
        $newAnnee = $db->real_escape_string($_POST['new_annee']);
        $newGenre = $db->real_escape_string($_POST['new_genre']);
        $newDuree = $db->real_escape_string($_POST['new_duree']);

        $query = "1 ";
      
        //Ajout des paramètres dans la clause
        if (!empty($newNom)) {
            $namewithoutspace = strtolower(str_replace(' ', '', $newNom));
            $query .= " AND LOWER(REPLACE(DVD_Nom, ' ', '')) = '$namewithoutspace'";

        }
        if (!empty($newGenre)) {
            $query .= " AND DVD_Genre = '$newGenre'";
        }
        if (!empty($newAnnee)) {
            $query .= " AND DVD_Annee = '$newAnnee'";
        }
        if (!empty($newDuree)) {
        $query .= " AND DVD_Duree = '$newDuree'";
        }
        $resultat = $db->select('dvd_data', 'count(*) as total', $query, '');

        $totalRow = mysqli_fetch_array($resultat); 
        
        $totalEntries = $totalRow['total'];

        if($totalEntries<1) {
            // Exécution de l'insertion
            if ($db->insertInto("dvd_data", $newNom, $newAnnee, $newGenre, $newDuree)) {
                //Affichage d'un message pour informé l'utilisateur du succès de sa demande
                echo "<p class='successText'>Le film a été ajouté avec succès !</p>";
            } else {
                //Affichage d'un message pour informé l'utilisateur que sa demande à entrainer une erreur
                echo "<p class='errorText'>Erreur lors de l'ajout du film : " . $db->error() . "</p>";
            }
        } else {
            echo "<p class='errorText'>Un film existe déjà dans la base de données. </p>";
        }
    }
?>
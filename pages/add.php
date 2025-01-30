<h3>Ajouter un nouveau film</h3>
<form method="POST" action="" class="formFilm">
    <label>Nom du Film:</label> <input type="text" name="new_nom" required /><br>
    <label>Année:</label> <input type="text" name="new_annee" required /><br>
    <label>Genre:</label> 
    <select name="new_genre" required>
        <option value="">Sélectionner un genre</option>
        <?php 
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
    if (isset($_POST['add_film'])) {
        // Récupération et sécurisation des données
        $newNom = $db->real_escape_string($_POST['new_nom']);
        $newAnnee = $db->real_escape_string($_POST['new_annee']);
        $newGenre = $db->real_escape_string($_POST['new_genre']);
        $newDuree = $db->real_escape_string($_POST['new_duree']);

        // Exécution de l'insertion
        if ($db->insertInto("dvd_data", $newNom, $newAnnee, $newGenre, $newDuree)) {
            echo "<p class='successText'>Le film a été ajouté avec succès !</p>";
        } else {
            echo "<p class='errorText'>Erreur lors de l'ajout du film : " . $db->error() . "</p>";
        }
    }
?>
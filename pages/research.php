<form method="POST" action="index.php" class="formFilm">
    
    <!-- Première ligne -->
     <table class="custom-table">
        <tr class="custom-tr">
            <td class="custom-td-row">
                <!-- FIltre pour ID, Nom et genre-->
                <div class="formRow">
                    <label for="id">ID :</label>
                    <input type="number" id="id" name="id" placeholder="ID" class="id">
            
                    <label for="name">Nom :</label>
                    <input type="text" id="name" name="name" placeholder="Nom du film" class="text-film">
            
                    <label for="genre">Genre :</label>
                    <select id="genre" name="genre">
                        <option value="">Tous</option>
                        <?php 
                        $genres = $db->select("dvd_data", "DISTINCT DVD_Genre", '', '');
                        foreach ($genres as $genre): ?>
                            <option value="<?= htmlspecialchars($genre['DVD_Genre']); ?>">
                                <?= htmlspecialchars($genre['DVD_Genre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            
                <!-- FIltre pour les années et les durées-->
                <div  class="filterOptions">
                    <table>
                        <tr class="custom-tr">
                                <td class="custom-td">
                                    <label for="yearStart">Année min :</label>
                                    <input type="number" id="yearStart" name="yearStart" placeholder="Entrez une date" min="1900" max="2100">
                            
                                    <label for="yearEnd">Année max :</label>
                                    <input type="number" id="yearEnd" name="yearEnd" placeholder="Entrez une date" min="1900" max="2100">
                                            <br>
                                    <label><input type="radio" name="date_filter" id="filter_between" value="between" checked> Entre</label>
                                    <label><input type="radio" name="date_filter" id="filter_before" value="before"> Avant</label>
                                    <label><input type="radio" name="date_filter" id="filter_after" value="after"> Après</label>
                                </td>
        
                                <td class="custom-td">
                                    <label for="durationStart">Durée min :</label>
                                    <input type="number" id="durationStart" name="durationStart" placeholder="Durée en minutes" class="duration">
                            
                                    <label for="durationEnd">Durée max :</label>
                                    <input type="number" id="durationEnd" name="durationEnd" placeholder="Durée en minutes" class="duration">
                                            <br>
                                    <label><input type="radio" name="duree_filter" id="filter_between_duree" value="between" checked> Entre</label>
                                    <label><input type="radio" name="duree_filter" id="filter_minus" value="before"> <</label>
                                    <label><input type="radio" name="duree_filter" id="filter_plus" value="after"> > </label>
        
                                </td>
                        </tr>
                        </table>
                </div>
                <!-- Sélections -->
                <div class="filterOptions">
                <label for="order">Trie par : </label>
                <select id="order" name="order">
                        <option value="1">ID : Croissant</option>
                        <option value="2">ID : Décroissant</option>
                        <option value="3">Nom : A à Z</option>
                        <option value="4">Nom : Z à A</option>
                        <option value="5">Genre : A à Z</option>
                        <option value="6">Genre : Z à A</option>
                        <option value="7">Date : Croissant</option>
                        <option value="8">Date : Décroissant</option>
                        <option value="9">Durée : Croissant</option>
                        <option value="10">Durée : Décroissant</option>
                </div>
            </td>
            <td class="custom-td-research">
                <button class="buttonFiltrer" type="submit">
                    <img src="img/search-engine-optimization.png" class='buttonEditFiltrer' />
                Filtrer
            </button>
        
            </td>
        </tr>
     </table>
     <script>
    //Script qui permet de passer de grisé à actif pour les champs année & durée
    document.addEventListener("DOMContentLoaded", function () {

        //Récupération des données via l'ID des champs
        const radioBetween = document.getElementById("filter_between");
        const radioBefore = document.getElementById("filter_before");
        const radioAfter = document.getElementById("filter_after");

        const yearStart = document.getElementById("yearStart");
        const yearEnd = document.getElementById("yearEnd");

        const radioBetween_duree = document.getElementById("filter_between_duree");
        const radioMinus = document.getElementById("filter_minus");
        const radioPlus = document.getElementById("filter_plus");

        const durationStart = document.getElementById("durationStart");
        const durationEnd = document.getElementById("durationEnd");

        // Fonction pour ajouter un style actif ou inactif
        function toggleStyle(inputElement, isActive) {
            if (isActive) {
                inputElement.style.backgroundColor = "#ffffff";  // Fond normal
                inputElement.disabled = false;
            } else {
                inputElement.style.backgroundColor = "#f0f0f0";  // Fond grisé
                inputElement.style.borderColor = "#cccccc";  // Bordure grisée
                inputElement.disabled = true;
                inputElement.value = '';
            }
        }

        //On met à jour les champs
        function updateFields() {
            // Mise à jour pour les champs année
            if (radioBetween.checked) {
                toggleStyle(yearStart, true);
                toggleStyle(yearEnd, true);
            } else if (radioBefore.checked) {
                toggleStyle(yearStart, false);
                toggleStyle(yearEnd, true);
            } else if (radioAfter.checked) {
                toggleStyle(yearStart, true);
                toggleStyle(yearEnd, false);
            }

            // Mise à jour pour les champs durée
            if (radioBetween_duree.checked) {
                toggleStyle(durationStart, true);
                toggleStyle(durationEnd, true);
            } else if (radioMinus.checked) {
                toggleStyle(durationStart, false);
                toggleStyle(durationEnd, true);
            } else if (radioPlus.checked) {
                toggleStyle(durationStart, true);
                toggleStyle(durationEnd, false);
            }
        }

        //Lancement de la fonction et MAJ des champs
        updateFields();
        radioBetween.addEventListener("change", updateFields);
        radioBefore.addEventListener("change", updateFields);
        radioAfter.addEventListener("change", updateFields);
        radioBetween_duree.addEventListener("change", updateFields);
        radioMinus.addEventListener("change", updateFields);
        radioPlus.addEventListener("change", updateFields);
    });
</script>


</form>



<form method="POSTGE" action="index.php" class="formFilm">
    
    <!-- Première ligne -->
    <div class="formRow">
        <label for="id">ID :</label>
        <input type="number" id="id" name="id" placeholder="Identifiant">

        <label for="name">Nom :</label>
        <input type="text" id="name" name="name" placeholder="Nom du film">

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

    <div  class="filterOptions">
        <label for="yearStart">Année de début :</label>
        <input type="number" id="yearStart" name="yearStart" placeholder="Entrez une date" min="1900" max="2100">

        <label for="yearEnd">Année de fin :</label>
        <input type="number" id="yearEnd" name="yearEnd" placeholder="Entrez une date" min="1900" max="2100">

        <label><input type="radio" name="date_filter" id="filter_between" value="between" checked> Entre deux dates</label>
        <label><input type="radio" name="date_filter" id="filter_before" value="before"> Avant une date</label>
        <label><input type="radio" name="date_filter" id="filter_after" value="after"> Après une date</label>

    </div>
    <!-- Sélections -->
    <div class="filterOptions">
        <label for="durationStart">Durée min (en minutes) :</label>
        <input type="text" id="durationStart" name="durationStart" placeholder="Durée en minutes">

        <label for="durationEnd">Durée max (en minutes) :</label>
        <input type="text" id="durationEnd" name="durationEnd" placeholder="Durée en minutes">

        <label><input type="radio" name="duree_filter" id="filter_between_duree" value="between" checked> Entre deux durées</label>
        <label><input type="radio" name="duree_filter" id="filter_minus" value="before"> Plus court</label>
        <label><input type="radio" name="duree_filter" id="filter_plus" value="after"> Plus long</label>
    </div>

    <button class="buttonFiltrer" type="submit">
        <img src="img/search-engine-optimization.png" class='buttonEditFiltrer' />
     Filtrer
</button>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
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

            function updateFields() {
                switch (true) {
                    case radioBetween.checked:
                        yearStart.disabled = false;
                        yearEnd.disabled = false;
                        break;
                    case radioBefore.checked:
                        yearStart.disabled = true;
                        yearEnd.disabled = false;
                        break;
                    case radioAfter.checked:
                        yearStart.disabled = false;
                        yearEnd.disabled = true;
                        break;
                }

                switch(true){
                    case radioBetween_duree.checked:
                        durationStart.disabled = false;
                        durationEnd.disabled = false;
                        break;
                    case radioMinus.checked:
                        durationStart.disabled = true;
                        durationEnd.disabled = false;
                        break;
                    case radioPlus.checked:
                        durationStart.disabled = false;
                        durationEnd.disabled = true;
                        break;
                }
            }

            updateFields();
            radioBetween.addEventListener("change", updateFields);
            radioBefore.addEventListener("change", updateFields);
            radioAfter.addEventListener("change", updateFields);
            updateFields();
            radioBetween_duree.addEventListener("change", updateFields);
            radioMinus.addEventListener("change", updateFields);
            radioPlus.addEventListener("change", updateFields);
        });
    </script>

</form>



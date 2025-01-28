<form method="POST" action="index.php" class="formFilm">
    <label for="id">ID :</label>
    <input type="id" id="id" name="id" placeholder="Identifiant">

    <label for="name">Nom :</label>
    <input type="text" id="name" name="name" placeholder="Nom du film">

    <label for="genre">Genre :</label>
    <select id="genre" name="genre">
        <option value="">Tous</option>
        
        <?php 
        $genres = $db->select("dvd_data", "DISTINCT DVD_Genre", '', '');
            foreach ($genres as $genre): ?>
            <option value="<?= htmlspecialchars($genre['DVD_Genre']); ?>"><?= htmlspecialchars($genre['DVD_Genre']); ?></option>
        <?php endforeach; ?>
    </select>

    <label for="year">Année :</label>
    <input type="number" id="year" name="year" placeholder="Entrez une année" min="1900" max="2100">

    <button class="buttonForm" type="submit" name="date_filter" value="before">Avant</button>
    <button class="buttonForm" type="submit" name="date_filter" value="after">Après</button>

    <label for="duration">Durée (en minutes) :</label>
    <input type="text" id="duration" name="duration" placeholder="Durée en minutes">

    <button class="buttonForm" type="submit">Filtrer</button>
</form>

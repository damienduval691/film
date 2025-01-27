<form method="GET" action="index.php">
    <label for="id">ID :</label>
    <input type="text" id="id" name="id" placeholder="Identifiant">

    <label for="name">Nom :</label>
    <input type="text" id="name" name="name" placeholder="Nom du film">

    <label for="genre">Genre :</label>
    <select id="genre" name="genre">
        <option value="">Tous</option>
        <option value="Science Fiction">Science Fiction</option>
        <option value="Western">Western</option>
        <option value="Policier">Policier</option>
        <option value="Thriller">Thriller</option>
        <option value="Romantique">Romantique</option>
    </select>

    <label for="year">Année :</label>
    <input type="number" id="year" name="year" placeholder="Entrez une année" min="1900" max="2100">

    <button type="submit" name="date_filter" value="before">Avant</button>
    <button type="submit" name="date_filter" value="after">Après</button>

    <label for="duration">Durée (en minutes) :</label>
    <input type="text" id="duration" name="duration" placeholder="Durée en minutes">

    <button type="submit">Filtrer</button>
</form>

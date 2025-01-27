<?php
class database{
    //Initialisation des attributs
    private $localhost;
    private $password;
    private $user;
    private $db;

    private $connected;

    //Constructeur de l'object Player
    public function __construct($localhost, $password, $user, $db){
        $this->localhost    = $localhost;
        $this->password     = $password;
        $this->user         = $user;
        $this->db           = $db;
    }
    public function connect(){
        $this->connected = mysqli_connect($this->localhost, $this->user, $this->password, $this->db);
        return $this->connected;
    }
    public function disconnect(){
        $this->connected = mysqli_close($this->connected);
        return $this->connected;
    }
    public function select($table, $parameters, $where, $other){
        $where = empty($where) ?"":"WHERE ". $where;
        $sql = "SELECT $parameters FROM $table $where $other";
        return mysqli_query($this->connected, $sql);
    }
    public function isConnected(){
        return $this->connected;
    }

    public function error(){
        return mysqli_error($this->connected);
    }

    public function insertInto($table, $film, $annee, $style, $duree){
        $sql = "INSERT INTO `$table` (`DVD_Nom`, `DVD_Annee`, `DVD_Genre`, `DVD_Duree`) VALUES (?, ?, ?, ?)";
        
        $stmtInsert = mysqli_prepare($this->connected, $sql);
        if(!$stmtInsert){
            return false;
        }
        mysqli_stmt_bind_param($stmtInsert, "siss", $film, $annee, $style, $duree);
        
        $return_value = mysqli_stmt_execute($stmtInsert);
        
        mysqli_stmt_close($stmtInsert);
        
        return $return_value;
    }

    public function update($table, $film, $annee, $style, $duree, $id) {
        // Construire les parties de la requête dynamiquement
        $fields = [];
        $params = [];
        $types = '';
    
        if (!empty($film)) {
            $fields[] = "`DVD_Nom` = ?";
            $params[] = $film;
            $types .= 's';
        }
        if (!empty($annee)) {
            $fields[] = "`DVD_Annee` = ?";
            $params[] = $annee;
            $types .= 'i';
        }
        if (!empty($style)) {
            $fields[] = "`DVD_Genre` = ?";
            $params[] = $style;
            $types .= 's';
        }
        if (!empty($duree)) {
            $fields[] = "`DVD_Duree` = ?";
            $params[] = $duree;
            $types .= 's';
        }
    
        // Ajouter l'ID pour la condition WHERE
        $params[] = $id;
        $types .= 'i';
    
        // Vérifier qu'il y a des colonnes à mettre à jour
        if (empty($fields)) {
            return false; // Rien à mettre à jour
        }
    
        // Construire la requête SQL
        $sql = "UPDATE `$table` SET " . implode(', ', $fields) . " WHERE `id` = ?";
    
        // Préparer la requête
        $stmtUpdate = mysqli_prepare($this->connected, $sql);
        if (!$stmtUpdate) {
            die("Erreur de préparation de la requête : " . mysqli_error($this->connected));
        }
    
        // Lier les paramètres dynamiquement
        mysqli_stmt_bind_param($stmtUpdate, $types, ...$params);
    
        // Exécuter la requête
        $return_value = mysqli_stmt_execute($stmtUpdate);
    
        // Fermer la déclaration
        mysqli_stmt_close($stmtUpdate);
    
        return $return_value;
    }

    public function deleteRecord($table, $id) {
        // Vérifier si l'ID est valide
        if (empty($id) || !is_numeric($id)) {
            return false; // ID invalide
        }
    
        // Préparer la requête SQL pour la suppression
        $sql = "DELETE FROM `$table` WHERE `id` = ?";
    
        // Préparer la déclaration
        $stmtDelete = mysqli_prepare($this->connected, $sql);
        
        if (!$stmtDelete) {
            die("Erreur de préparation de la requête : " . mysqli_error($this->connected));
        }
    
        // Lier le paramètre (ID) à la requête
        mysqli_stmt_bind_param($stmtDelete, 'i', $id);
    
        // Exécuter la requête
        $return_value = mysqli_stmt_execute($stmtDelete);
    
        // Fermer la déclaration
        mysqli_stmt_close($stmtDelete);
    
        return $return_value; // Retourne true si la suppression a réussi, false sinon
    }
    
    
}
?>
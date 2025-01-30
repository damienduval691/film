<?php
class database{
    //Initialisation des attributs
    private $localhost;
    private $password;
    private $user;
    private $db;

    private $connected;

    //Constructeur de l'object database
    public function __construct($localhost, $password, $user, $db){
        $this->localhost    = $localhost;
        $this->password     = $password;
        $this->user         = $user;
        $this->db           = $db;
    }

    /**
     * @function connect()
     * Fonction qui ouvre la connexion
     * @return : $connected 
     */
    public function connect(){
        $this->connected = mysqli_connect($this->localhost, $this->user, $this->password, $this->db);
        return $this->connected;
    }

    /**
     * @function disconnect()
     * Fonction qui ferme la connexion
     * @return : error
     */
    public function disconnect(){
        $this->connected = mysqli_close($this->connected);
        return $this->connected;
    }

    /**
     * @function select(string, string, string, string)
     * Fonction qui sélectionne en base
     * @parameter $table string : nom de la table
     * @parameter $parameters string : paramètres pour 
     * @parameter $where string : filtre (where etc)
     * @parameter $other string : paramètres complémentaires au select
     * @return : error
     */
    public function select($table, $parameters, $where, $other){
        $where = empty($where) ?"":"WHERE ". $where;
        $sql = "SELECT $parameters FROM $table $where $other";
        $stmt = mysqli_prepare($this->connected, $sql);
        if (!$stmt) {
            die("Erreur de préparation : " . mysqli_error($this->connected));
        }

        return mysqli_query($this->connected, $sql);
    }

    /**
     * @function isConnected()
     * Fonction qui si la connection est ouverte ou non
     * @return : error
     */
    public function isConnected(){
        return $this->connected;
    }

    /**
     * @function error()
     * Fonction qui retourne l'erreur
     * @return : error
     */
    public function error(){
        return mysqli_error($this->connected);
    }


    /**
     * @function insertInto(string, string, int, string, string)
     * Fonction d'insertion de la table / ligne
     * @parameter $table string : nom de la table à modifier
     * @parameter $film string : nom du film
     * @parameter $annee int : année à modifier
     * @parameter $style string : genre du film à modifier
     * @parameter $duree string : durée du film à modifier
     * @parameter $id int : id de la ligne à supprimer
     * @return $return_value bool : retour true ou false si ça a fonctionné
     */
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

    /**
     * @function update(string, string, int, string, string, int)
     * Fonction d'update de la table / ligne
     * @parameter $table string : nom de la table à modifier
     * @parameter $film string : nom du film
     * @parameter $annee int : année à modifier
     * @parameter $style string : genre du film à modifier
     * @parameter $duree string : durée du film à modifier
     * @parameter $id int : id de la ligne à supprimer
     * @return $return_value bool : retour true ou false si ça a fonctionné
     */

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
    
        //Ajout des id pour la condition WHERE
        $params[] = $id;
        $types .= 'i';
    
        //Vérifier si des paramètres sont présents dans le tableau
        if (empty($fields)) {
            return false; // Rien à mettre à jour
        }
    
        //Construction de la requête SQL
        $sql = "UPDATE `$table` SET " . implode(', ', $fields) . " WHERE `id_dvd` = ?";
    
        //Préparation de la requête SQL
        $stmtUpdate = mysqli_prepare($this->connected, $sql);
        if (!$stmtUpdate) {
            die("Erreur de préparation de la requête : " . mysqli_error($this->connected));
        }
    
        //Liaison des paramètres avec la requête
        mysqli_stmt_bind_param($stmtUpdate, $types, ...$params);
    
        //Execution de la requête
        $return_value = mysqli_stmt_execute($stmtUpdate);
    
        //Fermeture du statement
        mysqli_stmt_close($stmtUpdate);
    
        return $return_value;
    }

    /**
     * @function deleteRecord(string, int)
     * Fonction de suppression de la table / ligne
     * @parameter $table string : nom de la table à modifier
     * @parameter $id int : id de la ligne à supprimer
     * @return $return_value bool : retour true ou false si ça a fonctionné
     */

    public function deleteRecord($table, $id) {
        // Vérifier si l'ID est valide
        if (empty($id) || !is_numeric($id)) {
            return false; // ID invalide
        }
    
        // Préparer la requête SQL pour la suppression
        $sql = "DELETE FROM `$table` WHERE `id_dvd` = ?";
    
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
    
        return $return_value;
    }

    public function real_escape_string($param){
        return mysqli_real_escape_string($this->connected, $param);
    }
    
    public function connect_error(){
        return mysqli_connect_error();
    }
}
?>
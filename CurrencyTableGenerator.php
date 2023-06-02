<?php
require_once "Config.php";
class CurrencyTableGenerator {
    public function __construct() {
        $this->dbHost = Config::$dbHost;
        $this->dbName = Config::$dbName;
        $this->dbUser = Config::$dbUser;
        $this->dbPass = Config::$dbPass;
    }

    /**
     * Retieves all currency data from the database into the variable.
     *
     * @return array Currency data in array
     */
    public function generateCurrencyTable(){

        try {
            $pdo = new PDO("mysql:host=$this->dbHost;dbname=$this->dbName", $this->dbUser, $this->dbPass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->query("SELECT code, currency, mid FROM currency");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e){
            throw new Exception('Error while retrieving data from database: ' . $e->getMessage());
        }
    }
}
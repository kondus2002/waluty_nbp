<?php
require_once 'Config.php';
class Currency {
    private $url = "http://api.nbp.pl/api/exchangerates/tables/a";

    public function __construct() {
        $this->dbHost = Config::$dbHost;
        $this->dbName = Config::$dbName;
        $this->dbUser = Config::$dbUser;
        $this->dbPass = Config::$dbPass;
    }

    /**
     * Downloads current currency exchange rates
     *
     * @return array Currency data in array
     */
    public function getCurrentCurrency() {

        $response = file_get_contents($this->url);

        if ($response === false) {
            throw new Exception('Failed to fetch data from NBP API.');
        }

        $data = json_decode($response, true);

        if(isset($data[0]['rates'])){
            return $data;
        }

        throw new Exception('Invalid response from NBP API.');
    }

    /**
     * Writes currency data to the database
     *
     * @param string $code Currency code given in three letters. E.g. EUR, USD.
     * @param string $currency Currency name. E.g. U.S. dollar, euro, Canadian dollar
     * @param float $mid Currency value
     *
     * @return null
     */
    public function saveToDatabase($code, $currency, $mid) {

        try {

            $pdo = new PDO("mysql:host=$this->dbHost;dbname=$this->dbName", $this->dbUser, $this->dbPass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare("INSERT IGNORE INTO currency (code, currency, mid) VALUES (?, ?, ?)");
            $stmt->execute([$code, $currency, $mid]);

        } catch (PDOException $e){
            throw new Exception('Error while saving data to database: ' . $e->getMessage());
        }
    }
}
<?php
require_once "Config.php";
class CurrencyConverter {
    public function __construct() {
        $this->dbHost = Config::$dbHost;
        $this->dbName = Config::$dbName;
        $this->dbUser = Config::$dbUser;
        $this->dbPass = Config::$dbPass;
    }

    /**
     * Calculates the value of one currency into another
     *
     * @param float $value Value of money in the first currency.
     * @param float $fromCurrency Value of the first currency
     * @param float $toCurrency Value of the second currency.
     *
     * @return float Converted value of money between the first and second currencies.
     */
    public function convertCurrency($value, $fromCurrency, $toCurrency){

        return ($value*$fromCurrency)/$toCurrency;

    }

    /**
     * Saves the converted currencies to a table in the database.
     *
     * @param string $sourceCurrency Source currency code given in three letters. E.g. EUR, USD.
     * @param float $sourceCurrencyAmount Value of money in the source currency
     * @param string $targetCurrency Target currency code given in three letters. E.g. EUR, USD.
     * @param float $targetCurrencyAmount Value of money after currency conversion
     *
     * @return string "Data has been saved to database."
     */
    public function saveConvertedCurrency($sourceCurrency, $sourceCurrencyAmount, $targetCurrency, $targetCurrencyAmount){

        try {
            $pdo = new PDO("mysql:host=$this->dbHost;dbname=$this->dbName", $this->dbUser, $this->dbPass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare("INSERT INTO convertions (source_currency, source_currency_amount, target_currency, target_currency_amount) VALUES (?, ?, ?, ?)");
            $stmt->execute([$sourceCurrency, $sourceCurrencyAmount, $targetCurrency, $targetCurrencyAmount]);

            return 'Data has been saved to database.';
        } catch (PDOException $e) {
            throw new Exception('Error while saving data: ' . $e->getMessage());
        }

    }

    /**
     * Retrieves data on previously converted currencies from the database
     *
     * @return array An array of the last 5 currencies converted
     */
    public function getConvertedCurrencies(){

        try {
            $pdo = new PDO("mysql:host=$this->dbHost;dbname=$this->dbName", $this->dbUser, $this->dbPass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->query("SELECT source_currency, source_currency_amount, target_currency, target_currency_amount FROM convertions LIMIT 5");

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception('Error while extracting data: ' . $e->getMessage());
        }
    }
}
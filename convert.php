<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h3>Currency Converter</h3>
        <form method="post">
            <input type="number" name="value" step="0.01">
            <select name="from" id="fromId">
                <option value='{"value":1,"code":"PLN"}'>PLN</option>
                <?php

                    require_once "CurrencyTableGenerator.php";

                    $currency = new CurrencyTableGenerator();

                    try {
                        // najpierw pobiera z bazy danych informacje o wszystkich walutach, później wypisuje jako <option> w html
                        $results = $currency->generateCurrencyTable();

                        if(count($results)>0){

                            foreach ($results as $result) {
                                $codeFrom = $result['code'];
                                $currencyValueFrom = $result['mid'];

                                echo "<option value='{\"value\":$currencyValueFrom,\"code\":\"$codeFrom\"}'>$codeFrom</option>";
                            }
                            echo "</select>";
                            echo " &nbsp &nbsp TO  &nbsp &nbsp";

                            //powtarza wypisanie <option>, tym razem dla waluty docelowej
                            echo "<select name='to' id='toId'>";
                            echo "<option value='{\"value\":1,\"code\":\"PLN\"}'>PLN</option>";
                            foreach ($results as $result) {
                                $codeTo = $result['code'];
                                $currencyValueTo = $result['mid'];

                                echo "<option value='{\"value\":$currencyValueTo,\"code\":\"$codeTo\"}'>$codeTo</option>";
                            }
                            echo "</select>";

                        } else {
                            echo 'No data to display.';
                        }

                    } catch(Exception $e) {
                        echo "Error: ". $e->getMessage();
                    }

                ?>
                <input type="submit" name="calculate" value="Convert">
        </form>
        <?php

        if(isset($_POST['calculate'])){

            require_once "CurrencyConverter.php";
            //przewalutowuje i zapisuje do bazy
            $currencyData = new CurrencyConverter();

            try {

                $value = $_POST['value'];
                $from = json_decode($_POST['from'], true);
                $to = json_decode($_POST['to'], true);

                $response = $currencyData->convertCurrency($value, $from['value'], $to['value']);

                echo "<br><br><b>$value " . $from['code'] . " = " . round($response, 2) . " " . $to['code'] . "</b><br><br>"; // zwraca zaokrągloną do dwóch miejsc po przecinku wartość

                echo $saveData = $currencyData->saveConvertedCurrency($from['code'], $value, $to['code'], round($response, 2));

            } catch(Exception $e) {
                echo "Error: ". $e->getMessage();
            }
        }

        ?>
    <h3>Last Convertions</h3>
        <?php

            require_once "CurrencyConverter.php";
            //wypisuje 5 ostatnio wykonanych przewalutowań
            $currency = new CurrencyConverter();

            try {

                $results = $currency->getConvertedCurrencies();
                if(count($results) > 0) {

                    foreach($results as $result){
                        $from = $result['source_currency'];
                        $fromAmount = $result['source_currency_amount'];
                        $to = $result['target_currency'];
                        $toAmount = $result['target_currency_amount'];

                        echo '<p>' . $fromAmount . ' ' . $from . ' &rarr; ' . $toAmount . ' ' . $to . '</p>';
                    }

                } else {
                    echo 'No data to display.';
                }

            } catch(Exception $e){
                echo "Error: ". $e->getMessage();
            }
        ?>
    <a href="index.php"><button>Back</button></a>
    </body>
</html>
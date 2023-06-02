<?php

require_once 'Currency.php';

$currency = new Currency();

try {

    $data = $currency->getCurrentCurrency();

    foreach($data[0]['rates'] as $item){
        $code = $item['code'];
        $crrncy = $item['currency'];
        $mid = $item['mid'];

        $currency->saveToDatabase($code, $crrncy, $mid);
    }

    echo "Data saved successfully!";
    echo "<br><br><a href='index.php'><button>Back</button></a>";

} catch (Exception $e) {
    echo "Error: ". $e->getMessage();
    echo "<br><br><a href='index.php'><button>Back</button></a>";
}
<?php

require_once "CurrencyTableGenerator.php";

$currency = new CurrencyTableGenerator();

try {

    $results = $currency->generateCurrencyTable();

    if (count($results) > 0) {
        echo '<table>';
        echo '<tr><th>Currency Code</th><th>Currency Name</th><th>Price</th></tr>';

        foreach ($results as $row) {
            echo '<tr>';
            echo '<td>' . $row['code'] . '</td>';
            echo '<td>' . $row['currency'] . '</td>';
            echo '<td>' . $row['mid'] . '</td>';
            echo '</tr>';
        }

        echo '</table>';
    } else {
        echo 'No data to display.';
    }

    echo "<br><br>";
    echo "<a href='convert.php' target='_blank'><button>Currency Convertion</button></a>";
    echo "&nbsp &nbsp <a href='getCurrentData.php'><button>Update Currency Data</button></a>";

} catch (Exception $e) {
    echo "Error: ". $e->getMessage();
}
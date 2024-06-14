<?php
/**
 * 
 * @return string
 */
function getStyle() {
    return "<style>           
             table.my_table {
                font-family: verdana,arial,sans-serif;
                color:#333333;
                border-width: 1px;
                border-color: #999999;
                border-collapse: collapse;
            }
            table.my_table th {
                font-size:10px;
                background:#b5cfd2;
                border-width: 1px;
                padding: 3px;
                border-style: solid;
                border-color: #999999;
            }
            table.my_table tr{
                 -webkit-print-color-adjust: exact; 
            }
            table.my_table tr:nth-child(odd) {
                background: #f2f2f2;
            }
            table.my_table tr:hover {
                background-color: #ffff00;
            }
            table.my_table td {
                font-size:10px;
                border-width: 1px;
                padding: 2px;
                border-style: solid;
                border-color: #999999;
            }
        </style>";
}

/**
 * 
 * @return type
 */
function getHtmlTop() {
    $style = getStyle();
    return "<!DOCTYPE html>
                    <html>
                    <head>
                    $style
                    <title>Mortgage Calculator</title>
                    </head>
                    <body>";
}

/**
 * 
 * @return string
 */
function getHtmlBottom() {
    return "</body>
                </html>";
}

/**
 * 
 * @param type $results
 */
function displayResults($results, $numberOfYears, $messages) {
    $summary = $results["summary"];
    $amortization = $results["amortization"];
    // get top
    echo getHtmlTop();
    if ($summary == "n/a") {
        foreach ($messages as $message) {
            echo "<div>$message</div>";
        }
    } else {
        // display mortgage summary
        echo "<div>Mortgage Summary</div>";
        echo "<table class=\"my_table\">";
        echo "<tr><td>Home Value: </td><td>$" . $summary["homeValue"] . "</td></tr>";
        echo "<tr><td>Monthly Mortgage: </td><td>$" . $summary["monthlyMortgage"] . "</td></tr>";
        echo "<tr><td>Total Payment: </td><td>$" . $summary["totalPayment"] . "</td></tr>";
        echo "<tr><td>Total Interest: </td><td>$" . $summary["totalInterest"] . "</td></tr>";
        echo "</table><br>";
        // display amortization table
        echo "<div>Amortization Schedule</div>";
        echo "<table class=\"my_table\">";
        echo "<tr><th>Month #</th><th>Mortgage</th><th>Principal</th><th>Interest</th><th>Balance</th></tr>";
        foreach ($amortization as $row) {
            echo "<tr>";
            echo "<td>" . $row["monthNumber"] . "</td>";
            echo "<td>" . $row["mortgage"] . "</td>";
            echo "<td>" . $row["principal"] . "</td>";
            echo "<td>" . $row["interest"] . "</td>";
            echo "<td>" . $row["balance"] . "</td>";
            echo "</tr>";
            if ($row["monthNumber"] % 12 == 0 && $row["monthNumber"] != ($numberOfYears * 12)) {
                // redisplay header
                echo "<tr><th>Month #</th><th>Mortgage</th><th>Principal</th><th>Interest</th><th>Balance</th></tr>";
            }
        }
        echo "</table>";
    }
    // get bottom
    echo getHtmlBottom();
}

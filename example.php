<?php
/**
 * @author Adeola Odusola
 * 
 * This is an example of a mortgage amortization schedule calculator.
 * Here you can input your own values for loanAmount, rawRate and numberOfYears.
 * This example will use a $loanAmount of $500000, $rate of 5%, for 30 years
 * This does not include escrow amount that typically includes real estate taxes, insurance premiums, and private mortgage insurance.
 * This is for the mortgage amount only.
 */

require_once 'MortgageCalculator.php';
require_once 'view.php';

$loanAmount = 500000;
$rate = 5;
$numberOfYears = 30;

$mortgage = new MortgageCalculator($loanAmount, $rate, $numberOfYears);
$results = $mortgage->getResults();
$messages = $mortgage->getMessages();

displayResults($results, $numberOfYears, $messages);




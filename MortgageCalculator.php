<?php

/**
 * Description of MortgageCalculator
 *
 * @author Adeola Odusola
 * 
 * 
 * The following program calculates monthly mortgage, displays the mortgage summary and amortization table.
 * The formulas used are described below:
 * 
 * Monthly mortgage:
 * M = Lr/1-(1+r)^-n 
 * where M = monthly mortgage, L = loan amount, r = monthly interest rate, n= number of months
 * The interest rate inputted is normalized into a monthly interest rate by dividing by 100 and by 12
 * The inputted number of years is normalized into months by multiplying by 12.
 * 
 * Interest:
 * The cumulative interest paid at the end of any period N can be calculated by:
 * I = ((Lr-m)((1+r)^N - 1)/r) + mN
 * where I is the cumulative interest, L = loan amount, r= monthly interest rate, m= monthly mortgage, and N = month number
 * The interest for a month is computed by subtracting the previous cumulative interest from the current cumulative interest.
 *
 */
class MortgageCalculator {

    private $loanAmount;
    private $rawRate;
    private $numberOfYears;
    private $messages;

    /**
     * 
     * @param type $loanAmount
     * @param type $rawRate
     * @param type $numberOfYears
     * constructor initializes loan amount, rate, and number of years
     */
    public function __construct($loanAmount, $rawRate, $numberOfYears) {
        // initialize fields
        $this->loanAmount = $loanAmount;
        $this->rawRate = $rawRate;
        $this->numberOfYears = $numberOfYears;
    }

    /**
     * 
     * @return bool
     */
    public function valid() {
        $valid = true;
        $messages = [];
        // loan amount
        if (!is_numeric($this->loanAmount)) {
            array_push($messages, "enter a numeric value for loan amount");
            $valid = false;
        }
        if ($this->loanAmount <= 0) {
            array_push($messages, "enter a value greater than zero for loan amount");
            $valid = false;
        }
        // rate
        if (!is_numeric($this->rawRate)) {
            array_push($messages, "enter a numeric value for loan rate");
            $valid = false;
        }
        if ($this->rawRate <= 0) {
            array_push($messages, "enter a value greater than zero for loan rate");
            $valid = false;
        }
        // years
        if (!is_numeric($this->numberOfYears)) {
            array_push($messages, "enter a numeric value for number of years");
            $valid = false;
        }
        if ($this->numberOfYears <= 0) {
            array_push($messages, "enter a value greater than zero for number of years");
            $valid = false;
        }
        $this->messages = $messages;
        return $valid;
    }

    /**
     * 
     * @return type
     */
    public function getMessages() {
        return $this->messages;
    }

    /**
     * 
     * @param type $loanAmount
     * @param type $rate
     * @param type $numberOfMonths
     * @return type float 
     * 
     */
    public function calculateMonthlyMortgage($loanAmount, $rate, $numberOfMonths) {
        $monthlyMortgage = ($loanAmount * $rate) / (1 - pow(1 + $rate, -$numberOfMonths));
        return $monthlyMortgage;
    }

    /**
     * 
     * @param type $loanAmount
     * @param type $rate
     * @param type $monthlyMortgage
     * @param type $monthNumber
     * @return type float
     * 
     */
    public function calculateCumulativeInterest($loanAmount, $rate, $monthlyMortgage, $monthNumber) {
        $cumulativeInterest = ((($loanAmount * $rate) - $monthlyMortgage) * (pow(1 + $rate, $monthNumber) - 1)) / $rate + ($monthlyMortgage * $monthNumber);
        return $cumulativeInterest;
    }

    /**
     * 
     * @param type $loanAmount
     * @param type $monthlyMortgage
     * @param type $totalPayment
     * @param type $totalInterest
     * @return type array
     * 
     */
    public function generateSummary($loanAmount, $monthlyMortgage, $totalPayment, $totalInterest) {
        // summary
        $mortgageSummary = [
            "homeValue" => number_format($loanAmount, 2),
            "monthlyMortgage" => number_format($monthlyMortgage, 2),
            "totalPayment" => number_format($totalPayment, 2),
            "totalInterest" => number_format($totalInterest, 2)
        ];
        return $mortgageSummary;
    }

    /**
     * 
     * @param type $loanAmount
     * @param type $rate
     * @param type $numberOfMonths
     * @param type $monthlyMortgage
     * @return type array
     * 
     */
    public function generateAmortization($loanAmount, $rate, $numberOfMonths, $monthlyMortgage) {
        // payments, interest timelines    
        $amortization = [];
        $interest = 0;
        $balance = $loanAmount;
        $previousCumulativeInterest = 0;
        for ($x = 1; $x <= $numberOfMonths; $x++) {
            //
            $cumulativeInterest = $this->calculateCumulativeInterest($loanAmount, $rate, $monthlyMortgage, $x);
            $interest = $cumulativeInterest - $previousCumulativeInterest;
            $principal = $monthlyMortgage - $interest;
            $balance -= $principal;

            $amortization[] = [
                "monthNumber" => $x,
                "mortgage" => number_format($monthlyMortgage, 2),
                "principal" => number_format($principal, 2),
                "interest" => number_format($interest, 2),
                "balance" => number_format($balance, 2)
            ];

            $previousCumulativeInterest = $cumulativeInterest;
        }
        return $amortization;
    }

    /**
     * @return type array
     */
    public function getResults() {
        // validate
        if (!$this->valid()) {
            return ["summary" => "n/a", "amortization" => "n/a"];
        }
        // normalize
        $rate = $this->rawRate / 100 / 12;
        $numberOfMonths = $this->numberOfYears * 12;
        // mortgage, total payment, total interest
        $monthlyMortgage = $this->calculateMonthlyMortgage($this->loanAmount, $rate, $numberOfMonths);
        $totalPayment = $monthlyMortgage * $numberOfMonths;
        $totalInterest = $totalPayment - $this->loanAmount;
        // get summary
        $mortgageSummary = $this->generateSummary($this->loanAmount, $monthlyMortgage, $totalPayment, $totalInterest);
        // get amortization table
        $amortization = $this->generateAmortization($this->loanAmount, $rate, $numberOfMonths, $monthlyMortgage);

        return ["summary" => $mortgageSummary, "amortization" => $amortization];
    }
}

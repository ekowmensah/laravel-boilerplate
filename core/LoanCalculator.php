<?php
namespace Core;

class LoanCalculator {
    
    /**
     * Calculate loan repayment schedule
     * 
     * @param array $params [
     *   'principal' => float,
     *   'interest_rate' => float,
     *   'loan_term' => int,
     *   'repayment_frequency' => int,
     *   'repayment_frequency_type' => string (days|weeks|months|years),
     *   'interest_methodology' => string (flat|declining_balance),
     *   'interest_rate_type' => string (day|week|month|year|principal),
     *   'disbursement_date' => string (Y-m-d),
     *   'first_payment_date' => string (Y-m-d) optional,
     *   'grace_on_principal_paid' => int,
     *   'grace_on_interest_paid' => int,
     *   'grace_on_interest_charged' => int
     * ]
     * @return array
     */
    public static function calculateSchedule($params) {
        $principal = $params['principal'];
        $interestRate = $params['interest_rate'];
        $loanTerm = $params['loan_term'];
        $repaymentFrequency = $params['repayment_frequency'];
        $repaymentFrequencyType = $params['repayment_frequency_type'];
        $interestMethodology = $params['interest_methodology'];
        $interestRateType = $params['interest_rate_type'];
        $disbursementDate = $params['disbursement_date'];
        $firstPaymentDate = $params['first_payment_date'] ?? null;
        $gracePrincipal = $params['grace_on_principal_paid'] ?? 0;
        $graceInterest = $params['grace_on_interest_paid'] ?? 0;
        $graceInterestCharged = $params['grace_on_interest_charged'] ?? 0;
        
        $schedule = [];
        $balance = $principal;
        
        // Convert interest rate to period rate
        $periodRate = self::convertInterestRate($interestRate, $interestRateType, $repaymentFrequencyType);
        
        // Calculate installment amount
        if ($interestMethodology === 'flat') {
            $totalInterest = ($principal * $interestRate * $loanTerm) / 100;
            $totalAmount = $principal + $totalInterest;
            $installmentAmount = $totalAmount / $loanTerm;
            $principalPerInstallment = $principal / $loanTerm;
            $interestPerInstallment = $totalInterest / $loanTerm;
        } else {
            // Declining balance
            if ($periodRate > 0) {
                $installmentAmount = $principal * ($periodRate * pow(1 + $periodRate, $loanTerm)) / (pow(1 + $periodRate, $loanTerm) - 1);
            } else {
                $installmentAmount = $principal / $loanTerm;
            }
        }
        
        // Start date for schedule
        $currentDate = new \DateTime($disbursementDate);
        if ($firstPaymentDate) {
            $currentDate = new \DateTime($firstPaymentDate);
        } else {
            // Calculate first payment date based on frequency
            self::addPeriod($currentDate, $repaymentFrequency, $repaymentFrequencyType);
        }
        
        for ($i = 1; $i <= $loanTerm; $i++) {
            $isGracePrincipal = $i <= $gracePrincipal;
            $isGraceInterest = $i <= $graceInterest;
            $isGraceInterestCharged = $i <= $graceInterestCharged;
            
            if ($interestMethodology === 'flat') {
                $principalDue = $isGracePrincipal ? 0 : $principalPerInstallment;
                $interestDue = $isGraceInterestCharged ? 0 : ($isGraceInterest ? 0 : $interestPerInstallment);
            } else {
                // Declining balance
                if ($isGraceInterestCharged) {
                    $interestDue = 0;
                } else {
                    $interestDue = $balance * $periodRate;
                }
                
                if ($isGracePrincipal) {
                    $principalDue = 0;
                } else {
                    $principalDue = $installmentAmount - $interestDue;
                    if ($principalDue > $balance) {
                        $principalDue = $balance;
                    }
                }
                
                if ($isGraceInterest) {
                    $interestDue = 0;
                }
            }
            
            $balance -= $principalDue;
            if ($balance < 0) $balance = 0;
            
            $schedule[] = [
                'installment' => $i,
                'from_date' => $i === 1 ? $disbursementDate : $schedule[$i-2]['due_date'],
                'due_date' => $currentDate->format('Y-m-d'),
                'month' => $currentDate->format('m'),
                'year' => $currentDate->format('Y'),
                'principal' => round($principalDue, 6),
                'interest' => round($interestDue, 6),
                'fees' => 0,
                'penalties' => 0,
                'total_due' => round($principalDue + $interestDue, 6),
                'principal_repaid_derived' => 0,
                'principal_written_off_derived' => 0,
                'interest_repaid_derived' => 0,
                'interest_written_off_derived' => 0,
                'interest_waived_derived' => 0,
                'fees_repaid_derived' => 0,
                'fees_written_off_derived' => 0,
                'fees_waived_derived' => 0,
                'penalties_repaid_derived' => 0,
                'penalties_written_off_derived' => 0,
                'penalties_waived_derived' => 0,
                'paid_by_date' => null
            ];
            
            // Move to next payment date
            if ($i < $loanTerm) {
                self::addPeriod($currentDate, $repaymentFrequency, $repaymentFrequencyType);
            }
        }
        
        return $schedule;
    }
    
    /**
     * Convert interest rate to period rate
     */
    private static function convertInterestRate($rate, $rateType, $frequencyType) {
        // Convert rate to decimal
        $rate = $rate / 100;
        
        // Convert to annual if needed
        switch ($rateType) {
            case 'day':
                $annualRate = $rate * 365;
                break;
            case 'week':
                $annualRate = $rate * 52;
                break;
            case 'month':
                $annualRate = $rate * 12;
                break;
            case 'year':
                $annualRate = $rate;
                break;
            case 'principal':
                return 0; // Flat rate, handled differently
            default:
                $annualRate = $rate;
        }
        
        // Convert to period rate
        switch ($frequencyType) {
            case 'days':
                return $annualRate / 365;
            case 'weeks':
                return $annualRate / 52;
            case 'months':
                return $annualRate / 12;
            case 'years':
                return $annualRate;
            default:
                return $annualRate / 12;
        }
    }
    
    /**
     * Add period to date
     */
    private static function addPeriod(\DateTime $date, $frequency, $type) {
        switch ($type) {
            case 'days':
                $date->modify("+{$frequency} days");
                break;
            case 'weeks':
                $date->modify("+{$frequency} weeks");
                break;
            case 'months':
                $date->modify("+{$frequency} months");
                break;
            case 'years':
                $date->modify("+{$frequency} years");
                break;
        }
    }
    
    /**
     * Calculate expected maturity date
     */
    public static function calculateMaturityDate($disbursementDate, $loanTerm, $repaymentFrequency, $repaymentFrequencyType) {
        $date = new \DateTime($disbursementDate);
        
        for ($i = 0; $i < $loanTerm; $i++) {
            self::addPeriod($date, $repaymentFrequency, $repaymentFrequencyType);
        }
        
        return $date->format('Y-m-d');
    }
    
    /**
     * Allocate repayment amount to principal, interest, fees, penalties
     */
    public static function allocateRepayment($amount, $loan, $schedule) {
        $allocation = [
            'penalties' => 0,
            'fees' => 0,
            'interest' => 0,
            'principal' => 0,
            'overpayment' => 0
        ];
        
        $remaining = $amount;
        
        // 1. Allocate to penalties first
        $penaltiesDue = $loan['penalties_outstanding_derived'];
        if ($remaining > 0 && $penaltiesDue > 0) {
            $allocation['penalties'] = min($remaining, $penaltiesDue);
            $remaining -= $allocation['penalties'];
        }
        
        // 2. Allocate to fees
        $feesDue = $loan['fees_outstanding_derived'];
        if ($remaining > 0 && $feesDue > 0) {
            $allocation['fees'] = min($remaining, $feesDue);
            $remaining -= $allocation['fees'];
        }
        
        // 3. Allocate to interest
        $interestDue = $loan['interest_outstanding_derived'];
        if ($remaining > 0 && $interestDue > 0) {
            $allocation['interest'] = min($remaining, $interestDue);
            $remaining -= $allocation['interest'];
        }
        
        // 4. Allocate to principal
        $principalDue = $loan['principal_outstanding_derived'];
        if ($remaining > 0 && $principalDue > 0) {
            $allocation['principal'] = min($remaining, $principalDue);
            $remaining -= $allocation['principal'];
        }
        
        // 5. Any remaining is overpayment
        if ($remaining > 0) {
            $allocation['overpayment'] = $remaining;
        }
        
        return $allocation;
    }
}

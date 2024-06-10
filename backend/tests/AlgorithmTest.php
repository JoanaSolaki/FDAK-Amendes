<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AlgorithmTest extends WebTestCase
{
    protected function validateIdTaxes(string $idTaxes): array {
        $currentDate = new \DateTime();
        $currentYear = $currentDate->format('Y');

        $letter1 = $idTaxes[0];
        $letter2 = $idTaxes[1];
        $year = substr($idTaxes, 2, 4);
        $parts = explode('_', $idTaxes);
        $num1 = (int)$parts[1];
        $num2 = (int)$parts[2];

        if (strcmp($letter1, $letter2) >= 0) {
            return [false, "La première lettre n'est pas avant la seconde dans l'alphabet."];
        }

        if ($year != $currentYear) {
            return [false, "L'année n'est pas la bonne."];
        }

        if ($num1 + $num2 != 100) {
            return [false, "Le total des deux derniers chiffres ne fait pas 100."];
        }

        return [true, "L'ID des taxes est valide."];
    }

    protected function isValidCardNumber($cardNumber): bool {
        $sum = 0;
        $shouldDouble = false;

        for ($i = strlen($cardNumber) - 1; $i >= 0; $i--) {
            $digit = intval($cardNumber[$i]);

            if ($shouldDouble) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit -= 9;
                }
            }
            $sum += $digit;
            $shouldDouble = !$shouldDouble;
        }
        return ($sum % 10) === 0;
    }

    public function testAlgoIdTaxesSuccess() :void {
        list($isValid, $message) = $this->validateIdTaxes('KW2024_22_78');
        $this->assertTrue($isValid, $message);
    }

    public function testAlgoIdTaxesFailed() :void {
        list($isValid, $message) = $this->validateIdTaxes('ZA2024_22_78');
        $this->assertFalse($isValid, $message);
    }

    public function testAlgoLuhnSuccess() :void {
        $this->assertTrue($this->isValidCardNumber('2994001147686804'), "Le numéro de carte est valide.");
    }

    public function testAlgoLuhnFailed() :void {
        $this->assertFalse($this->isValidCardNumber('299400114768680'), "Le numéro de carte n'est pas valide.");
    }
}
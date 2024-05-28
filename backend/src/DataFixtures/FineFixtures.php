<?php

namespace App\DataFixtures;

use App\DataFixtures\AbstractFixtures;
use App\Entity\Fine;
use Doctrine\Persistence\ObjectManager;

class FineFixtures extends AbstractFixtures {
    public function load(ObjectManager $objectManager) {
        $id_taxes = [
            "KW2024_22_78",
            "AB2024_17_83",
            "YZ2024_1_99",
            "DS2024_50_50",
            "JK2024_20_80",
            "HQ2024_37_63",
            "RS2024_48_52",
            "EN2024_29_71",
            "RO2024_34_66",
            "AC2024_23_77"
        ];

        $fines = [
            ["reason" => "Non respect des feux Kaijus.", 
            "amount" => 100],
            ["reason" => "Refus de se rendre à un abri.", 
            "amount" => 400],
            ["reason" => "Non-respect des consignes de sécurité lors des attaques", 
            "amount" => 300],
            ["reason" => "Refus d'obtempérer face à un membre des Forces de Défense Anti-Kaiju.", 
            "amount" => 450],
            ["reason" => "Obstruction des Forces de Défense Anti-Kaiju.", 
            "amount" => 1500],
            ["reason" => "Attaque non autorisée sur un Kaiju.", 
            "amount" => 700],
            ["reason" => "Obstruction des forces de défense Kaiju", 
            "amount" => 1000],
            ["reason" => "Usage inapproprié des systèmes d'alerte Kaiju.", 
            "amount" => 150],
            ["reason" => "Tentative de vol d'équipement du FDAK.", 
            "amount" => 2000],
            ["reason" => "Utilisation de drones en zones de combat Kaiju", 
            "amount" => 200]
        ];

        for ($i = 0; $i < 10; $i++) {
            $fine = new Fine();
            $currentFine = ($this->faker->randomElement($fines));
            $reason = $currentFine['reason'];
            $amount = $currentFine['amount'];
            $fine->setReason($reason);
            $fine->setAmount($amount);
            $fine->setIdTaxes($id_taxes[$i]);

            $this->setReference('fine_' . $i, $fine);

            $objectManager->persist($fine);
        }

        $objectManager->flush();
    }
}
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

        $reasons = [
            "Non respect des feux Kaijus.",
            "Refus de se rendre à un abri.",
            "Non-respect des consignes de sécurité lors des attaques",
            "Refus d'obtempérer face à un membre des Forces de Défense Anti-Kaiju.",
            "Obstruction des Forces de Défense Anti-Kaiju.",
            "Attaque non autorisée sur un Kaiju.",
            "Obstruction des forces de défense Kaiju",
            "Usage inapproprié des systèmes d'alerte Kaiju.",
            "Tentative de vol d'équipement du FDAK.",
            "Utilisation de drones en zones de combat Kaiju"
        ];

        $amounts = [
            100,
            400,
            300,
            450,
            1500,
            700,
            1000,
            150,
            2000,
            200
        ];

        for ($i = 0; $i < 10; $i++) {
            $fine = new Fine();
            $fine->setReason($reasons[$i]);
            $fine->setAmount($amounts[$i]);
            $fine->setIdTaxes($id_taxes[$i]);

            $this->setReference('fine_' . $i, $fine);

            $objectManager->persist($fine);
        }

        $objectManager->flush();
    }
}
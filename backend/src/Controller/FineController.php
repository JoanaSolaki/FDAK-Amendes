<?php

namespace App\Controller;

use App\Entity\Fine;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\FineRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

class FineController extends AbstractController
{
    private $fineRepository;

    public function __construct(FineRepository $fineRepository)
    {
        $this->fineRepository = $fineRepository;
    }

    private function validateIdTaxes(string $idTaxes): array
    {
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

        return [true, ""];
    }

    #[Route('/api/fines/IdTaxes/{idTaxes}', name: 'find_by_id_taxe', methods: ['GET'])]
    public function getTaxeByNumber(string $idTaxes, EntityManagerInterface $entityManager): JsonResponse
    {
        list($isValid, $error) = $this->validateIdTaxes($idTaxes);

        if (!$isValid) {
            return new JsonResponse(['message' => $error], 400);
        }

        $fine = $entityManager->getRepository(Fine::class)->findOneBy(["id_taxes" => $idTaxes]);

        if (!$fine) {
            return new JsonResponse(['message' => 'Aucune amende trouvée pour ce matricule.'], 404);
        }

        return new JsonResponse([
            "id" => $fine->getId(),
            "reason" => $fine->getReason(),
            "amount" => $fine->getAmount(),
            "id_taxes" => $fine->getIdTaxes()
        ]);
    }
}

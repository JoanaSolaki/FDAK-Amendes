<?php

namespace App\Controller;

use App\Entity\Fine;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\FineRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

class FineController extends AbstractController
{
    private $fineRepository;

    public function __construct(FineRepository $fineRepository)
    {
        $this->fineRepository = $fineRepository;
    }

    #[Route('/api/fines/IdTaxes/{idTaxes}', name: 'find_by_id_taxe', methods: ['GET'])]
    public function getTaxeByNumber(string $idTaxes, EntityManagerInterface $entityManager): JsonResponse
    {
        // print_r(gettype($idTaxes));
        // die;
        $fine = $entityManager->getRepository(Fine::class)->findOneBy(["id_taxes" => $idTaxes]);

        if (!$fine) {
            return new JsonResponse(['message' => 'Aucune amende trouvée pour ce matricule.'], 404);
        }

        // Vous pouvez formater la réponse selon vos besoins
        return new JsonResponse([
            "id" => $fine->getId(),
            "reason" => $fine->getReason(),
            "amount" => $fine->getAmount(),
            "id_taxes" => $fine->getIdTaxes()
        ]);
    }
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\FineRepository;

class FineController extends AbstractController
{
    private $fineRepository;

    public function __construct(FineRepository $fineRepository)
    {
        $this->fineRepository = $fineRepository;
    }

    public function searchByIdTaxes(Request $request): JsonResponse
    {
        $idTaxes = $request->getContent(); // Récupérer l'id_taxes à partir du corps de la requête

        $fine = $this->fineRepository->findOneByIdTaxes($idTaxes);

        if (!$fine) {
            return new JsonResponse(['message' => 'Aucune amende trouvée pour ce matricule.'], 404);
        }

        // Vous pouvez formater la réponse selon vos besoins
        return new JsonResponse($fine->toArray()); // Par exemple, convertir l'objet en tableau associatif
    }
}

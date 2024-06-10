<?php

namespace App\Controller;

use App\Entity\Paidment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\PaidmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

class PaidmentController extends AbstractController
{
    private $paidmentRepository;

    public function __construct(PaidmentRepository $paidmentRepository)
    {
        $this->paidmentRepository = $paidmentRepository;
    }

    private function isValidCardNumber($cardNumber): bool {
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

    #[Route('/api/paidments/check', name: 'post_fine', methods: ['POST'])]
    public function paidmentCheck(EntityManagerInterface $entityManager)
    {
        $paidment = new Paidment();

        // Est-ce que l'amende à été payée
        $existingPaidment = $this->paidmentRepository->findOneBy(['fine' => $paidment->getFine()]);
        if ($existingPaidment) {
            throw new \Exception('Cette amende a déjà été payée.');
        }

        // Si y'a un utilisateur et une amende
        if (null === $paidment->getUser() || null === $paidment->getFine()) {
            throw new BadRequestHttpException('Il faut un utilisateur et une amende relié.');
        }

        // Carte validation
        if (!$this->isValidCardNumber($paidment->getCard())) {
            throw new BadRequestHttpException('Numéro de carte invalide.');
        }

        //  Crypto validation
        if (strlen($paidment->getCrypto()) < 3 || strlen($paidment->getCrypto()) > 4) {
            throw new BadRequestHttpException('Cryptogramme invalide.');
        }

        // Exp_date validation
        if ($paidment->getExpDate() > new \DateTime()) {
            throw new BadRequestHttpException('Date d\'expiration invalide.');
        }

    }
}

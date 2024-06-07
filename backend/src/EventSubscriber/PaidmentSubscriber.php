<?php

namespace App\EventSubscriber;

use App\Entity\Paidment;
use App\Repository\PaidmentRepository;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use PHPUnit\Framework\Constraint\ExceptionMessage;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

final class PaidmentSubscriber implements EventSubscriber
{
    private $paidmentRepository;

    public function __construct(PaidmentRepository $paidmentRepository) {
        $this->paidmentRepository = $paidmentRepository;
    }

    public function getSubscribedEvents()
    {
        var_dump("getSubscribedEvents");
        die;
        return [
            Events::prePersist,
        ];
    }

    public function prePersist(LifecycleEventArgs $args) {
        $entity = $args->getObject();
        
        var_dump("Hello");
        die;

        // Est-ce que l'amende à été payée
        $existingPaidment = $this->paidmentRepository->findOneBy(['fine' => $entity->getFine()]);
        if ($existingPaidment) {
            throw new \Exception('Cette amende a déjà été payée.');
        }

        if (!$entity instanceof Paidment) {
            return;
        }

        // Si y'a un utilisateur et une amende
        if (null === $entity->getUser() || null === $entity->getFine()) {
            throw new BadRequestHttpException('Il faut un utilisateur et une amende relié.');
        }

        // Carte validation
        if (!$this->isValidCardNumber($entity->getCard())) {
            throw new BadRequestHttpException('Numéro de carte invalide.');
        }

        //  Crypto validation
        if (strlen($entity->getCrypto()) < 3 || strlen($entity->getCrypto()) > 4) {
            throw new BadRequestHttpException('Cryptogramme invalide.');
        }

        // Exp_date validation
        if ($entity->getExpDate() > new \DateTime()) {
            throw new BadRequestHttpException('Date d\'expiration invalide.');
        }
    }

    private function isValidCardNumber($cardNumber): bool {
        var_dump("isValidCardNumber");
        die;
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
}
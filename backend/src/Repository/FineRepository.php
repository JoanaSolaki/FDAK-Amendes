<?php

namespace App\Repository;

use App\Entity\Fine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ReportPlanet>
 *
 * @method Fine|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fine|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fine[]    findAll()
 * @method Fine[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FineRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fine::class);
    }

    //    /**
    //     * @return Fine[] Returns an array of Fine objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('f.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneByIdTaxes($idTaxes): ?Fine
    //    {
    //        $dbb = $this->getDb()->prepare('SELECT * FROM Fine WHERE id_taxes = :id_taxes');
    //        $dbb->bindValue(':id_taxes', $idTaxes);
    //        $dbb->setFetchMode(PDO::FETCH_CLASS, 'Fine');
    //        $dbb->execute();
    //        return $dbb->fetch();
    //        ;
    //    }
}

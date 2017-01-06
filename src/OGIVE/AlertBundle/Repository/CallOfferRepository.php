<?php

namespace OGIVE\AlertBundle\Repository;

use Doctrine\ORM\EntityRepository;
/**
 * CallOfferRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CallOfferRepository extends EntityRepository
{
    public function deleteCallOffer(\OGIVE\AlertBundle\Entity\CallOffer $callOffer) {
        $em= $this->_em;
        $callOffer->setStatus(0);
        $em->getConnection()->beginTransaction();
        try{
            $em->persist($callOffer);
            $em->flush();
            $em->getConnection()->commit();
        } catch (Exception $ex) {
            $em->getConnection()->rollback();
            $em->close();
            throw $ex;
        }
    }


    public function saveCallOffer(\OGIVE\AlertBundle\Entity\CallOffer $callOffer) {
        $em= $this->_em;
        $callOffer->setStatus(1);
        $em->getConnection()->beginTransaction();
        try{
            $em->persist($callOffer);
            $em->flush();
            $em->getConnection()->commit();
        } catch (Exception $ex) {
            $em->getConnection()->rollback();
            $em->close();
            throw $ex;
        }
        return $callOffer;
    }

    public function updateCallOffer(\OGIVE\AlertBundle\Entity\CallOffer $callOffer) {
        $em= $this->_em;
        $em->getConnection()->beginTransaction();
        try{
            $em->persist($callOffer);
            $em->flush();
            $em->getConnection()->commit();           
        } catch (Exception $ex) {
            $em->getConnection()->rollback();
            $em->close();
            throw $ex;
        }
        return $callOffer;
    }
    public function getAll() 
    {
        $qb = $this->createQueryBuilder('e');
        $qb->where('e.status = :status')
            ->orderBy('e.createDate', 'DESC')
            ->setParameter('status', 1);
        return $qb->getQuery()->getResult();
    }
    
    public function getCallOfferQueryBuilder() {
         return $this
          ->createQueryBuilder('e')
          ->where('e.status = :status')
          ->andWhere('e.state = :state')
          ->orderBy('e.name', 'ASC')
          ->setParameter('status', 1)
         ->setParameter('state', 1);

    }
}

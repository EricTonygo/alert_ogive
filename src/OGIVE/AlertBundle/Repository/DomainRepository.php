<?php

namespace OGIVE\AlertBundle\Repository;

/**
 * DomainRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DomainRepository extends \Doctrine\ORM\EntityRepository
{
     public function deleteDomain(\OGIVE\AlertBundle\Entity\Domain $domain) {
        $em = $this->_em;
        $domain->setStatus(0);
        $entreprise = new \OGIVE\AlertBundle\Entity\Entreprise();
        $repositoryEntreprise = $em->getRepository("OGIVE\AlertBundle:Entreprise");
        $procedureResult = new \OGIVE\AlertBundle\Entity\ProcedureResult();
        $repositoryProcedureResult = $em->getRepository("OGIVE\AlertBundle:ProcedureResult");
        $additive = new \OGIVE\AlertBundle\Entity\Additive();
        $repositoryAdditive = $em->getRepository("OGIVE\AlertBundle:Additive");
        $expressionInterest = new \OGIVE\AlertBundle\Entity\ExpressionInterest();
        $repositoryExpressionInterest = $em->getRepository("OGIVE\AlertBundle:ExpressionInterest");
        $callOffer = new \OGIVE\AlertBundle\Entity\CallOffer();
        $repositoryCallOffer = $em->getRepository("OGIVE\AlertBundle:CallOffer");
        $em->getConnection()->beginTransaction();
        try {
            $entreprises = $domain->getEntreprises();
            $additives = $repositoryAdditive->findBy(array('domain' => $domain, "status" =>1));
            $procedureResults = $repositoryProcedureResult->findBy(array('domain' => $domain, "status" =>1));
            $expressionInterests = $repositoryExpressionInterest->findBy(array('domain' => $domain, "status" =>1));
            $callOffers = $repositoryCallOffer->findBy(array('domain' => $domain, "status" =>1));
            foreach ($entreprise as $entreprises) {
                $entreprise->setDomain(null);
                $repositoryEntreprise->updateEntreprise($entreprise);
            }
            foreach ($callOffer as $callOffers) {
                $callOffer->setDomain(null);
                $repositoryCallOffer->updateCallOffer($callOffer);
            }
            foreach ($additive as $additives) {
                $additive->setDomain(null);
                $repositoryAdditive->updateAdditive($additive);
            }
            foreach ($procedureResult as $procedureResults) {
                $procedureResult->setDomain(null);
                $repositoryProcedureResult->updateProcedure($procedureResult);
            }
            foreach ($expressionInterest as $expressionInterests) {
                $expressionInterest->setDomain(null);
                $repositoryExpressionInterest->updateExpressionInterest($expressionInterest);
            }
            $em->persist($domain);
            $em->flush();
            $em->getConnection()->commit();
        } catch (Exception $ex) {
            $em->getConnection()->rollback();
            $em->close();
            throw $ex;
        }
    }

    public function saveDomain(\OGIVE\AlertBundle\Entity\Domain $domain) {
        $em = $this->_em;
        $domain->setStatus(1);
        $em->getConnection()->beginTransaction();
        try {
            $em->persist($domain);
            $em->flush();
            $em->getConnection()->commit();
        } catch (Exception $ex) {
            $em->getConnection()->rollback();
            $em->close();
            throw $ex;
        }
        return $domain;
    }

    public function updateDomain(\OGIVE\AlertBundle\Entity\Domain $domain) {
        $em = $this->_em;
        $em->getConnection()->beginTransaction();
        try {
            $em->persist($domain);
            $em->flush();
            $em->getConnection()->commit();
        } catch (Exception $ex) {
            $em->getConnection()->rollback();
            $em->close();
            throw $ex;
        }
        return $domain;
    }
    
    public function getAll() 
    {
        $qb = $this->createQueryBuilder('d');
        $qb->where('d.status = :status')
           ->setParameter('status', 1);
        return $qb->getQuery()->getResult();
    }
    
    public function getDomainQueryBuilder() {
         return $this
          ->createQueryBuilder('d')
          ->where('d.status = :status')
          ->orderBy('t.createDate', 'DESC')
          ->setParameter('status', 1);

    }
}

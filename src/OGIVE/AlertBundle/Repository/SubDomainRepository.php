<?php

namespace OGIVE\AlertBundle\Repository;

/**
 * SubDomainRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SubDomainRepository extends \Doctrine\ORM\EntityRepository {

    public function deleteSubDomain(\OGIVE\AlertBundle\Entity\SubDomain $subDomain) {
        $em = $this->_em;
        $subDomain->setStatus(0);
        $entreprise = new \OGIVE\AlertBundle\Entity\Entreprise();
        $repositoryEntreprise = $em->getRepository("OGIVEAlertBundle:Entreprise");
        $procedureResult = new \OGIVE\AlertBundle\Entity\ProcedureResult();
        $repositoryProcedureResult = $em->getRepository("OGIVEAlertBundle:ProcedureResult");
        $additive = new \OGIVE\AlertBundle\Entity\Additive();
        $repositoryAdditive = $em->getRepository("OGIVEAlertBundle:Additive");
        $expressionInterest = new \OGIVE\AlertBundle\Entity\ExpressionInterest();
        $repositoryExpressionInterest = $em->getRepository("OGIVEAlertBundle:ExpressionInterest");
        $callOffer = new \OGIVE\AlertBundle\Entity\CallOffer();
        $repositoryCallOffer = $em->getRepository("OGIVEAlertBundle:CallOffer");
        $em->getConnection()->beginTransaction();
        try {
            $entreprises = $subDomain->getEntreprises();
            $additives = $repositoryAdditive->findBy(array('subDomain' => $subDomain, "status" => 1));
            $procedureResults = $repositoryProcedureResult->findBy(array('subDomain' => $subDomain, "status" => 1));
            $expressionInterests = $repositoryExpressionInterest->findBy(array('subDomain' => $subDomain, "status" => 1));
            $callOffers = $repositoryCallOffer->findBy(array('subDomain' => $subDomain, "status" => 1));

            foreach ($entreprises as $entreprise) {
                $entreprise->removeSubDomain($subDomain);
                $repositoryEntreprise->updateEntreprise($entreprise);
            }

            foreach ($callOffers as $callOffer) {
                $callOffer->setSubDomain(null);
                $repositoryCallOffer->updateCallOffer($callOffer);
            }

            foreach ($additives as $additive) {
                $additive->setSubDomain(null);
                $repositoryAdditive->updateAdditive($additive);
            }

            foreach ($procedureResults as $procedureResult) {
                $procedureResult->setSubDomain(null);
                $repositoryProcedureResult->updateProcedureResult($procedureResult);
            }

            foreach ($expressionInterests as $expressionInterest) {
                $expressionInterest->setSubDomain(null);
                $repositoryExpressionInterest->updateExpressionInterest($expressionInterest);
            }
            $em->persist($subDomain);
            $em->flush();
            $em->getConnection()->commit();
        } catch (Exception $ex) {
            $em->getConnection()->rollback();
            $em->close();
            throw $ex;
        }
    }

    public function saveSubDomain(\OGIVE\AlertBundle\Entity\SubDomain $subDomain) {
        $em = $this->_em;
        $subDomain->setStatus(1);
        $em->getConnection()->beginTransaction();
        try {
            $em->persist($subDomain);
            $em->flush();
            $em->getConnection()->commit();
        } catch (Exception $ex) {
            $em->getConnection()->rollback();
            $em->close();
            throw $ex;
        }
        return $subDomain;
    }

    public function updateSubDomain(\OGIVE\AlertBundle\Entity\SubDomain $subDomain) {
        $em = $this->_em;
        $em->getConnection()->beginTransaction();
        try {
            $em->persist($subDomain);
            $em->flush();
            $em->getConnection()->commit();
        } catch (Exception $ex) {
            $em->getConnection()->rollback();
            $em->close();
            throw $ex;
        }
        return $subDomain;
    }

    public function getAll() {
        $qb = $this->createQueryBuilder('e');
        $qb->where('e.status = :status')
                ->orderBy('e.createDate', 'DESC')
                ->setParameter('status', 1);
        return $qb->getQuery()->getResult();
    }

    public function getSubDomainQueryBuilder() {
        return $this
            ->createQueryBuilder('e')
            ->where('e.status = :status')
            ->andWhere('e.state = :state')
            ->orderBy('e.name', 'ASC')
            ->setParameter('status', 1)
            ->setParameter('state', 1);
    }

}
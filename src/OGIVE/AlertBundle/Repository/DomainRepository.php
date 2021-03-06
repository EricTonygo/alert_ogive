<?php

namespace OGIVE\AlertBundle\Repository;

/**
 * DomainRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DomainRepository extends \Doctrine\ORM\EntityRepository {

    public function deleteDomain(\OGIVE\AlertBundle\Entity\Domain $domain) {
        $em = $this->_em;
        $domain->setStatus(0);
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
        $repositorySubDomain = $em->getRepository("OGIVEAlertBundle:SubDomain");
        $em->getConnection()->beginTransaction();
        try {
            $entreprises = $domain->getEntreprises();
            $additives = $repositoryAdditive->findBy(array('domain' => $domain, "status" => 1));
            $procedureResults = $repositoryProcedureResult->findBy(array('domain' => $domain, "status" => 1));
            $expressionInterests = $repositoryExpressionInterest->findBy(array('domain' => $domain, "status" => 1));
            $callOffers = $repositoryCallOffer->findBy(array('domain' => $domain, "status" => 1));
            $subDomains = $repositorySubDomain->findBy(array('domain' => $domain, "status" => 1));
            foreach ($entreprises as $entreprise) {
                $entreprise->removeDomain($domain);
                $repositoryEntreprise->updateEntreprise($entreprise);
            }

            foreach ($callOffers as $callOffer) {
                $callOffer->setDomain(null);
                $repositoryCallOffer->updateCallOffer($callOffer);
            }

            foreach ($additives as $additive) {
                $additive->setDomain(null);
                $repositoryAdditive->updateAdditive($additive);
            }

            foreach ($procedureResults as $procedureResult) {
                $procedureResult->setDomain(null);
                $repositoryProcedureResult->updateProcedureResult($procedureResult);
            }

            foreach ($expressionInterests as $expressionInterest) {
                $expressionInterest->setDomain(null);
                $repositoryExpressionInterest->updateExpressionInterest($expressionInterest);
            }

            foreach ($subDomains as $subDomain) {
                $repositorySubDomain->deleteSubdomain($subDomain);
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

    public function getAll($offset = null, $limit = null, $search_query = null) {
        $qb = $this->createQueryBuilder('e');
        $qb->where('e.status = ?1');
        if ($search_query) {
            $qb->andWhere(
                    $qb->expr()->orX(
                            $qb->expr()->like('lower(e.name)', '?2'), $qb->expr()->like('lower(e.description)', '?2')
            ));
        }
        $qb->orderBy('e.createDate', 'DESC');
        if ($search_query) {
            $qb->setParameters(array(1 => 1, 2 => '%' . strtolower($search_query) . '%'));
        } else {
            $qb->setParameters(array(1 => 1));
        }
        if ($offset) {
            $qb->setFirstResult($offset);
        }
        if ($limit) {
            $qb->setMaxResults($limit);
        }
        return $qb->getQuery()->getResult();
    }

    public function getAllByString($search_query = null) {
        
        $qb = $this->createQueryBuilder('e');
        $qb->where('e.status = ?1');
        if ($search_query) {
            $qb->andWhere(
                    $qb->expr()->orX(
                            $qb->expr()->like('lower(e.name)', '?2'), $qb->expr()->like('lower(e.description)', '?2')
            ));
        }
        $qb->orderBy('e.createDate', 'DESC');
        if ($search_query) {
            $search_query = strtolower($search_query);
            $qb->setParameters(array(1 => 1, 2 => '%' . strtolower($search_query) . '%'));
        } else {
            $qb->setParameters(array(1 => 1));
        }
        return $qb->getQuery()->getResult();
    }

    public function getDomainQueryBuilder() {
        return $this
        ->createQueryBuilder('e')
        ->where('e.status = :status')
        ->andWhere('e.state = :state')
        ->orderBy('e.name', 'ASC')
        ->setParameter('status', 1)
        ->setParameter('state', 1);
    }

}

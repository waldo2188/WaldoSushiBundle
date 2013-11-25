<?php

namespace Waldo\SushiBundle\EntityRepository;

use Doctrine\ORM\EntityRepository;


class SushiRepository extends EntityRepository {

    /**
     * Retourne la liste des aliments en fonctions de l'aliment parent sélectionné
     * @param int $selectedParent
     * @return Array
     */
    public function getAlimentsByHisParent($selectedParent = null) {
        if ($selectedParent != null) {
            $dql = $this->getAlimentsByHisParentQuery($selectedParent);
            $dql->resetDQLPart('select');
            $dql->select("a.ID, a.libelle");
            return $dql->getQuery()->getResult();
        }
        return null;
    }

    /** Retourne la liste des aliments en fonctions de l'aliment parent sélectionné
     * @param int $selectedParent
     * @return Array
     */
    public function getAlimentsByHisParentQuery($selectedParent = null) {
        $dql = $this->_em->createQueryBuilder();
        $dql->select('a')
                ->from('CnertaUranieBundle:AlimNiv2', 'a')
                ->where('a.alimNiv1 = :selectedParent')
                ->orderBy('a.libelle', 'ASC')
                ->setParameter('selectedParent', $selectedParent);
        return $dql;
    }

}


<?php
namespace UsaRugbyStats\AccountProfile\PersonalStats;

use UsaRugbyStats\Application\Service\AbstractService;
use ZfcUser\Entity\UserInterface;
use SoliantEntityAudit\Service\AuditService;
use SoliantEntityAudit\Entity\RevisionEntity;

class ExtensionService extends AbstractService
{
    /**
     * @var AuditService
     */
    protected $entityAuditService;

    protected $temporaryCache;

    public function getTimeseriesForUser(UserInterface $user)
    {
        if ( ! empty($this->temporaryCache[$user->getId()]) ) {
            return $this->temporaryCache[$user->getId()];
        }

        $class = 'UsaRugbyStats\AccountProfile\PersonalStats\ExtensionEntity';

        $entries = array();

        $svc = $this->getEntityAuditService();

        array_filter(
            $svc->getRevisionEntities($class),
            function ( RevisionEntity $re ) use ($user, $svc, &$entries) {
                $entity = $re->getAuditEntity();
                $entityValues = $svc->getEntityValues($entity);
                if ( $entityValues['account'] != $user->getId() ) {
                    return false;
                }
                $revision = $re->getRevision();

                array_push($entries, [
                    'stats' => $entityValues,
                    'modified_on' => $revision->getTimestamp(),
                    'modified_by' => $revision->getUser(),
                ]);
            }
        );

        uasort($entries, function($a,$b) {
            return $a['modified_on'] > $b['modified_on'] ? 1 : -1;
        });

        $this->temporaryCache[$user->getId()] = $entries;
        return $entries;
    }

    public function getEntityAuditService()
    {
        return $this->entityAuditService;
    }

    public function setEntityAuditService(AuditService $svc)
    {
        $this->entityAuditService = $svc;

        return $this;
    }

}

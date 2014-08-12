<?php
namespace UsaRugbyStats\Competition\DataImporter;

use UsaRugbyStats\DataImporter\Task\TaskInterface;
use Zend\Log\LoggerAwareInterface;
use Zend\Log\LoggerAwareTrait;
use UsaRugbyStats\Competition\Service\UnionService;
use UsaRugbyStats\Competition\Entity\Union;

class ImportUnionsTask implements TaskInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    protected $svcUnion;

    public function __construct(UnionService $svcUnion)
    {
        $this->svcUnion = $svcUnion;
    }

    public function execute(array $data)
    {
        $this->getLogger()->debug('Importing Union records...');

        // Allow manually setting the record identifer
        $metadata = $this->svcUnion->getObjectManager()->getClassMetadata('UsaRugbyStats\Competition\Entity\Union');
        $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);

        foreach ($data as $union) {
            $this->getLogger()->debug(" - {$union['name']}");

            $session = $this->svcUnion->startSession();
            $session->form = clone $this->svcUnion->getCreateForm();
            $session->entity = new Union();
            $this->svcUnion->prepare();

            $entity = $this->svcUnion->create(['union' => $union]);
            if (! $entity instanceof Union) {
                $this->getLogger()->crit("ERROR: Failed to create union: " . $union['name']);
                continue;
            }
            unset($entity);
        }
    }
}

<?php
namespace UsaRugbyStats\Competition\Traits;

use UsaRugbyStats\Competition\Service\UnionService;

trait UnionServiceTrait
{
    /**
     * @var UnionService
     */
    protected $unionService;

    public function getUnionService()
    {
        if ( is_null($this->unionService) ) {
            if ( ! method_exists($this, 'getServiceLocator') ) {
                return NULL;
            }
            $this->unionService = $this->getServiceLocator()->get(
                'usarugbystats_competition_union_service'
            );
        }

        return $this->unionService;
    }

    public function setUnionService(UnionService $svc)
    {
        $this->unionService = $svc;

        return $this;
    }
}

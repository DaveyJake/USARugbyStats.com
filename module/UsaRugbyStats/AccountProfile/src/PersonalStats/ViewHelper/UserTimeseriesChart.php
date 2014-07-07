<?php
namespace UsaRugbyStats\AccountProfile\PersonalStats\ViewHelper;

use Zend\View\Helper\AbstractHelper;
use UsaRugbyStats\Application\Entity\AccountInterface;
use UsaRugbyStats\AccountProfile\PersonalStats\ExtensionService;

class UserTimeseriesChart extends AbstractHelper
{
    /**
     * @var ExtensionService
     */
    protected $extensionService;

    public function __construct(ExtensionService $svc)
    {
        $this->extensionService = $svc;
    }

    public function __invoke(AccountInterface $acct)
    {
        return $this->getView()->render('usa-rugby-stats/account-profile/personal-stats/user-timeseries-chart', [
            'timeseries' => $this->extensionService->getTimeseriesForUser($acct),
        ]);
    }

}

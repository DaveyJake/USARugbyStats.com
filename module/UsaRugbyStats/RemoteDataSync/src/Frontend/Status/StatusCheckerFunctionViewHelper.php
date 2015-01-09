<?php
namespace UsaRugbyStats\RemoteDataSync\Frontend\Status;

use Zend\View\Helper\AbstractHelper;

class StatusCheckerFunctionViewHelper extends AbstractHelper
{
    protected $hasRun = array();

    public function __invoke($id)
    {
        // Only run once per ID
        if (isset($this->hasRun[$id])) {
            return;
        }
        $this->hasRun[$id] = true;

        $statusUrl = $this->view->url('usarugbystats_remotedatasync_queue_trigger_statuscheck', [], ['query' => ['id' => 'XXXXX']]);
        $javascript = <<<EOB
function ursRemoteDataSyncWatchJobStatus_{$id}(data, completedCallback)
{
    var statusUrl = '{$statusUrl}'.replace(/=XXXXX$/, '=' + data.token);
    var runner = setInterval(function () {
        $.ajax({
          url: statusUrl,
          data: [],
          dataType: 'json',
          async: false,
          success: function (data) {
            if (typeof data.token == 'undefined') {
              $('#{$id} .urs-remote-data-sync').hide();
              $('#{$id} .urs-remote-data-sync-3').show();
              clearInterval(runner);
              completedCallback({token: null, status: 0});

              return;
            }

            $('#{$id} .urs-remote-data-sync').hide();
            $('#{$id} .urs-remote-data-sync-' + data.status).show();

            if (data.status == 3 || data.status == 4) {
              clearInterval(runner);
              completedCallback(data);
            }
            if (data.status == 4) {
                setTimeout(function () { $('#{$id}').modal('hide'); }, 5000);
            }
          },
          failure: function () {
            $('#{$id} .urs-remote-data-sync').hide();
            $('#{$id} .urs-remote-data-sync-3').show();
            clearInterval(runner);
            completedCallback({token: null, status: 0});
          }
        });
    }, 500);
}
EOB;

        $this->view->inlineScript()->appendScript($javascript);
    }
}

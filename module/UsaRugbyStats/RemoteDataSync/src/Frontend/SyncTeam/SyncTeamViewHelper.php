<?php
namespace UsaRugbyStats\RemoteDataSync\Frontend\SyncTeam;

use Zend\View\Helper\AbstractHelper;

class SyncTeamViewHelper extends AbstractHelper
{
    public function __invoke($team, $id, $buttonSelector)
    {
        $syncUrl = $this->view->url('usarugbystats_remotedatasync_queue_trigger_syncteam', [], ['query' => ['id' => $team->getId()]]);

        // @TODO everything below here is not specific to 'sync_team', and so could probably be extracted to a superclass

        $javascript = <<<EOB
$(document).ready(function () {
    $('#{$id}').modal({ show: false });
    $('{$buttonSelector}').click(function () {
        $('#{$id}').modal('show');
        $('#{$id} .urs-remote-data-sync').hide();
        $('#{$id} .urs-remote-data-sync-1').show();

        $.get('{$syncUrl}', [], function (data) {
            if (typeof data.token == 'undefined') {
                $('#{$id} .urs-remote-data-sync').hide();
                $('#{$id} .urs-remote-data-sync-3').show();

                return;
            }

            $('#{$id} .urs-remote-data-sync').hide();
            $('#{$id} .urs-remote-data-sync-' + data.status).show();

            if (data.status == 4) {
                setTimeout(function () {  $('#{$id}').modal('hide'); }, 5000);
            }
            if (data.status == 3 || data.status == 4) {
                setTimeout(function() { document.location.reload(true); }, 5000);
                return;
            }

            ursRemoteDataSyncWatchJobStatus(data, function(data) {
                if (data.status == 3 || data.status == 4) {
                    setTimeout(function() { document.location.reload(true); }, 5000);
                }
            });

        }, 'json');
    });
});
EOB;

        $html = <<<EOB
<div id="{$id}" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content" style="padding-top: 10px !important;" >
      <div class="modal-body"style="text-align:center">
        <p class="urs-remote-data-sync urs-remote-data-sync-1" style="font-size: 2em"><i class="glyphicon glyphicon-refresh"></i> Waiting for sync to start...</p>
        <p class="urs-remote-data-sync urs-remote-data-sync-2" style="font-size: 2em; display: none"><i class="glyphicon glyphicon-refresh"></i> Sync is running...</p>
        <p class="urs-remote-data-sync urs-remote-data-sync-3" style="font-size: 2em; display: none"><i class="glyphicon glyphicon-refresh"></i> Sync has failed :(</p>
        <p class="urs-remote-data-sync urs-remote-data-sync-4" style="font-size: 2em; display: none"><i class="glyphicon glyphicon-refresh"></i> Sync has completed!</p>
      </div>
    </div>
  </div>
</div>
EOB;

        $this->view->inlineScript()->appendScript($javascript);

        $this->view->ursRemoteDataSyncJobStatusCheckerFunction($id);

        return $html;
    }
}

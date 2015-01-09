<?php
namespace UsaRugbyStats\RemoteDataSync\Frontend\SyncTeam;

use Zend\View\Helper\AbstractHelper;

class SyncTeamViewHelper extends AbstractHelper
{
    protected $syncFunctionInjected = array();

    public function __invoke($id = 'TeamRosterSync')
    {
        $syncUrl = $this->view->url('usarugbystats_remotedatasync_queue_trigger_syncteam', [], ['query' => ['id' => 'XXXXXX']]);

        if ( isset($this->syncFunctionInjected[$id]) ) {
            return;
        }

        $syncFunction = <<<EOB

$(document).ready(function () {
    $('#{$id}').modal({ show: false });
});

function ursRemoteDataSyncTriggerSyncTeam_{$id}(teamid, callback) {
    $('#{$id}').modal('show');
    $('#{$id} .urs-remote-data-sync').hide();
    $('#{$id} .urs-remote-data-sync-1').show();

    $.get('{$syncUrl}'.replace(/XXXXXX/, teamid), [], function (data) {
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
            setTimeout(function () { document.location.reload(true); }, 5000);

            return;
        }

        ursRemoteDataSyncWatchJobStatus_{$id}(data, function (data) {
            if (data.status == 3 || data.status == 4) {
                callback(data);
            }
        });

    }, 'json');
};
EOB;
        $this->view->inlineScript()->appendScript($syncFunction);

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

        $this->view->ursRemoteDataSyncJobStatusCheckerFunction($id);

        $this->syncFunctionInjected[$id] = true;

        return $html;
    }
}

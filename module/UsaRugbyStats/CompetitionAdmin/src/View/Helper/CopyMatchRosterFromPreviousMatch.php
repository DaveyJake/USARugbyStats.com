<?php
namespace UsaRugbyStats\CompetitionAdmin\View\Helper;

use Zend\View\Helper\AbstractHelper;

class CopyMatchRosterFromPreviousMatch extends AbstractHelper
{
    protected $hasRun = array();

    public function __invoke($id, $syncUrl)
    {
        if ( isset($this->hasRun[$id]) ) {
            return;
        }
        $this->hasRun[$id] = true;

        $javascript = <<<EOB
$(document).ready(function () {
    $('#{$id}').modal({ show: false });
});

function ursCopyMatchRosterFromPreviousMatch_{$id}(teamid, callback) {
    $('#{$id}').modal('show');
    $('#{$id} .urs-competition-match-roster-copy').hide();
    $('#{$id} .urs-competition-match-roster-copy-1').show();

    var button = $(this);

    $.ajax({
        url: "{$syncUrl}".replace(/XXXXXX/g, teamid),
        data: [],
        success: function (data) {
            if (typeof data.roster == 'undefined' || data.roster.length == 0) {
                $('#{$id} .urs-competition-match-roster-copy').hide();
                $('#{$id} .urs-competition-match-roster-copy-2').show();
            } else {

                $('#{$id} .urs-competition-match-roster-copy-3 span').html(
                    '<a href="' + data.match.url + '" target="_blank">Match #' + data.match.id + ' (' + data.match.title + ')</a>'
                );

                $('#{$id} .urs-competition-match-roster-copy').hide();
                $('#{$id} .urs-competition-match-roster-copy-3').show();
            }

            callback(data);
            setTimeout(function () { $('#{$id}').modal('hide'); }, 5000);
        },
        error: function (data) {
            $('#{$id} .urs-competition-match-roster-copy').hide();
            $('#{$id} .urs-competition-match-roster-copy-2').show();
        },
        dataType: 'json'
    });
}
EOB;

        $html = <<<EOB
<div id="{$id}" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" style="width: 500px; font-size: 10px !important;">
    <div class="modal-content" style="padding-top: 10px !important;" >
      <div class="modal-body"style="text-align:center">
        <p class="urs-competition-match-roster-copy urs-competition-match-roster-copy-1" style="font-size: 2em"><i class="glyphicon glyphicon-refresh"></i> Retrieving Roster...</p>
        <p class="urs-competition-match-roster-copy urs-competition-match-roster-copy-2" style="font-size: 2em; display: none"><i class="glyphicon glyphicon-remove"></i> Failed to load previous match data</p>
        <p class="urs-competition-match-roster-copy urs-competition-match-roster-copy-3" style="font-size: 2em; display: none"><i class="glyphicon glyphicon-ok"></i> Loaded data from <span></span></p>
      </div>
    </div>
  </div>
</div>
EOB;

        $this->view->inlineScript()->appendScript($javascript);

        return $html;
    }
}

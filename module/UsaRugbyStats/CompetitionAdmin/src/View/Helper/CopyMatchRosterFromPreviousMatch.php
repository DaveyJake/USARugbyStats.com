<?php
namespace UsaRugbyStats\CompetitionAdmin\View\Helper;

use Zend\View\Helper\AbstractHelper;
use UsaRugbyStats\Competition\Entity\Team;

class CopyMatchRosterFromPreviousMatch extends AbstractHelper
{
    public function __invoke(Team $team, $id, $syncUrl, $triggerButtonSelector)
    {
        $javascript = <<<EOB
$(document).ready(function () {
    $('#{$id}').modal({ show: false });
    $('{$triggerButtonSelector}').click(function () {
        $('#{$id}').modal('show');
        $('#{$id} .urs-competition-match-roster-copy').hide();
        $('#{$id} .urs-competition-match-roster-copy-1').show();

        var button = $(this);

        $.ajax({
            url: '{$syncUrl}',
            data: [],
            success: function (data) {
                if (typeof data.roster == 'undefined' || data.roster.length == 0) {
                    $('#{$id} .urs-competition-match-roster-copy').hide();
                    $('#{$id} .urs-competition-match-roster-copy-2').show();

                    return;
                }

                $('#{$id} .urs-competition-match-roster-copy-3 span').html(
                    '<a href="' + data.match.url + '" target="_blank">Match #' + data.match.id + ' (' + data.match.title + ')</a>'
                );

                $('#{$id} .urs-competition-match-roster-copy').hide();
                $('#{$id} .urs-competition-match-roster-copy-3').show();

                $.each(data.roster, function (position, player) {
                    var playerSelect = button.closest('.competition-match-team-roster')
                                             .find('.competition-match-team-roster-slot-'+position+' .column-player select');
                    if (playerSelect.length > 0) {
                        playerSelect.val(player);
                    }
                });
                setTimeout(function () { $('#{$id}').modal('hide'); }, 5000);
            },
            error: function (data) {
                $('#{$id} .urs-competition-match-roster-copy').hide();
                $('#{$id} .urs-competition-match-roster-copy-2').show();
            },
            dataType: 'json'
        });
    });
});
EOB;

        $html = <<<EOB
<div id="{$id}" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm">
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

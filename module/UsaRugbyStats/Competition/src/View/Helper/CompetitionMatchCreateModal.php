<?php
namespace UsaRugbyStats\Competition\View\Helper;

use Zend\View\Helper\AbstractHelper;
use UsaRugbyStats\Competition\Entity\Competition\Match;
use ZfcRbac\Service\AuthorizationServiceInterface;
use UsaRugbyStats\Competition\Service\Competition\MatchService;

class CompetitionMatchCreateModal extends AbstractHelper
{
    protected $authService;
    protected $matchService;

    public function __construct(AuthorizationServiceInterface $authService, MatchService $s)
    {
        $this->authService = $authService;
        $this->matchService = $s;
    }

    public function __invoke($competition, $tableSelector = '#schedule body')
    {
        if ( ! $this->authService->isGranted('competition.competition.match.create', $competition) ) {
            return;
        }

        $session = $this->matchService->startSession();
        $session->form = $this->matchService->getCreateForm();
        $session->competition = $competition;
        $session->entity = new Match();
        $this->matchService->prepare();

        $this->view->placeholder('form')->captureStart();
        $session->form->get('match')->get('competition')->setValue($competition->getId());
        $session->form->prepare();
        $fieldset = $session->form->get('match');
?>
<div class="well" style="margin: 20px">
  <h4 style="margin-top: 0; margin-bottom:20px">Quick-Add Match</h4>
    <div id="AsyncMatchAddSpinner" class="alert alert-info" style="display:none"><i class="glyphicon glyphicon-refresh"></i> Sending your request.  Please wait...</div>
    <div id="AsyncMatchAddedSuccessfully" class="alert alert-success" style="display:none"><i class="glyphicon glyphicon-ok"></i> Match added successfully!</div>
    <div id="AsyncMatchAddForm">
        <div class="row">
            <div class="col-xs-12 col-sm-4 col-md-2" style="vertical-align:top; padding: 4px; ">

                <?php $element = $fieldset->get('date_date'); ?>
                <?php $element->setLabelAttributes(array('class' => 'control-label input-sm', 'style' => 'display:inline !important')); ?>
                <?php $element->setAttribute('class', 'form-control input-sm'); ?>
                <?php $element->setAttribute('style', 'max-width: 95%;'); ?>
                <?php echo $this->view->formLabel($element) ?><br />
                <?php echo $this->view->formElement($element) ?>

                 <span class="help-block error-message" style="display:none"></span>
                <script type="text/javascript">
                    $(function () {
                        $('input[name=match\\[date_date\\]]').datetimepicker({
                            pickTime: false,
                            format: 'YYYY-MM-DD'
                        });
                    });
                </script>

            </div>

            <div class="col-xs-12 col-sm-4 col-md-3" style="vertical-align:top; padding: 4px;">
                <?php $element = $fieldset->get('teams')->get('H')->get('team'); ?>
                <?php $element->setLabel('Home Team'); ?>
                <?php $element->setLabelAttributes(array('class' => 'control-label input-sm', 'style' => 'display:inline !important')); ?>
                <?php $element->setAttribute('class', 'form-control input-sm'); ?>
                <?php $element->setAttribute('style', 'max-width: 95%;'); ?>

                <?php echo $this->view->formLabel($element) ?><br />
                <?php echo $this->view->formElement($element) ?>
                <span class="help-block error-message" style="display:none"></span>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-3" style="vertical-align:top; padding: 4px;">
                <?php $element = $fieldset->get('teams')->get('A')->get('team'); ?>
                <?php $element->setLabel('Away Team'); ?>
                <?php $element->setLabelAttributes(array('class' => 'control-label input-sm', 'style' => 'display:inline !important;')); ?>
                <?php $element->setAttribute('class', 'form-control input-sm'); ?>
                <?php $element->setAttribute('style', 'max-width: 95%;'); ?>

                <?php echo $this->view->formLabel($element) ?><br />
                <?php echo $this->view->formElement($element) ?>
                <span class="help-block error-message" style="display:none"></span>
            </div>

            <div class="col-xs-12 col-sm-4 col-md-2" style="vertical-align:top; padding: 4px;">
                <?php $element = $fieldset->get('date_time'); ?>
                <?php $element->setLabelAttributes(array('class' => 'control-label input-sm', 'style' => 'display:inline !important')); ?>
                <?php $element->setAttribute('class', 'form-control input-sm'); ?>
                <?php $element->setAttribute('style', 'max-width: 95%;'); ?>
                <?php echo $this->view->formLabel($element) ?><br />
                <?php echo $this->view->formElement($element) ?>
                <span class="help-block error-message" style="display:none"></span>
            </div>

            <div class="col-xs-12 col-sm-4 col-md-2" style="vertical-align:top; padding: 4px;">
                <?php $element = $fieldset->get('timezone'); ?>
                <?php $element->setLabelAttributes(array('class' => 'control-label input-sm', 'style' => 'display:inline !important')); ?>
                <?php $element->setAttribute('class', 'form-control input-sm'); ?>
                <?php echo $this->view->formLabel($element) ?><br />
                <?php echo $this->view->formElement($element) ?><br />

                <p class="help-block error-message" style="display:none;"></p>
                <script type="text/javascript">
                    $(function () {
                        $('input[name=match\\[date_time\\]]').datetimepicker({
                            pickDate: false,
                            format: 'h:mm A'
                        });
                    });
                </script>

            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-4 col-md-3" style="vertical-align:top; padding: 4px;">
                <?php $element = $fieldset->get('location'); ?>
                <?php $element->setLabelAttributes(array('class' => 'control-label input-sm', 'style' => 'display:inline !important')); ?>
                <?php $element->setAttribute('class', 'form-control input-sm'); ?>
                <?php $element->setAttribute('style', 'max-width: 95%;'); ?>
                <?php echo $this->view->formLabel($element) ?><br />
                <?php echo $this->view->formElement($element) ?><br />

                <div class="text-center"><a class="btn btn-info btn-sm" id="AddLocationButton"><i class="glyphicon glyphicon-plus-sign"></i> Add a Location</a></div>
            </div>

            <div class="col-xs-12 col-sm-4 col-md-3" style="vertical-align:top; padding: 4px;">
                <?php $element = $fieldset->get('locationDetails'); ?>
                <?php $element->setLabelAttributes(array('class' => 'control-label input-sm', 'style' => 'display:inline !important')); ?>
                <?php $element->setAttribute('class', 'form-control input-sm'); ?>
                <?php $element->setAttribute('style', 'max-width: 95%;'); ?>
                <?php echo $this->view->formLabel($element) ?><br />
                <?php echo $this->view->formElement($element) ?>

                <span class="help-block error-message" style="display:none"></span>
            </div>

            <div class="col-xs-12 col-md-6 text-right" style="margin-top: 20px">
                <button id="MatchQuickAdd" class="btn btn-primary">Add Match!</button>
            </div>
        </div>

        <div id="AddLocationModal" class="modal fade">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Add New Location</h4>
              </div>
              <div class="modal-body geocomplete-details">
                <div id="AsyncLocationAddSpinner" class="alert alert-info" style="display:none"><i class="glyphicon glyphicon-refresh"></i> Sending your request.  Please wait...</div>
                <div id="AsyncLocationAddedSuccessfully" class="alert alert-success" style="display:none"><i class="glyphicon glyphicon-ok"></i> Location added successfully!</div>
                <form id="AsyncLocationAddForm" class="form-horizontal" role="form">
                    <div class="row form-group">
                        <label class="col-sm-3 control-label" for="location[name]">Name</label>
                        <div class="col-sm-9">
                            <input type="text" name="location[name]" class="form-control" value="">
                            <span class="help-block error-message" style="display:none"></span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-sm-3 control-label" for="location[address]">Address</label>
                        <div class="col-sm-9">
                            <textarea name="location[address]" class="form-control"></textarea>
                            <span class="help-block error-message" style="display:none"></span>
                            <div class="map_canvas" style="width:100%; height: 200px;"></div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-sm-3 control-label" for="location[coordinates]">Coordinates</label>
                        <div class="col-sm-9">
                            <input type="text" name="location[coordinates]" class="form-control" value="" data-geocomplete-detail="location">
                            <span class="help-block error-message" style="display:none"></span>
                        </div>
                    </div>
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button id="AddLocationSaveButton" type="button" class="btn btn-primary">Save</button>
              </div>
            </div><!-- /.modal-content -->
          </div><!-- /.modal-dialog -->
        </div>
    </div>
</div>
<?php
        $this->view->placeholder('form')->captureEnd();

        $locationCreateUrl = $this->view->url('usarugbystats_competition-api_location');

        $matchCreateUrl = $this->view->url('usarugbystats_competition-api_competition_match', ['cid' => $competition->getId()]);
        $matchRenderUrl = $this->view->url('usarugbystats_frontend_competition_match/render-match-row', ['cid' => $competition->getId(), 'mid' => 'XXXXXX']);
        $addMatchJavascript = <<<JSBLOCK

function addErrorMessage(field, messages)
{
    if (typeof messages == 'undefined') {
        return;
    }
    $(field).parent().addClass('has-error');
    var errContainer = $(field).parent().find('.error-message').text("");
    $.each(messages, function (k,v) {
        errContainer.append(v).show();
    });
}

$('#AddLocationModal').modal({show: false}).on('shown.bs.modal', function () {
  google.maps.event.trigger($("*[name=location\\\\[address\\\\]]").geocomplete('map'), 'resize');
})

$("*[name=location\\\\[address\\\\]]").geocomplete({
  map: ".map_canvas",
  details: ".geocomplete-details",
  detailsAttribute: "data-geocomplete-detail",
  types: ["geocode", "establishment"]
});

$('#AddLocationButton').click(function() {
    $('#AddLocationModal').modal('show');
});
$('#AddLocationSaveButton').click(function() {
    $('#AsyncLocationAddSpinner').show();
    $('#AsyncLocationAddForm').hide().removeClass('has-error');
    $('#AsyncLocationAddForm .error-message').hide();

    var payload = {
        'location[name]': $('*[name=location\\\\[name\\\\]]').val(),
        'location[address]': $('*[name=location\\\\[address\\\\]]').val(),
        'location[coordinates]': $('*[name=location\\\\[coordinates\\\\]]').val()
    };

    $.ajax({
        type: "POST",
        url: "{$locationCreateUrl}",
        data: payload,
        dataType: "json"
    }).done(function (data) {
        $('#AsyncLocationAddSpinner').hide();
        $('#AsyncLocationAddForm').show();
        $('#AsyncLocationAddedSuccessfully').show();

        $('*[name=match\\\\[location\\\\]]').append($('<option>', {
            text: data.location.name,
            value: data.location.id
        }));
        $('*[name=match\\\\[location\\\\]]').val(data.location.id);

        setTimeout(function () {
            $('#AsyncLocationAddedSuccessfully').hide();
            $('#AddLocationModal').modal('hide');
        }, 5000);
    }).fail(function (xhr, status) {
        try { addErrorMessage('*[name=location\\\\[name\\\\]]', xhr.responseJSON.validation_messages.location.name); } catch (e) {};
        try { addErrorMessage('*[name=location\\\\[address\\\\]]', xhr.responseJSON.validation_messages.location.address); } catch (e) {};
        try { addErrorMessage('*[name=location\\\\[coordinates\\\\]]', xhr.responseJSON.validation_messages.location.coordinates); } catch (e) {};

        $('#AsyncLocationAddSpinner').hide();
        $('#AsyncLocationAddForm').show();
    });

});


$('#MatchQuickAdd').click(function () {
    $('#AsyncMatchAddSpinner').show();
    $('#AsyncMatchAddForm').hide();

    var payload = {
        'match[date_date]': $('*[name=match\\\\[date_date\\\\]]').val(),
        'match[date_time]': $('*[name=match\\\\[date_time\\\\]]').val(),
        'match[timezone]': $('*[name=match\\\\[timezone\\\\]]').val(),
        'match[location]': $('*[name=match\\\\[location\\\\]]').val(),
        'match[locationDetails]': $('*[name=match\\\\[locationDetails\\\\]]').val(),
        'match[teams][H][team]': $('*[name=match\\\\[teams\\\\]\\\\[H\\\\]\\\\[team\\\\]]').val(),
        'match[teams][A][team]': $('*[name=match\\\\[teams\\\\]\\\\[A\\\\]\\\\[team\\\\]]').val(),
    };

    $('#AsyncMatchAddForm *').removeClass('has-error');
    $('#AsyncMatchAddForm .error-message').hide();

    $.ajax({
        type: "POST",
        url: "{$matchCreateUrl}",
        data: payload,
        dataType: "json"
    }).done(function (data) {
        $.ajax({
            type: "GET",
            url: "{$matchRenderUrl}".replace('XXXXXX', data.match.id),
            data: {relativeToCompetition: '{$competition->getId()}'},
            dataType: "html"
        }).done(function (data) {
            $('{$tableSelector}').append(data);
        }).always(function () {
            $('#AsyncMatchAddSpinner').hide();
            $('#AsyncMatchAddForm').show();
            $('#AsyncMatchAddedSuccessfully').show();
            setTimeout(function () { $('#AsyncMatchAddedSuccessfully').hide(); }, 5000);
        });
    }).fail(function (xhr, status) {
        try { addErrorMessage('*[name=match\\\\[date_date\\\\]]', xhr.responseJSON.validation_messages.match.date_date); } catch (e) {};
        try { addErrorMessage('*[name=match\\\\[date_time\\\\]]', xhr.responseJSON.validation_messages.match.date_time); } catch (e) {};
        try { addErrorMessage('*[name=match\\\\[timezone\\\\]]', xhr.responseJSON.validation_messages.match.timezone); } catch (e) {};
        try { addErrorMessage('*[name=match\\\\[location\\\\]]', xhr.responseJSON.validation_messages.match.location); } catch (e) {};
        try { addErrorMessage('*[name=match\\\\[locationDetails\\\\]]', xhr.responseJSON.validation_messages.match.locationDetails); } catch (e) {};
        try { addErrorMessage('*[name=match\\\\[teams\\\\]\\\\[H\\\\]\\\\[team\\\\]]', xhr.responseJSON.validation_messages.match.teams.H.team); } catch (e) {};
        try { addErrorMessage('*[name=match\\\\[teams\\\\]\\\\[A\\\\]\\\\[team\\\\]]', xhr.responseJSON.validation_messages.match.teams.A.team); } catch (e) {};

        $('#AsyncMatchAddSpinner').hide();
        $('#AsyncMatchAddForm').show();
    });
});
JSBLOCK;
        $this->view->inlineScript()->appendScript($addMatchJavascript);

        return $this->view->placeholder('form');

    }
}

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
    </div>
</div>
<?php
        $this->view->placeholder('form')->captureEnd();

        $matchCreateUrl = $this->view->url('usarugbystats_competition-api_competition_match', ['cid' => $competition->getId()]);
        $matchRenderUrl = $this->view->url('usarugbystats_frontend_competition_match/render-match-row', ['cid' => $competition->getId(), 'mid' => 'XXXXXX']);
        $addMatchJavascript = <<<JSBLOCK
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

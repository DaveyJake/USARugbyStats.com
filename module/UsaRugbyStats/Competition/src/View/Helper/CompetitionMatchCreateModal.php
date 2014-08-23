<?php
namespace UsaRugbyStats\Competition\View\Helper;

use Zend\View\Helper\AbstractHelper;
use UsaRugbyStats\Competition\Entity\Competition\Match;
use Zend\Form\FormInterface;
use ZfcRbac\Service\AuthorizationServiceInterface;

class CompetitionMatchCreateModal extends AbstractHelper
{
    protected $authService;
    protected $form;

    public function __construct(AuthorizationServiceInterface $authService, FormInterface $form)
    {
        $this->authService = $authService;
        $this->form = $form;
    }

    public function __invoke($competition, $tableSelector = '#schedule body')
    {
        if ( ! $this->authService->isGranted('competition.competition.match.create', $competition) ) {
            return;
        }

        $this->view->placeholder('form')->captureStart();
        $this->form->get('match')->get('competition')->setValue($competition->getId());
        $this->form->prepare();
        $fieldset = $this->form->get('match');
?>
<div class="well" style="margin: 20px">
  <h4 style="margin-top: 0; margin-bottom:20px">Quick-Add Match</h4>
    <div id="AsyncMatchAddSpinner" class="alert alert-info" style="display:none"><i class="glyphicon glyphicon-refresh"></i> Sending your request.  Please wait...</div>
    <div id="AsyncMatchAddForm" class="form-inline">
        <div class="form-group" style="vertical-align:top">
            <?php $element = $fieldset->get('date'); ?>
            <?php $element->setLabelAttributes(array('class' => 'control-label input-sm', 'style' => 'display:inline !important')); ?>
            <?php $element->setAttribute('class', 'form-control input-sm'); ?>

            <?php echo $this->view->formLabel($element) ?><br />
            <?php echo $this->view->formElement($element) ?>
            <span class="help-block error-message" style="display:none"></span>
            <script type="text/javascript">
                $(function () {
                    $('input[name=match\\[date\\]]').datetimepicker({
                        format: 'YYYY-MM-DDTHH:mmZ'
                    });
                });
            </script>
        </div>
        <div class="form-group" style="vertical-align:top">
            <?php $element = $fieldset->get('location'); ?>
            <?php $element->setLabelAttributes(array('class' => 'control-label input-sm', 'style' => 'display:inline !important')); ?>
            <?php $element->setAttribute('class', 'form-control input-sm'); ?>

            <?php echo $this->view->formLabel($element) ?><br />
            <?php echo $this->view->formElement($element) ?><br />

            <?php $element = $fieldset->get('locationDetails'); ?>
            <?php $element->setLabelAttributes(array('class' => 'control-label input-sm', 'style' => 'display:inline !important')); ?>
            <?php $element->setAttribute('class', 'form-control input-sm'); ?>
            <?php echo $this->view->formElement($element) ?>
            <span class="help-block error-message" style="display:none"></span>
        </div>
        <div class="form-group" style="vertical-align:top">
            <?php $element = $fieldset->get('teams')->get('H')->get('team'); ?>
            <?php $element->setLabel('Home Team'); ?>
            <?php $element->setLabelAttributes(array('class' => 'control-label input-sm', 'style' => 'display:inline !important')); ?>
            <?php $element->setAttribute('class', 'form-control input-sm'); ?>

            <?php echo $this->view->formLabel($element) ?><br />
            <?php echo $this->view->formElement($element) ?>
            <span class="help-block error-message" style="display:none"></span>
        </div>
        <div class="form-group" style="vertical-align:top">
            <?php $element = $fieldset->get('teams')->get('A')->get('team'); ?>
            <?php $element->setLabel('Away Team'); ?>
            <?php $element->setLabelAttributes(array('class' => 'control-label input-sm', 'style' => 'display:inline !important')); ?>
            <?php $element->setAttribute('class', 'form-control input-sm'); ?>

            <?php echo $this->view->formLabel($element) ?><br />
            <?php echo $this->view->formElement($element) ?>
            <span class="help-block error-message" style="display:none"></span>
        </div>
        <div class="form-group pull-right" style="margin-top: 21px">
            <button id="MatchQuickAdd" class="btn btn-primary">Add Match!</button>
        </div>
    </div>
</div>
<?php
        $this->view->placeholder('form')->captureEnd();

        $matchCreateUrl = $this->view->url('usarugbystats_competition-api_competition_match', ['cid' => $competition->getId()]);
        $matchRenderUrl = $this->view->url('usarugbystats_frontend_competition_match/render-match-row', ['cid' => $competition->getId(), 'mid' => 'XXXXXX']);
        $addMatchJavascript = <<<JSBLOCK
$('#MatchQuickAdd').click(function() {
    $('#AsyncMatchAddSpinner').show();
    $('#AsyncMatchAddForm').hide();

    var payload = {
        'match[date]': $('*[name=match\\\\[date\\\\]]').val(),
        'match[location]': $('*[name=match\\\\[location\\\\]]').val(),
        'match[locationDetails]': $('*[name=match\\\\[locationDetails\\\\]]').val(),
        'match[teams][H][team]': $('*[name=match\\\\[teams\\\\]\\\\[H\\\\]\\\\[team\\\\]]').val(),
        'match[teams][A][team]': $('*[name=match\\\\[teams\\\\]\\\\[A\\\\]\\\\[team\\\\]]').val(),
    };

    function addErrorMessage(field, messages) {
        if ( typeof messages == 'undefined' ) {
            return;
        }
        $(field).parent().addClass('has-error');
        var errContainer = $(field).parent().find('.error-message').text("");
        $.each(messages, function(k,v) {
            errContainer.append(v).show();
        });
    }

    $('#AsyncMatchAddForm .form-group').removeClass('has-error');
    $('#AsyncMatchAddForm .error-message').hide();

    $.ajax({
        type: "POST",
        url: "{$matchCreateUrl}",
        data: payload,
        dataType: "json"
    }).done(function(data) {
        $('*[name=match\\\\[date\\\\]]').val(null),
        $('*[name=match\\\\[location\\\\]]').val(null),
        $('*[name=match\\\\[locationDetails\\\\]]').val(null),
        $('*[name=match\\\\[teams\\\\]\\\\[H\\\\]\\\\[team\\\\]]').val(null),
        $('*[name=match\\\\[teams\\\\]\\\\[A\\\\]\\\\[team\\\\]]').val(null),

        $.ajax({
            type: "GET",
            url: "{$matchRenderUrl}".replace('XXXXXX', data.match.id),
            data: {relativeToCompetition: '{$competition->getId()}'},
            dataType: "html"
        }).done(function(data) {
            $('{$tableSelector}').append(data);

        }).always(function() {
            $('#AsyncMatchAddSpinner').hide();
            $('#AsyncMatchAddForm').show();
        });
    }).fail(function(xhr, status) {
        try { addErrorMessage('*[name=match\\\\[date\\\\]]', xhr.responseJSON.validation_messages.match.date); } catch (e) {};
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
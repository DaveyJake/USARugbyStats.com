<?php
namespace UsaRugbyStats\CompetitionApi\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use UsaRugbyStats\Competition\Traits\CompetitionServiceTrait;
use UsaRugbyStats\Competition\Traits\CompetitionMatchServiceTrait;
use UsaRugbyStats\Competition\Entity\Competition;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;
use UsaRugbyStats\Competition\Entity\Competition\Match;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\Common\Persistence\ObjectManager;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeam;
use UsaRugbyStats\Competition\Entity\Team;
use UsaRugbyStats\Application\Entity\AccountInterface;
use UsaRugbyStats\Competition\Form\Fieldset\Competition\Match\MatchTeamEventFieldset;
use UsaRugbyStats\Competition\InputFilter\Competition\Match\MatchTeamEventFilter;
use Zend\Validator\InArray;

class CompetitionMatchEventController extends AbstractRestfulController
{
    use CompetitionServiceTrait;
    use CompetitionMatchServiceTrait;

    protected $objectManager;

    /**
     * @var ObjectRepository
     */
    protected $matchTeamEventMapper;

    public function create($data)
    {
        unset($data['event']['id']);

        $match = $this->getCompetitionMatchEntityFromRoute();
        if ($match instanceof ApiProblem) {
            return new ApiProblemResponse($match);
        }

        $service = $this->getCompetitionMatchService();
        $session = $service->startSession();
        $session->form = $service->getCreateForm();
        $session->competition = $match->getCompetition();
        $session->entity = $match;
        $service->prepare();

        $flags = $session->form->getFeatureFlags();
        $side = $match->getHomeTeam()->getId() == @$data['event']['team'] ? 'H' : ($match->getAwayTeam()->getId() == @$data['event']['team'] ? 'A' : null);
        if ( empty($side) ) {
            return new ApiProblemResponse(
                new ApiProblem(422, 'Validation failed', null, null, [
                    'validation_messages' => ['team' => ['isEmpty' => 'You must select a side!']]
                ])
            );
        }

        if ( ! $flags->{"match.teams.{$side}.events"}->is_on() ) {
            return new ApiProblemResponse(new ApiProblem(403, 'Not authorized to add events!'));
        }

        $entity = $this->hydrate($session, $data);
        if ($entity instanceof ApiProblem) {
            return new ApiProblemResponse($entity);
        }

        $match->addEvent($entity);
        $this->getCompetitionMatchService()->save($match);

        return new JsonModel($this->extractEvent($entity));
    }

    public function delete($id)
    {
        $match = $this->getCompetitionMatchEntityFromRoute();
        $event = $this->getEventEntityFromRoute();
        if ($event instanceof ApiProblem) {
            return new ApiProblemResponse($event);
        }

        if ( ! $this->isGranted('competition.competition.match.team.events.change', $match->getTeam('H'))
          && ! $this->isGranted('competition.competition.match.team.events.change', $match->getTeam('A')) ) {
            return new ApiProblemResponse(new ApiProblem(403, 'Not authorized to remove events!'));
        }

        $match->removeEvent($event);
        $this->getCompetitionMatchService()->save($match);

        return $this->getResponse()->setStatusCode(204);
    }

    public function deleteList()
    {
        return new ApiProblemResponse(new ApiProblem(405, null));
    }

    public function get($id)
    {
        $event = $this->getEventEntityFromRoute();
        if ($event instanceof ApiProblem) {
            return new ApiProblemResponse($event);
        }

        return new JsonModel($this->extractEvent($event));
    }

    public function getList()
    {
        return new ApiProblemResponse(new ApiProblem(405, null));
    }

    public function head($id = null)
    {
        return new ApiProblemResponse(new ApiProblem(405, null));
    }

    public function options()
    {
        return new ApiProblemResponse(new ApiProblem(405, null));
    }

    public function patch($id, $data)
    {
        return new ApiProblemResponse(new ApiProblem(405, null));
    }

    public function patchList($data)
    {
        return new ApiProblemResponse(new ApiProblem(405, null));
    }

    public function replaceList($data)
    {
        return new ApiProblemResponse(new ApiProblem(405, null));
    }

    public function update($id, $data)
    {
        return new ApiProblemResponse(new ApiProblem(405, null));
    }

    protected function getCompetitionEntityFromRoute()
    {
        $id = $this->params()->fromRoute('cid');
        $comp = $this->getCompetitionService()->findByID($id);
        if (! $comp instanceof Competition) {
            return new ApiProblem(404, 'Competition not found!');
        }

        return $comp;
    }

    protected function getCompetitionMatchEntityFromRoute()
    {
        $comp = $this->getCompetitionEntityFromRoute();
        if ($comp instanceof ApiProblem) {
            return $comp;
        }

        $id = $this->params()->fromRoute('mid');
        $match = $this->getCompetitionMatchService()->findByID($id);
        if (! $match instanceof Match || $match->getCompetition()->getId() != $comp->getId()) {
            return new ApiProblem(404, 'Match not found!');
        }

        return $match;
    }

    protected function getEventEntityFromRoute()
    {
        $match = $this->getCompetitionMatchEntityFromRoute();
        if ($match instanceof ApiProblem) {
            return $match;
        }

        $id = $this->params()->fromRoute('id');
        $event = $this->getMatchTeamEventMapper()->find($id);
        if ( ! $event instanceof MatchTeamEvent || $event->getMatch()->getId() != $match->getId() ) {
            return new ApiProblem(404, 'Event not found!');
        }

        return $event;
    }

    protected function extractEvent(MatchTeamEvent $e)
    {
        $hydrator = new DoctrineObject($this->getObjectManager());
        $data['event'] = $hydrator->extract($e);
        $data['event']['event'] = $e->getDiscriminator();
        $data['event']['match'] = $e->getMatch()->getId();
        $data['event']['team'] = $e->getTeam() instanceof MatchTeam ? $e->getTeam()->getId() : null;

        if ( $e->getTeam() instanceof MatchTeam ) {
            $side = $e->getTeam();
            if ( $side->getTeam() instanceof Team ) {
                $data['_embedded']['team'] = [
                    'id' => $side->getTeam()->getId(),
                    'name' => $side->getTeam()->getName(),
                ];
            }
        }

        switch ($data['event']['event']) {
            case 'sub':

                $data['event']['playerOn'] = $e->getPlayerOn() instanceof MatchTeamPlayer ? $e->getPlayerOn()->getId() : null;
                if ( $e->getPlayerOn() instanceof MatchTeamPlayer ) {
                    $position = $e->getPlayerOn();
                    if ( $position->getPlayer() instanceof AccountInterface ) {
                        $data['_embedded']['playerOn'] = [
                            'id' => $position->getPlayer()->getId(),
                            'name' => $position->getPlayer()->getDisplayName(),
                        ];
                    }
                }

                $data['event']['playerOff'] = $e->getPlayerOff() instanceof MatchTeamPlayer ? $e->getPlayerOff()->getId() : null;
                if ( $e->getPlayerOff() instanceof MatchTeamPlayer ) {
                    $position = $e->getPlayerOff();
                    if ( $position->getPlayer() instanceof AccountInterface ) {
                        $data['_embedded']['playerOff'] = [
                            'id' => $position->getPlayer()->getId(),
                            'name' => $position->getPlayer()->getDisplayName(),
                        ];
                    }
                }

                break;
            default:
                $data['event']['player'] = $e->getPlayer() instanceof MatchTeamPlayer ? $e->getPlayer()->getId() : null;
                if ( $e->getPlayer() instanceof MatchTeamPlayer ) {
                    $position = $e->getPlayer();
                    if ( $position->getPlayer() instanceof AccountInterface ) {
                        $data['_embedded']['player'] = [
                            'id' => $position->getPlayer()->getId(),
                            'name' => $position->getPlayer()->getDisplayName(),
                        ];
                    }
                }
                break;
        }

        return $data;
    }

    protected function hydrate($session, $data)
    {
        $ifEventTypes = $session->form->getInputFilter()->get('match')->get('teams')->getInputFilter()->get('events')->getInputFilter();
        $ifEvent = @$ifEventTypes[$data['event']['event']];
        if (! $ifEvent instanceof MatchTeamEventFilter) {
            return new ApiProblem(400, 'Invalid event type!');
        }

        // @TODO These validator attachments shouldn't be necessary here....investigate

        // Ensure team is valid
        $ifEvent->get('team')->getValidatorChain()->attach(
            new InArray([
                'haystack' => [
                    $session->entity->getHomeTeam()->getId(),
                    $session->entity->getAwayTeam()->getId()
                ]
            ])
        );

        // Ensure player fields are valid
        $side = $session->entity->getHomeTeam()->getId() == $data['event']['team'] ? 'H' : ($session->entity->getAwayTeam()->getId() == $data['event']['team'] ? 'A' : null);
        if ( !empty($side) ) {
            $playerHaystack = array();
            foreach ( $session->entity->getTeam($side)->getPlayers() as $p ) {
                array_push($playerHaystack, $p->getId());
            }
            switch ($data['event']['event']) {
                case 'sub':
                    $ifEvent->get('playerOn')->getValidatorChain()->attach(
                        new InArray(['haystack' => $playerHaystack])
                    );
                    break;
                    $ifEvent->get('playerOff')->getValidatorChain()->attach(
                        new InArray(['haystack' => $playerHaystack])
                    );
                    break;
                case 'default':
                    $ifEvent->get('player')->getValidatorChain()->attach(
                        new InArray(['haystack' => $playerHaystack])
                    );
                    break;
            }
        }

        if ( ! $ifEvent->setData($data['event'])->isValid() ) {
            return new ApiProblem(422, 'Validation failed', null, null, [
                'validation_messages' => $ifEvent->getMessages()
            ]);
        }

        $values = $ifEvent->getValues();

        $fsEventTypes = $session->form->get('match')->get('teams')->get($side)->get('events')->getTargetElement();
        $fsEvent = @$fsEventTypes[$data['event']['event']];
        if (! $fsEvent instanceof MatchTeamEventFieldset) {
            return new ApiProblem(400, 'Invalid event type!');
        }

        $fsEvent->bindValues($values);

        $entity = $fsEvent->getObject();
        $entity->setMatch($session->entity);

        return $entity;
    }

    /**
     * @return ObjectRepository
     */
    public function getMatchTeamEventMapper()
    {
        if ( is_null($this->matchTeamEventMapper) ) {
            $this->matchTeamEventMapper = $this->getObjectManager()->getRepository(
                'UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent'
            );
        }

        return $this->matchTeamEventMapper;
    }

    /**
     * @param ObjectRepository $m
     */
    public function setMatchTeamEventMapper(ObjectRepository $m)
    {
        $this->matchTeamEventMapper = $m;

        return $this;
    }

    /**
     * @return ObjectManager
     */
    public function getObjectManager()
    {
        if ( is_null($this->objectManager) ) {
            $this->objectManager = $this->getServiceLocator()->get(
                'zfcuser_doctrine_em'
            );
        }

        return $this->objectManager;
    }

}

<?php
namespace UsaRugbyStats\Competition\Form\Fieldset\Competition\Match;

use Zend\Form\Fieldset;
use Doctrine\Common\Persistence\ObjectManager;
use Zend\Form\FieldsetInterface;
use Zend\Form\Element\Collection;
use Zend\Form\FormInterface;

class MatchTeamFieldset extends Fieldset
{

    public function __construct(ObjectManager $om, FieldsetInterface $fsMatchTeamPlayer, Collection $collEvents)
    {
        parent::__construct('match-team');

        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'id',
            'options' => array(
                'label' => 'Identifier',
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'type',
            'options' => array(
                'label' => 'Type',
                'value_options' => [
                    'H' => 'Home Team',
                    'A' => 'Away Team',
                ]
            ),
        ));

        $this->add(array(
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'name' => 'team',
            'options' => array(
                'label' => 'Team',
                'object_manager' => $om,
                'target_class'   => 'UsaRugbyStats\Competition\Entity\Team',
            ),
        ));

        $this->add(array(
            'type'    => 'Zend\Form\Element\Collection',
            'name'    => 'players',
            'options' => array(
                'target_element' => $fsMatchTeamPlayer,
                'should_create_template' => true,
                'template_placeholder' => '__playerindex__',
                'count' => 0,
            )
        ));

        $this->add($collEvents);

    }

    public function prepareElement(FormInterface $form)
    {
        $players = $this->get('players');

        // Pop off any existing entries and store them temporarily
        // (prevents manual change in number from ruining sort order on form render)
        $tempStorage = [];
        foreach ($players as $item) {
            $players->remove($item->getName());
            $item->setName($item->get('number')->getValue());
            $tempStorage[$item->get('number')->getValue()] = $item;
        }

        // Pre-fill the first 23 roster slots with appropriate number and position
        for ($key = 1; $key <= 23; $key++) {

            if ( isset($tempStorage[$key]) ) {
                $players->add($tempStorage[$key]);
                unset($tempStorage[$key]);
                continue;
            }

            $item = clone $players->getTargetElement();
            $item->setName($key);
            $players->add($item);

            $valueOptions = $item->get('position')->getValueOptions();
            $thisPosition = array_slice($valueOptions, $key-1, 1, true);
            if ( count($thisPosition) == 1 ) {
                $thisPositionKey = array_keys($thisPosition);
                $item->get('position')->setValue(array_pop($thisPositionKey));
                $item->get('number')->setValue($key);
            }
        }

        // Re-add any extra records (>23) to the bottom
        if (count($tempStorage)) {
            foreach ($tempStorage as $item) {
                $players->add($item);
            }
        }

        return parent::prepareElement($form);
    }

}

<?php
namespace UsaRugbyStats\Competition\InputFilter;

use Zend\InputFilter\InputFilter;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Zend\InputFilter\FileInput;
use UsaRugbyStats\Application\Common\USStates;

/**
 * Team Input Filter
 *
 * @author Adam Lundrigan <adam@lundrigan.ca>
 */
class TeamFilter extends InputFilter
{
    public function __construct(ObjectManager $objectManager, ObjectRepository $unionRepository)
    {

        $this->add(array(
            'name'       => 'remoteId',
            'required'   => false,
            'validators' => array(),
            'filters'   => array(
                array('name' => 'StringTrim'),
            ),
        ));

        $this->add(array(
            'name'       => 'name',
            'required'   => true,
            'validators' => array(),
            'filters'   => array(
                array('name' => 'StringTrim'),
            ),
        ));

        $this->add(array(
            'name'       => 'union',
            'required'   => true,
            'validators' => array(
                array(
                    'name' => 'DoctrineModule\Validator\ObjectExists',
                    'options' => array(
                        'object_repository' => $unionRepository,
                        'fields' => 'id',
                    )
                ),
            ),
            'filters'   => array(
                array('name' => 'Int'),
            ),
        ));

        $file = new FileInput();
        $file->setRequired(false);
        $file->getValidatorChain()->attachByName('fileisimage');
        $file->getFilterChain()->attachByName('filerenameupload', [
            'target' => 'data/uploads/teamlogos',
            'randomize' => true,
            'use_upload_extension' => true,
        ]);
        $file->getFilterChain()->attachByName(
            'UsaRugbyStats\Application\Common\Filter\FileConvertToPng',
            []
        );
        $file->getFilterChain()->attachByName(
            'UsaRugbyStats\Application\Common\Filter\FileScale',
            []
        );
        $this->add($file, 'new_logo');

        $fileCover = new FileInput();
        $fileCover->setRequired(false);
        $fileCover->getValidatorChain()->attachByName('fileisimage');
        $fileCover->getFilterChain()->attachByName('filerenameupload', [
            'target' => 'data/uploads/teamcoverimages',
            'randomize' => true,
            'use_upload_extension' => true,
            ]);
        $fileCover->getFilterChain()->attachByName(
            'UsaRugbyStats\Application\Common\Filter\FileConvertToPng',
            []
        );
        $this->add($fileCover, 'new_cover_image');

        $this->add(array(
            'name'       => 'email',
            'required'   => false,
            'validators' => array(
                array( 'name' => 'EmailAddress' ),
            ),
            'filters'   => array(
                array('name' => 'StringTrim'),
            ),
        ));

        $this->add(array(
            'name'       => 'website',
            'required'   => false,
            'validators' => array(
                array(
                    'name' => 'Uri',
                    'options' => array(
                        'allowRelative' => false
                    ),
                ),
            ),
            'filters'   => array(
                array('name' => 'StringTrim'),
            ),
        ));

        $this->add(array(
            'name'       => 'facebookHandle',
            'required'   => false,
            'validators' => array(),
            'filters'   => array(
                array('name' => 'StringTrim'),
            ),
        ));

        $this->add(array(
            'name'       => 'twitterHandle',
            'required'   => false,
            'validators' => array(),
            'filters'   => array(
                array('name' => 'StringTrim'),
            ),
        ));

        $this->add(array(
            'name'       => 'city',
            'required'   => false,
            'validators' => array(),
            'filters'   => array(
                array('name' => 'StringTrim'),
            ),
        ));

        $this->add(array(
            'name'       => 'state',
            'required'   => false,
            'validators' => array(
                array('name' => 'InArray', 'options' => ['haystack' => [null] + array_keys(USStates::$states)])
            ),
            'filters'   => array(
                array('name' => 'StringTrim'),
            ),
        ));
    }

}

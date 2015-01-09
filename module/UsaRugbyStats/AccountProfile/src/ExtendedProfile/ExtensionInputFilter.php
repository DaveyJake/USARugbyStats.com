<?php
namespace UsaRugbyStats\AccountProfile\ExtendedProfile;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\FileInput;

class ExtensionInputFilter extends InputFilter
{
    public function __construct()
    {
        $this->add(array(
            'name'       => 'firstName',
            'required'   => true,
            'filters'    => array(array('name' => 'StringTrim')),
            'validators' => array(),
        ));

        $this->add(array(
            'name'       => 'lastName',
            'required'   => true,
            'filters'    => array(array('name' => 'StringTrim')),
            'validators' => array(),
        ));

        $this->add(array(
            'name'       => 'telephoneNumber',
            'required'   => false,
            'filters'    => array(array('name' => 'StringTrim')),
            'validators' => array(),
        ));

        $this->add(array(
            'name'       => 'photoSource',
            'required'   => false,
            'filters'    => array(array('name' => 'StringTrim')),
            'validators' => array(array(
                'name' => 'InArray',
                'options' => array(
                    'haystack' => ['G','C'],
                ),
            )),
        ));

        $file = new FileInput('custom_photo');
        $file->setRequired(false);
        $file->getValidatorChain()->attachByName('fileisimage');
        $file->getFilterChain()->attachByName('filerenameupload', [
            'target' => 'data/uploads/playeravatars',
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
        $this->add($file);

        $this->add(array(
            'name'       => 'citizenship',
            'required'   => false,
            'filters'    => array(array('name' => 'StringTrim')),
            'validators' => array(array(
                'name' => 'InArray',
                'options' => array(
                    'haystack' => array_keys(ExtensionEntity::$citizenshipMap),
                ),
            )),
        ));
    }
}

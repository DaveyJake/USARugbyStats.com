<?php
namespace UsaRugbyStats\AccountProfile\ExtendedProfile\Listener;

use Zend\EventManager\EventInterface;
use Zend\Form\FormInterface;
use Zend\EventManager\SharedListenerAggregateInterface;
use Zend\EventManager\SharedEventManagerInterface;

/**
 * Update player avatar with name of newly-uploaded file
 */
class UpdatePlayerPhotoWithNewlyUploadedFile implements SharedListenerAggregateInterface
{
    const TARGET_CLASS = 'LdcUserProfile\Service\ProfileService';

    /**
     * @var \Zend\Stdlib\CallbackHandler[]
     */
    protected $listeners = array();

    /**
     * @var string
     */
    protected $uploadedImage = null;

    public function attachShared(SharedEventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(
            static::TARGET_CLASS,
            static::TARGET_CLASS . '::validate.post',
            array($this, 'stashFileName')
        );
        $this->listeners[] = $events->attach(
            static::TARGET_CLASS,
            static::TARGET_CLASS . '::save.post',
            array($this, 'moveUploadedFile')
        );
    }

    public function stashFileName(EventInterface $e)
    {
        $formData = $e->getParams()['form']->getData(FormInterface::VALUES_AS_ARRAY);
        if ( !isset($formData['extprofile']['custom_photo']['tmp_name']) ) {
            return;
        }
        $this->uploadedImage = $formData['extprofile']['custom_photo']['tmp_name'];
    }

    public function moveUploadedFile(EventInterface $e)
    {
        if ( empty($this->uploadedImage) ) {
            return;
        }
        if ( ! is_readable($this->uploadedImage) ) {
            return;
        }
        $newFileName = sprintf('public/assets/img/playeravatars/%d.png', $e->getParams()['entity']->zfcuser->getId());
        copy($this->uploadedImage, $newFileName);
        $this->uploadedImage = null;
    }

    public function detachShared(SharedEventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $callback) {
            if ($events->detach(static::TARGET_CLASS, $callback)) {
                unset($this->listeners[$index]);
            }
        }
    }
}

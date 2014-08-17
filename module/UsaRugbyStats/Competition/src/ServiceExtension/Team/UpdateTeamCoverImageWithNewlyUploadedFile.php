<?php
namespace UsaRugbyStats\Competition\ServiceExtension\Team;

use UsaRugbyStats\Application\Service\ServiceExtensionInterface;
use Zend\EventManager\EventInterface;
use Zend\Form\FormInterface;

/**
 * Update team's cover image with name of newly-uploaded file
 */
class UpdateTeamCoverImageWithNewlyUploadedFile implements ServiceExtensionInterface
{
    protected $uploadedImage = null;

    public function checkPrecondition(EventInterface $e)
    {
        return true;
    }

    public function execute(EventInterface $e)
    {
        switch ( $e->getName() ) {
            case 'form.validate.post':
            {
                $formData = $e->getParams()->form->getData(FormInterface::VALUES_AS_ARRAY);
                if ( !isset($formData['team']['new_cover_image']['tmp_name']) ) {
                    return;
                }
                $this->uploadedImage = $formData['team']['new_cover_image']['tmp_name'];
                break;
            }
            case 'save.post':
            {
                if ( empty($this->uploadedImage) ) {
                    return;
                }
                if ( ! is_readable($this->uploadedImage) ) {
                    return;
                }
                $newFileName = sprintf('public/assets/img/teamcoverimages/%d.png', $e->getParams()->entity->getId());
                copy($this->uploadedImage, $newFileName);
                $this->uploadedImage = null;
            }
        }
    }
}

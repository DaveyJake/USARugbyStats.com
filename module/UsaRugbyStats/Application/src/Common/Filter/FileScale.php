<?php
namespace UsaRugbyStats\Application\Common\Filter;

use Zend\Filter\AbstractFilter;

/**
 * Filter to scale an uploaded image
 */
class FileScale extends AbstractFilter
{
    /**
     * @var array
     */
    protected $options = array(
        'target'    => null,
        'overwrite' => true,
        'max_dimension' => 300,
    );

    public function __construct($options = array())
    {
        $this->setOptions($options);
    }

    public function filter($value)
    {
        if (!is_scalar($value) && !is_array($value)) {
            return $value;
        }

        // An uploaded file? Retrieve the 'tmp_name'
        $isFileUpload = false;
        if (is_array($value)) {
            if (!isset($value['tmp_name'])) {
                return $value;
            }

            $isFileUpload = true;
            $uploadData = $value;
            $sourceFile = $value['tmp_name'];
        } else {
            $uploadData = array(
                'tmp_name' => $value,
                'name'     => $value,
            );
            $sourceFile = $value;
        }

        if (!file_exists($sourceFile)) {
            return $value;
        }
        $targetFile = $sourceFile;

        $this->checkFileExists($targetFile);

        $return = $targetFile;
        if ($isFileUpload) {
            $return = $uploadData;
            $return['tmp_name'] = $targetFile;
        }

        try {
            $imagine = new \Imagine\Gd\Imagine();
            $image = $imagine->open($sourceFile);

            if ( $image->getSize()->getWidth() > $this->options['max_dimension']
              || $image->getSize()->getHeight() > $this->options['max_dimension']
            ) {
                $image->resize(
                    ( $image->getSize()->getWidth() >= $image->getSize()->getHeight() )
                        ? $image->getSize()->widen($this->options['max_dimension'])
                        : $image->getSize()->heighten($this->options['max_dimension'])
                );
           }
            $image->save($targetFile);
        } catch ( \Exception $e ) {}

        return $return;
    }

    /**
     * @param  string                             $targetFile Target file path
     * @throws Exception\InvalidArgumentException
     */
    protected function checkFileExists($targetFile)
    {
        if (file_exists($targetFile) && ! $this->options['overwrite'] === true) {
            throw new \Zend\Filter\Exception\InvalidArgumentException(
                sprintf("File '%s' already exists and could not be overwritten.", $targetFile)
            );
        }
    }
}

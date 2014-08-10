<?php
namespace UsaRugbyStats\Application\Common\Doctrine;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;

class DateTimeTzType extends Type
{
    protected $format = DATE_ISO8601;

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return Type::DATETIMETZ;
    }

    /**
     * {@inheritdoc}
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return 'VARCHAR(25)';
    }

    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return ($value !== null) ? $value->format($this->format) : null;
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null || $value instanceof \DateTime) {
            return $value;
        }

        $val = \DateTime::createFromFormat($this->format, $value);
        if (! $val) {
            throw ConversionException::conversionFailedFormat($value, $this->getName(), $this->format);
        }

        return $val;
    }
}

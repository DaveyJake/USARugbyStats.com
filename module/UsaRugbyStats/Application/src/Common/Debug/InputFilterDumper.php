<?php
namespace UsaRugbyStats\Application\Common\Debug;

class InputFilterDumper
{
    public static function extractToArray($if)
    {
        $contents = array();
        foreach ( $if->getInputs() as $key => $child ) {
            if ($child instanceof \Zend\InputFilter\BaseInputFilter) {
                $contents[$key] = self::extractToArray($child);
                continue;
            }
            if ($child instanceof \Zend\InputFilter\InputInterface) {
                $contents[$key] = [
                    'name' => $child->getName(),
                    'required' => $child->isRequired(),
                    'allow_empty' => $child->allowEmpty(),
                ];

                foreach ( $child->getFilterChain()->getFilters() as $fk => $filter ) {
                    if (! $filter instanceof \Zend\Filter\AbstractFilter) {
                        continue;
                    }
                    $contents[$key]['filters'][$fk] = get_class($filter);
                }

                foreach ( $child->getValidatorChain()->getValidators() as $vk => $validator ) {
                    if (! $validator['instance'] instanceof \Zend\Validator\AbstractValidator) {
                        continue;
                    }
                    $contents[$key]['validators'][$vk] = get_class($validator['instance']);
                }
            }
        }

        return $contents;
    }

    public static function dump($if)
    {
        echo '<pre>';
        print_r(self::extractToArray($if));
        echo '</pre>';
    }
}

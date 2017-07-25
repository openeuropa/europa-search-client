<?php
/**
 * @file
 * Contains EC\EuropaSearch\Common\DocumentMetadata.
 */

namespace EC\EuropaSearch\Common;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class DocumentMetadata.
 *
 * It represents a metadata of a indexed document.
 *
 * @package EC\EuropaSearch\Common
 */
class DocumentMetadata
{

    /**
     * Metadata name.
     *
     * @var string
     */
    private $name;

    /**
     * Metadata value.
     *
     * @var
     */
    private $value;

    /**
     * Metadata type.
     *
     * @var
     */
    private $type;

    /**
     * Metadata boost level.
     *
     * @var
     */
    private $boost;

    /**
     * DocumentMetadata constructor.
     *
     * @param string $name
     *   The metadata name as know by the library consumer.
     * @param string $value
     *   The metadata value. If the metadata type is 'date', the value type
     *   must be a valid date.
     * @param string $type
     *   The metadata value type. the accepted type value are:
     *   - 'fulltext': for string that can be included in a full text search;
     *   - 'uri': for URL or URI;
     *   - 'string': for string that can be used to filter a search;
     *   - 'int' or 'integer': for integer that can be used to filter a search;
     *   - 'float': for float that can be used to filter a search;
     *   - 'boolean': for boolean that can be used to filter a search;
     *   - 'date': for date that can be used to filter a search;
     *   - 'not_indexed': for metadata that need to be send to Europa Search
     *      services but not indexed.
     * @param int    $boost
     *   The boost value for the metadata; I.E. the importance if the metadata
     *   The search query.
     */
    public function __construct($name, $value, $type, $boost = null)
    {
        $this->name = $name;
        $this->value = $value;
        $this->type = $type;
        $this->boost = $boost;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     *   The metadata value. If the metadata type is 'date', the value type
     *   must be a valid date.
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     *   - 'fulltext': for string that can be included in a full text search;
     *   - 'uri': for URL or URI;
     *   - 'string': for string that can be used to filter a search;
     *   - 'int' or 'integer': for integer that can be used to filter a search;
     *   - 'float': for float that can be used to filter a search;
     *   - 'boolean': for boolean that can be used to filter a search;
     *   - 'date': for date that can be used to filter a search;
     *   - 'not_indexed': for metadata that need to be send to Europa Search
     *      services but not indexed.
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return int
     */
    public function getBoost()
    {
        return $this->boost;
    }

    /**
     * @param int $boost
     */
    public function setBoost($boost)
    {
        $this->boost = $boost;
    }

    /**
     * Loads constraints declarations for the validator process.
     *
     * @param ClassMetadata $metadata
     */
    public static function getConstraints(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('name', new Assert\NotBlank());
        $metadata->addPropertyConstraints('type', [
            new Assert\NotBlank(),
            new Assert\Choice([
                'fulltext',
                'uri',
                'string',
                'int',
                'integer',
                'float',
                'boolean',
                'date',
                'not_indexed',
            ]),
        ]);
        $metadata->addPropertyConstraint('value', new Assert\NotNull());
        $metadata->addPropertyConstraint('boost', new Assert\Type('int'));

        $metadata->addConstraint(new Assert\Callback('validate'));
    }

    /**
     * Special validator callback for value.
     *
     * @param ExecutionContextInterface $context
     * @param mixed                     $payload
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if ($this->getType() == 'date' && empty($this->getValue())) {
            $context->buildViolation('The value should not be empty as the type is "date".')
                ->atPath('documentContent')
                ->addViolation();
        }

        if ($this->getType() == 'date') {
            $value = $this->getValue();
            $values = (is_array($value)) ? $value : array($value);

            $validator = new Assert\DateValidator();
            $validator->initialize($context);
            foreach ($values as $key => $value) {
                // use procedural because the DateTime instantiation is
                // incompatible with the constraints mechanism.
                $date = date_create($value);
                if (!$date) {
                    $context->buildViolation('The value is not a valid date as the type is "date".')
                        ->atPath('value')
                        ->addViolation();
                }
            }
        }
    }
}

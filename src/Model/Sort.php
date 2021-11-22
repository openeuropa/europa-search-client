<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Model;

use OpenEuropa\EuropaSearchClient\Exception\ParameterValueException;

class Sort implements \JsonSerializable
{
    /**
     * @var string|null
     */
    protected $field;

    /**
     * @var string|null
     */
    protected $order;

    /**
     * @param string|null $field
     * @param string|null $order
     */
    public function __construct(?string $field = null, ?string $order = null)
    {
        $this->setField($field);
        $this->setOrder($order);
    }

    /**
     * @param string|null $field
     *
     * @return $this
     */
    public function setField(?string $field): self
    {
        $this->field = $field;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getField(): ?string
    {
        return $this->field;
    }

    /**
     * @param string|null $order
     *
     * @return $this
     */
    public function setOrder(?string $order): self
    {
        if ($order !== null) {
            $order = strtoupper($order);
            if (!in_array($order, ['ASC', 'DESC'], true)) {
                throw new ParameterValueException(
                    "::setOrder() received an invalid argument '{$order}', must be one of 'ASC' and 'DESC'."
                );
            }
        }
        $this->order = $order;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getOrder(): ?string
    {
        return $this->order;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): \stdClass
    {
        return (object) [
            'field' => $this->getField(),
            'order' => $this->getOrder(),
        ];
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->field) || empty($this->order);
    }
}

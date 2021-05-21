<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Traits;

use OpenEuropa\EuropaSearchClient\Contract\LanguagesAwareInterface;

trait LanguagesAwareTrait
{
    /**
     * @var string[]
     */
    protected $languages;

    /**
     * @inheritDoc
     */
    public function setLanguages(?array $languages): LanguagesAwareInterface
    {
        // @todo Validate passed language codes against ISO-639-1 in OEL-154.
        $this->languages = $languages;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getLanguages(): ?array
    {
        return $this->languages;
    }
}

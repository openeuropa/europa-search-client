<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Model;

class Info
{
    /**
     * @var string
     */
    protected $groupId;

    /**
     * @var string
     */
    protected $comment;

    /**
     * @var string
     */
    protected $artifactId;

    /**
     * @param string $groupId
     *
     * @return $this
     */
    public function setGroupId(string $groupId): self
    {
        $this->groupId = $groupId;
        return $this;
    }

    /**
     * @return string
     */
    public function getGroupId(): string
    {
        return $this->groupId;
    }

    /**
     * @param string $comment
     *
     * @return $this
     */
    public function setComment(string $comment): self
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * @return string
     */
    public function getComment(): string
    {
        return $this->comment;
    }

    /**
     * @param string $artifactId
     *
     * @return $this
     */
    public function setArtifactId(string $artifactId): self
    {
        $this->artifactId = $artifactId;
        return $this;
    }

    /**
     * @return string
     */
    public function getArtifactId(): string
    {
        return $this->artifactId;
    }
}

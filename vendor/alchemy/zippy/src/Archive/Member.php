<?php

/*
 * This file is part of Zippy.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Zippy\Archive;

use Alchemy\Zippy\Adapter\AdapterInterface;
use Alchemy\Zippy\Adapter\Resource\ResourceInterface;

/**
 * Represents a member of an archive.
 */
class Member implements MemberInterface
{
    /**
     * The location of the file
     *
     * @var string
     */
    private $location;

    /**
     * Tells whether the archive member is a directory or not
     *
     * @var bool
     */
    private $isDir;

    /**
     * The uncompressed size of the file
     *
     * @var int
     */
    private $size;

    /**
     * The last modified date of the file
     *
     * @var \DateTime
     */
    private $lastModifiedDate;

    /**
     * The resource to the actual archive
     *
     * @var string
     */
    private $resource;

    /**
     * An adapter
     *
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * Constructor
     *
     * @param ResourceInterface $resource The path of the archive which contain the member
     * @param AdapterInterface $adapter The archive adapter interface
     * @param string $location The path of the archive member
     * @param int $fileSize The uncompressed file size
     * @param \DateTime $lastModifiedDate The last modified date of the member
     * @param bool $isDir Tells whether the member is a directory or not
     */
    public function __construct(
        ResourceInterface $resource,
        AdapterInterface  $adapter,
                          $location,
                          $fileSize,
        \DateTime         $lastModifiedDate,
                          $isDir
    )
    {
        $this->resource = $resource;
        $this->adapter = $adapter;
        $this->location = $location;
        $this->isDir = $isDir;
        $this->size = $fileSize;
        $this->lastModifiedDate = $lastModifiedDate;
    }

    /**
     * {@inheritdoc}
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * {@inheritdoc}
     */
    public function isDir()
    {
        return $this->isDir;
    }

    /**
     * {@inheritdoc}
     */
    public function getLastModifiedDate()
    {
        return $this->lastModifiedDate;
    }

    /**
     * {@inheritdoc}
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->location;
    }

    /**
     * {@inheritdoc}
     */
    public function extract($to = null, $overwrite = false)
    {
        $this->adapter->extractMembers($this->resource, $this->location, $to, (bool)$overwrite);

        return new \SplFileInfo(sprintf('%s%s', rtrim(null === $to ? getcwd() : $to, '/'), $this->location));
    }

    /**
     * @inheritdoc
     * */
    public function getResource()
    {
        return $this->resource;
    }
}

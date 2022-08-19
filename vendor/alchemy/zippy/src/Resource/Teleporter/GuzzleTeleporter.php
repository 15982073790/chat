<?php

/*
 * This file is part of Zippy.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Zippy\Resource\Teleporter;

use Alchemy\Zippy\Resource\Reader\Guzzle\LegacyGuzzleReaderFactory;
use Alchemy\Zippy\Resource\ResourceLocator;
use Alchemy\Zippy\Resource\ResourceReaderFactory;
use Alchemy\Zippy\Resource\Writer\FilesystemWriter;
use GuzzleHttp\Client;

/**
 * Guzzle Teleporter implementation for HTTP resources
 */
class GuzzleTeleporter extends GenericTeleporter
{
    /**
     * @param Client $client
     * @param ResourceReaderFactory $readerFactory
     * @param ResourceLocator $resourceLocator
     */
    public function __construct(
        Client                $client = null,
        ResourceReaderFactory $readerFactory = null,
        ResourceLocator       $resourceLocator = null
    )
    {
        parent::__construct($readerFactory ?: new LegacyGuzzleReaderFactory($client), new FilesystemWriter(),
            $resourceLocator);
    }

    /**
     * Creates the GuzzleTeleporter
     *
     * @return GuzzleTeleporter
     * @deprecated
     */
    public static function create()
    {
        return new static();
    }
}

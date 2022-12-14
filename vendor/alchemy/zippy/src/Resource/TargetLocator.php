<?php

/*
 * This file is part of Zippy.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Zippy\Resource;

use Alchemy\Zippy\Exception\TargetLocatorException;

class TargetLocator
{
    /**
     * Locates the target for a resource in a context
     *
     * For example, adding /path/to/file where the context (current working
     * directory) is /path/to will return `file` as target
     *
     * @param string $context
     * @param string|resource $resource
     *
     * @return string
     *
     * @throws TargetLocatorException when the resource is invalid
     */
    public function locate($context, $resource)
    {
        switch (true) {
            case is_resource($resource):
                return $this->locateResource($resource);
            case is_string($resource):
                return $this->locateString($context, $resource);
            case $resource instanceof \SplFileInfo:
                return $this->locateString($context, $resource->getRealPath());
            default:
                throw new TargetLocatorException($resource, 'Unknown resource format');
        }
    }

    /**
     * Locate the target for a resource.
     *
     * @param resource $resource
     *
     * @return string
     *
     * @throws TargetLocatorException
     */
    private function locateResource($resource)
    {
        $meta = stream_get_meta_data($resource);
        $data = parse_url($meta['uri']);

        if (!isset($data['path'])) {
            throw new TargetLocatorException($resource, 'Unable to retrieve path from resource');
        }

        return PathUtil::basename($data['path']);
    }

    /**
     * Locate the target for a string.
     *
     * @param        $context
     * @param string $resource
     *
     * @return string
     *
     * @throws TargetLocatorException
     */
    private function locateString($context, $resource)
    {
        $url = parse_url($resource);

        if (isset($url['scheme']) && $this->isLocalFilesystem($url['scheme'])) {
            $resource = $url['path'] = $this->cleanupPath($url['path']);
        }

        // resource is a URI
        if (isset($url['scheme'])) {
            if ($this->isLocalFilesystem($url['scheme']) && $this->isFileInContext($url['path'], $context)) {
                return $this->getRelativePathFromContext($url['path'], $context);
            }

            return PathUtil::basename($resource);
        }

        // resource is a local path
        if ($this->isFileInContext($resource, $context)) {
            $resource = $this->cleanupPath($resource);

            return $this->getRelativePathFromContext($resource, $context);
        } else {
            return PathUtil::basename($resource);
        }
    }

    /**
     * Removes backward path sequences (..)
     *
     * @param string $path
     *
     * @return string
     *
     * @throws TargetLocatorException In case the path is invalid
     */
    private function cleanupPath($path)
    {
        if (false === $cleanPath = realpath($path)) {
            throw new TargetLocatorException($path, sprintf('%s is an invalid location', $path));
        }

        return $cleanPath;
    }

    /**
     * Checks whether the path belong to the context
     *
     * @param string $path A resource path
     * @param string $context
     *
     * @return bool
     */
    private function isFileInContext($path, $context)
    {
        return 0 === strpos($path, $context);
    }

    /**
     * Gets the relative path from the context for the given path
     *
     * @param string $path A resource path
     * @param string $context
     *
     * @return string
     */
    private function getRelativePathFromContext($path, $context)
    {
        return ltrim(str_replace($context, '', $path), '/\\');
    }

    /**
     * Checks if a scheme refers to a local filesystem
     *
     * @param string $scheme
     *
     * @return bool
     */
    private function isLocalFilesystem($scheme)
    {
        return 'plainfile' === $scheme || 'file' === $scheme;
    }
}

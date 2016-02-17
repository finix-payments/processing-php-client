<?php

namespace Finix\Hal;

use Finix\Hal\Exception\RelNotFoundException;
use Finix\Hal\Exception\LinkUniqueException;
use Finix\Hal\Exception\LinkNotUniqueException;
use Finix\Hal\Exception\EmbeddedResourceUniqueException;
use Finix\Hal\Exception\EmbeddedResourceNotUniqueException;

/**
 * The Resource Object described in the
 * JSON Hypertext Application Language (draft-kelly-json-hal-07)
 * @see https://tools.ietf.org/html/draft-kelly-json-hal-07#section-4
 *
 * Note:    When trying to find a Link or an embedded Resource
 *            by their relation type (Rel), the search is done by
 *            comparing the lower-case relation name.
 *
 * "When extension relation types are compared, they MUST be compared as
 * strings [...] in a case-insensitive fashion."
 * @see https://tools.ietf.org/html/rfc5988#section-4.2
 */
class Resource
{
    private $state;
    private $links;
    private $embeddedResources;

    public function __construct(array $state = null, array $links = null, array $embeddedResources = null)
    {
        $this->state = (array) $state;
        $this->links = (array) $links;
        $this->embeddedResources = (array) $embeddedResources;
    }

    /**
     * All the properties of the resource
     * ("_links" and "_embedded" not included).
     * @return    array
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * All the links directly available in the resource.
     * The key is the relation type (Rel) and the value
     * can be either a Link or a numeric array of Links.
     *
     * Note that there is no guarantees as to the order of the links.
     * @return    array
     */
    public function getAllLinks()
    {
        return $this->links;
    }

    /**
     * All the embedded resources directly available in the resource.
     * The key is the relation type (Rel) and the value
     * can be either a Resource or a numeric array of Resources.
     *
     * Note that there is no guarantees as to the order of the embedded resources.
     * @return  array
     */
    public function getAllEmbeddedResources()
    {
        return $this->embeddedResources;
    }

    /**
     * Finds a unique link by its relation type.
     * @param $rel    string|RegisteredRel|CustomRel        The relation type.
     * @return    Link    The Link referenced by the given rel.
     * @throws LinkNotUniqueException
     * @throws RelNotFoundException
     */
    public function getLink($rel)
    {
        $link = self::findByRel($this->links, $rel);

        if (!$link) {
            throw new RelNotFoundException($rel, array_keys($this->links));
        }

        if (is_array($link)) {
            throw new LinkNotUniqueException();
        }

        return $link;
    }

    /**
     * Finds an array of links by their relation type.
     * Note that there is no guarantees as to the order of the links.
     * @param $rel    string|RegisteredRel|CustomRel        The relation type.
     * @return    array of links referenced by the given rel
     * @throws LinkUniqueException
     * @throws RelNotFoundException
     */
    public function getLinks($rel)
    {
        $links = self::findByRel($this->links, $rel);

        if (!$links) {
            throw new RelNotFoundException($rel, array_keys($this->links));
        }

        if (!is_array($links)) {
            throw new LinkUniqueException();
        }

        return $links;
    }

    /**
     * Finds a unique embedded resource by its relation type.
     * @param $rel    RegisteredRel|CustomRel        The relation type.
     * @return    Resource    The Resource referenced by the given rel.
     * @throws EmbeddedResourceNotUniqueException
     * @throws RelNotFoundException
     */
    public function getEmbeddedResource($rel)
    {
        $resource = self::findByRel($this->embeddedResources, $rel);

        if (!$resource) {
            throw new RelNotFoundException($rel, array_keys($this->embeddedResources));
        }

        if (is_array($resource)) {
            throw new EmbeddedResourceNotUniqueException();
        }

        return $resource;
    }

    /**
     * Finds an array of embedded resources by their relation type.
     * Note that there is no guarantees as to the order of the resources.
     * @param $rel    RegisteredRel|CustomRel        The relation type.
     * @return    array of embedded resources referenced by the given rel.
     * @throws EmbeddedResourceUniqueException
     * @throws RelNotFoundException
     */
    public function getEmbeddedResources($rel)
    {
        $resources = self::findByRel($this->embeddedResources, $rel);

        if (!$resources) {
            throw new RelNotFoundException($rel, array_keys($this->embeddedResources));
        }

        if (!is_array($resources)) {
            throw new EmbeddedResourceUniqueException();
        }

        return $resources;
    }

    /**
     * Looks for the given relation name in a case-insensitive
     * fashion and returns the corresponding value.
     * @param array $a
     * @param string $rel
     * @return mixed The value in $a matching the relation name
     *                    or null if not found.
     */
    private static function findByRel(array $a, $rel)
    {
        $relName = mb_strtolower($rel, 'UTF-8');
        foreach ($a as $name => $value) {
            if (mb_strtolower($name, 'UTF-8') === $relName) {
                return $value;
            }
        }

        return null;
    }

    /**
     * Builds a Resource from its JSON representation.
     * @param $json        string|array|object        A JSON representing the resource.
     * @return  \Finix\Hal\Resource
     * @throws \Exception
     */
    public static function fromJson($json)
    {
        if (!$json) {
            $json = array();
        }

        if (!is_array($json)) {
            if (is_object($json)) {
                $json = (array) $json;
            } elseif (is_string($json)) {
                $json = json_decode(trim($json) ? $json : '{}', true);
            } else {
                throw new \Exception("JSON must be a string, an array or an object ('" . gettype($json) . "' provided).");
            }
        }

        return new Resource(
            self::extractState($json),
            self::extractByRel($json, '_links'),
            self::extractByRel($json, '_embedded')
        );
    }

    private static function extractState(array $json)
    {
        unset($json['_links']);
        unset($json['_embedded']);

        return $json;
    }

    private static function extractByRel(array $json, $rel)
    {
        $out = [];

        if (!isset($json[$rel])) {
            return $out;
        }

        foreach ($json[$rel] as $name => $uniqueOrArray) {
            if (isset($uniqueOrArray[0])) { // Array
                $aArrayOfLinks = [];
                foreach ($uniqueOrArray as $unique) {
                    if ($rel == '_links') {
                        $aArrayOfLinks[] = Link::fromJson($unique);
                    } else {
                        $aArrayOfLinks[] = Resource::fromJson($unique);
                    }
                }

                $out[$name] = $aArrayOfLinks;
            } else { // Unique
                if ($rel == '_links') {
                    $out[$name] = Link::fromJson($uniqueOrArray);
                } else {
                    $out[$name] = Resource::fromJson($uniqueOrArray);
                }
            }
        }

        return $out;
    }
}

<?php

namespace Finix\Hal;

class Link
{
    private $href;
    private $templated;
    private $type;
    private $deprecation;
    private $name;
    private $profile;
    private $title;
    private $hreflang;

    /**
     * See getters for details about each param.
     * @param $href            string
     * @param $templated    boolean|null
     * @param $type            string|null
     * @param $deprecation    string|null
     * @param $name            string|null
     * @param $profile        string|null
     * @param $title        string|null
     * @param $hreflang        string|null
     */
    public function __construct($href, $templated = null, $type = null, $deprecation = null,
            $name = null, $profile = null, $title = null, $hreflang = null)
    {
        $href = trim($href);
        if (!$href) {
            throw new \InvalidArgumentException('The href property is mandatory.');
        }

        $this->href        = $href;
        $this->templated   = $templated;
        $this->type        = $type;
        $this->deprecation = $deprecation;
        $this->name        = $name;
        $this->profile     = $profile;
        $this->title       = $title;
        $this->hreflang    = $hreflang;
    }

    /**
     * REQUIRED
     * Its value is either a URI [RFC3986] or a URI Template [RFC6570].<br>
     * If the value is a URI Template then the Link Object SHOULD have a
     * "templated" attribute whose value is true.
     */
    public function getHref()
    {
        return $this->href;
    }

    /**
     * OPTIONAL
     * Its value is boolean and SHOULD be true when the Link Object's "href"
     * property is a URI Template.<br>
     * Its value SHOULD be considered false if it is undefined or any other
     * value than true.
     */
    public function isTemplated()
    {
        return (bool) $this->templated;
    }

    /**
     * OPTIONAL
     * Its value is a string used as a hint to indicate the media type
     * expected when dereferencing the target resource.
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * OPTIONAL
     * Its presence indicates that the link is to be deprecated (i.e.
     * removed) at a future date.  Its value is a URL that SHOULD provide
     * further information about the deprecation.
     * A client SHOULD provide some notification (for example, by logging a
     * warning message) whenever it traverses over a link that has this
     * property.  The notification SHOULD include the deprecation property's
     * value so that a client maintainer can easily find information about
     * the deprecation.
     */
    public function getDeprecation()
    {
        return $this->deprecation;
    }

    /**
     * OPTIONAL
     * Its value MAY be used as a secondary key for selecting Link Objects
     * which share the same relation type.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * OPTIONAL
     * Its value is a string which is a URI that hints about the profile (as
     * defined by [I-D.wilde-profile-link]) of the target resource.
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * OPTIONAL
     * Its value is a string and is intended for labelling the link with a
     * human-readable identifier (as defined by [RFC5988]).
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * OPTIONAL
     * Its value is a string and is intended for indicating the language of
     * the target resource (as defined by [RFC5988]).
     */
    public function getHreflang()
    {
        return $this->hreflang;
    }

    /**
     * Constructor from a map of properties (json).
     * Keys that are not a valid property are ignored.
     * @param array|object|string $json
     * @return Link
     */
    public static function fromJson($json) {
        if (!$json) {
            $json = array();
        }

        if (!is_array($json)) {
            if (is_object($json)) {
                $json = (array) $json;
            } elseif (is_string($json)) {
                $json = json_decode(trim($json) ? $json : '{}', true);
            } else {
                throw new \InvalidArgumentException("JSON must be a string, an array or an object ('" . gettype($json) . "' provided).");
            }
        }

        return new Link(
            isset($json['href'])        ? $json['href']            : null,
            isset($json['templated'])    ? $json['templated']    : null,
            isset($json['type'])        ? $json['type']            : null,
            isset($json['deprecation'])    ? $json['deprecation']    : null,
            isset($json['name'])        ? $json['name']            : null,
            isset($json['profile'])        ? $json['profile']        : null,
            isset($json['title'])        ? $json['title']        : null,
            isset($json['hreflang'])    ? $json['hreflang']        : null
        );
    }

    public function __toString() {
        $s = 'Link (href=' . $this->href;

        if ($this->templated)    $s .= ', templated=' . ($this->templated ? 'true' : 'false');
        if ($this->type)        $s .= ', type=' . $this->type;
        if ($this->deprecation)    $s .= ', deprecation=' . $this->deprecation;
        if ($this->name)        $s .= ', name=' . $this->name;
        if ($this->profile)        $s .= ', profile=' . $this->profile;
        if ($this->title)        $s .= ', title=' . $this->title;
        if ($this->hreflang)    $s .= ', hreflang=' . $this->hreflang;

        return $s . ')';
    }
}
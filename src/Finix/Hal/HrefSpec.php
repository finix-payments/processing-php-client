<?php

namespace Finix\Hal;

class HrefSpec
{
    public $collection_uri = null,
           $name,
           $idNames,
           $override;

    # $override supercedes $name when creating an object, only necessary when
    # name and class are not equal
    public function __construct($name, $idNames, $root = null, $override = null)
    {
        $this->name = $name;
        if ($override != null) {
            $this->override = $override;
        }

        if (!is_array($idNames)) {
            $idNames = array($idNames);
        }
        $this->idNames = $idNames;
        if ($root != null) {
            if ($root == '' || substr($root, -1) == '/') {
                $this->collection_uri = $root . $name;
            } else {
                $this->collection_uri = $root . '/' . $name;
            }
        }
    }
}

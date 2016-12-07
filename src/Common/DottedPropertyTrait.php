<?php

namespace Registry\Common;

trait DottedPropertyTrait
{
    /**
     * Convert an associative array to a flattened list key value pairs.
     *
     * The keys of the flattened array are dot separated paths of the original
     * keys; for example:-
     *
     *     [ foo => [bar => 'baz'] ]
     *
     * becomes:-
     *
     *     ['foo.bar' => 'baz' ]
     *
     * @param array $properties
     *
     * @return array
     */
    public function flatten($properties)
    {
        return $this->makeFlat($properties);
    }

    /**
     * Convert a flattened list of key value pairs to an associative array.
     *
     * The keys of the flattened array are converted back to a nested array; for
     * example:-
     *
     *     ['foo.bar' => 'baz' ]
     *
     * becomes:-
     *
     *     [ foo => [bar => 'baz'] ]
     *
     * @param array $properties
     *
     * @return array
     */
    public function unflatten($properties)
    {
        $result = array();

        foreach ($properties as $key => $value) {
            $ptr = &$result;
            $keys = explode('.', $key);
            while (sizeof($keys) > 1) {
                $k = array_shift($keys);
                if (! array_key_exists($k, $ptr)) {
                    $ptr[$k] = array();
                } elseif (! is_array($ptr[$k])) {
                    $ptr[$k] = array($ptr[$k]);
                }
                $ptr = &$ptr[$k];
            }
            $ptr[$keys[0]] = $value;
        }

        return $result;
    }

    private function makeFlat($properties, $result = array(), $prefix = null, $conflicts = array())
    {
        foreach ($properties as $key => $value) {
            if ($prefix) {
                $key = sprintf('%s.%s', $prefix, $key);
            }
            if (is_array($value)) {
                $result = $this->makeFlat($value, $result, $key, $conflicts);
                continue;
            }
            if (array_key_exists($key, $conflicts)) {
                $key = sprintf('%s.%s', $key, ++$conflicts[$key]);
            } elseif (array_key_exists($key, $result)) {
                $conflicts[$key] = 0;
                $result[sprintf('%s.%s', $key, '0')] = $result[$key];
                unset($result[$key]);
                $key = sprintf('%s.%s', $key, ++$conflicts[$key]);
            }
            $result[$key] = $value;
        }

        return $result;
    }
}

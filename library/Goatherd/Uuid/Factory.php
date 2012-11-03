<?php
/**
 * Uuid generator factory.
 *
 * PHP VERSION 5.
 *
 * @category Goaterd
 * @package  Goatherd\Uuid
 * @author   Maik Penz <maik@phpkuh.de>
 * @license  https://github.com/goatherd/uuid/blob/master/COPYING
 *           dual licensed as BSDL or Apache 2.0
 * @link     https://github.com/goatherd/uuid
 */

namespace Goatherd\Uuid;

/**
 * UUID (RFC4122) Generator (factory),
 * http://tools.ietf.org/html/rfc4122
 *
 * Implements version 1, 3, 4 and 5
 *
 * @todo a validator would be helpful.
 *
 * @category Goaterd
 * @package  Goatherd\Uuid
 * @author   Maik Penz <maik@phpkuh.de>
 * @license  https://github.com/goatherd/uuid/blob/master/COPYING
 *           dual licensed as BSDL or Apache 2.0
 * @link     https://github.com/goatherd/uuid
 */
class Uuid
{
    /**@#+
     * UUID version
     *
     * @var integer
     */
    const UUID_TIME = 1;       /* Time based UUID */
    const UUID_NAME_MD5 = 3;   /* Name based (MD5) UUID */
    const UUID_RANDOM = 4;     /* Random UUID */
    const UUID_NAME_SHA1 = 5;  /* Name based (SHA1) UUID */
    /**@#-*/

    /**
     * Public API, generate a UUID of 'type' in format 'fmt' for
     * the given namespace 'ns' and node 'node'
     *
     * @param integer $version uuid version
     * @param integer $fmt     format
     * @param string  $node    node
     * @param string  $ns      namespace
     *
     * @return string uuid or NULL on error
     */
    public static function generate(
        $version = self::UUID_NAME_SHA1, $fmt = UuidInterface::FMT_BYTE,
        $node = '', $ns = ''
    ) {
        $class = 'V' . (int) $version;

        return $class::generate($fmt, $node, $ns);
    }

    /**
     * Auto-detect UUID format.
     *
     * @param string $src test data
     *
     * @return integer
     */
    static public function detectFormat($src)
    {
        $format = self::FMT_BINARY;
        if (is_string($src)) {
            return self::FMT_STRING;
        } elseif (is_array($src)) {
            $len = count($src);
            $format = $len == 2 || ($len % 2) == 0?$len:-1;
        }

        return $format;
    }

    /**
     * Public API, convert a UUID from one format to another
     *
     * @param string  $uuid uuid
     * @param integer $from from version number
     * @param integer $to   to version number
     *
     * @return string
     */
    static public function convert(
        $uuid, $from, $to = self::UUID_NAME_SHA1
    ) {
        $from = 'V' . (int) $from;
        $to = 'V' . (int) $to;

        $fields = $from::getFields($uuid);
        return $to::fromFields($fields);
    }
}

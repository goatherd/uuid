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
class Uuid {

    /**@#+
     * UUID version
     * @var integer
     */
    const UUID_TIME = 1;       /* Time based UUID */
    const UUID_NAME_MD5 = 3;   /* Name based (MD5) UUID */
    const UUID_RANDOM = 4;     /* Random UUID */
    const UUID_NAME_SHA1 = 5;  /* Name based (SHA1) UUID */
    /**@#-*/

    /**
     * Generate UUID.
     *
     * @param integer $version version id (optional; defaults to 5)
     * @param integer $fmt     format bitmask (optional; defaults to string)
     * @param string  $node    node (optional; defaults to '').
     * @param string  $ns      namespace (optional; defaults to '').
     *
     * @return string
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
    static public function detectFormat($src) {
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
     * Public API, generate a UUID of 'type' in format 'fmt' for
     * the given namespace 'ns' and node 'node'
     *
     * @param integer $type
     * @param integer $fmt
     * @param string $node
     * @param string $ns
     *
     * @return string uuid or NULL on error
     */
    static public function generate(
            $type, $fmt = self::FMT_BYTE, $node = "", $ns = ""
    ) {
        if (
                !isset(self::$m_generate[$type]) ||
                !isset(self::$m_convert[self::FMT_FIELD][$fmt])
        ) {
            return ;
        }
        $func = self::$m_generate[$type];
        $conv = self::$m_convert[self::FMT_FIELD][$fmt];

        $uuid = self::$func($ns, $node);
        return self::$conv($uuid);
    }

    /**
     * Public API, convert a UUID from one format to another
     *
     */
    static public function convert($uuid, $from, $to)
    {
        $conv = self::$m_convert[$from][$to];
        if (!isset($conv))
            return ($uuid);

        return (self::$conv($uuid));
    }
}

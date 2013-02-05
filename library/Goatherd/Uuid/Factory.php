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
 * UUID Generator (factory).
 * Provides version 1, 3, 4 and 5 uuids as specified by
 * http://tools.ietf.org/html/rfc4122
 *
 * @category Goaterd
 * @package  Goatherd\Uuid
 * @author   Maik Penz <maik@phpkuh.de>
 * @license  https://github.com/goatherd/uuid/blob/master/COPYING
 *           dual licensed as BSDL or Apache 2.0
 * @link     https://github.com/goatherd/uuid
 */
class Factory
{
    /**@#+
     * UUID version
     *
     * @var integer
     */
    const UUID_TIME = 1;       // Time based UUID
    const UUID_NAME_MD5 = 3;   // Name based (MD5) UUID
    const UUID_RANDOM = 4;     // Random UUID
    const UUID_NAME_SHA1 = 5;  // Name based (SHA1) UUID
    /**@#-*/

    /**@#+
     * UUID formats
     *
     * @var integer
     */
    const FMT_BYTE = UuidInterface::FMT_BYTE;
    const FMT_STRING = UuidInterface::FMT_STRING;
    const FMT_FIELD = UuidInterface::FMT_FIELD;
    /**@#-*/

    /**
     *
     * @var boolean
     */
    private static $isBigEndian = null;

    /**
     * Public API, convert a UUID from one format to another
     *
     * @param string  $uuid uuid
     * @param integer $from from version number
     * @param integer $to   to version number
     *
     * @return string
     */
    public static function convert(
        $uuid,
        $from,
        $to = self::UUID_NAME_SHA1
    ) {
        $fields = self::getClass($from)->getFields($uuid);
        return self::getClass($to)->fromFields($fields);
    }

    /**
     * Auto-detect UUID format.
     *
     * @param mixed $src test data
     *
     * @return integer
     */
    public static function detectFormat($src)
    {
        if (is_string($src)) {
            return UuidInterface::FMT_STRING;
        } elseif (is_array($src)) {
            $len = count($src);
            $format = ($len % 2) == 0 ? $len : -1;
        } else {
            $format = UuidInterface::FMT_BINARY;
        }

        return $format;
    }

    /**
     * Public API, generate a UUID of any 'version' in 'format' for
     * the given 'namespace' and 'node'.
     *
     * @param integer $version uuid version
     * @param integer $fmt     format
     * @param string  $node    node
     * @param string  $ns      namespace
     *
     * @return string uuid or NULL on error
     */
    public static function generate(
        $version = self::UUID_NAME_SHA1,
        $fmt = UuidInterface::FMT_BYTE,
        $node = '',
        $ns = ''
    ) {
        return self::getClass($version)->generate($fmt, $node, $ns);
    }

    /**
     * Fully qualified class name.
     *
     * @param string|int $version
     *
     * @return UuidInterface
     */
    public static function getClass($version)
    {
        $class = __NAMESPACE__ . '\\V' . (int) $version;
        return new $class();
    }

    /**
     *
     * @return boolean
     */
    public static function isBigEndian()
    {
        if (null === self::$isBigEndian) {
            self::$isBigEndian = pack('L', 0x6162797A) === pack('N', 0x6162797A);
        }
        return self::$isBigEndian;
    }
}

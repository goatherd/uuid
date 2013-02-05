<?php
/**
 * Name based uuid generator.
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
 * Name based uuid generator.
 *
 * @category Goaterd
 * @package  Goatherd\Uuid
 * @author   Maik Penz <maik@phpkuh.de>
 * @license  https://github.com/goatherd/uuid/blob/master/COPYING
 *           dual licensed as BSDL or Apache 2.0
 * @link     https://github.com/goatherd/uuid
 */
abstract class NameBasedUuid extends UuidAbstract
{
    /**
     * Hash method.
     *
     * @var string
     */
    static protected $hashMethod = 'sha1';

    /**
     * Version id.
     *
     * @var integer
     */
    static protected $version;

    /**
     * Generate uuid field.
     *
     * @param string $node node
     * @param string $ns   namespace
     *
     * @return array
     */
    public static function generateField($node = '', $ns = '')
    {
        $ns_fmt = $ns === '' ? self::FMT_STRING : Factory::detectFormat($ns);
        if ($ns_fmt == self::FMT_BYTE) {
            $field = self::convByte2field($ns);
        } elseif ($ns_fmt == self::FMT_STRING) {
            $field = self::convString2field($ns);
        } else {
            $field = $ns;
        }

        // Swap byte order to keep it in big endian on all platforms
        if (self::isBigEndian()) {
            $field[self::FIELD_TIME_LOW] = self::swap32($field[self::FIELD_TIME_LOW]);
            $field[self::FIELD_TIME_MID] = self::swap16($field[self::FIELD_TIME_MID]);
            $field[self::FIELD_TIME_HI] = self::swap16($field[self::FIELD_TIME_HI]);
        }

        // Convert the namespace to binary and concatenate node
        $raw = self::convField2binary($field) . $node;

        // Hash the namespace and node and convert to a byte array
        $hash = self::$hashMethod;
        $val = $hash($raw, true);
        $byte = array_values(unpack('C16', $val));

        // Convert byte array to a field array
        $field = self::convByte2field($byte);

        // Swap byte order to keep it in big endian on all platforms
        if (self::isBigEndian()) {
            $field[self::FIELD_TIME_LOW] = self::swap32($field[self::FIELD_TIME_LOW]);
            $field[self::FIELD_TIME_MID] = self::swap16($field[self::FIELD_TIME_MID]);
            $field[self::FIELD_TIME_HI] = self::swap16($field[self::FIELD_TIME_HI]);
        }

        // Apply version and constants
        $field[self::FIELD_CLOCK_SEQUENCE_HI] &= 0x3f;
        $field[self::FIELD_CLOCK_SEQUENCE_HI] |= (1 << 7);
        $field[self::FIELD_TIME_HI] &= 0x0fff;
        $field[self::FIELD_TIME_HI] |= (self::$version << 12);

        return $field;
    }
}

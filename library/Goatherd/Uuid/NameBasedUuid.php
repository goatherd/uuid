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
    static protected $hash = 'sha1';

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
        $ns_fmt = $ns === ''?self::FMT_STRING:Factory::detectFormat($ns);
        if ($ns_fmt == self::FMT_BYTE) {
            $field = self::convByte2field($ns);
        } elseif ($ns_fmt == self::FMT_STRING) {
            $field = self::convString2field($ns);
        } else {
            $field = $ns;
        }

        // Swap byte order to keep it in big endian on all platforms
        $field['time_low'] = self::swap32($field['time_low']);
        $field['time_mid'] = self::swap16($field['time_mid']);
        $field['time_hi'] = self::swap16($field['time_hi']);

        // Convert the namespace to binary and concatenate node
        $raw = self::convField2binary($field);
        $raw .= $node;

        // Hash the namespace and node and convert to a byte array
        $hash = self::$hash;
        $val = $hash($raw, true);
        $tmp = unpack('C16', $val);
        foreach ($tmp as $k => $v) {
            $byte[$k - 1] = $v;
        }

        // Convert byte array to a field array
        $field = self::convByte2field($byte);

        $field['time_low'] = self::swap32($field['time_low']);
        $field['time_mid'] = self::swap16($field['time_mid']);
        $field['time_hi'] = self::swap16($field['time_hi']);

        // Apply version and constants
        $field['clock_seq_hi'] &= 0x3f;
        $field['clock_seq_hi'] |= (1 << 7);
        $field['time_hi'] &= 0x0fff;
        $field['time_hi'] |= (self::$version << 12);

        return $field;
    }
}

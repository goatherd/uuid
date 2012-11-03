<?php
/**
 * Uuid generator base.
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
 * Uuid generator base.
 *
 * @category Goaterd
 * @package  Goatherd\Uuid
 * @author   Maik Penz <maik@phpkuh.de>
 * @license  https://github.com/goatherd/uuid/blob/master/COPYING
 *           dual licensed as BSDL or Apache 2.0
 * @link     https://github.com/goatherd/uuid
 */
abstract class UuidAbstract
implements UuidInterface
{
    /**
     * Uuid wrapper for converter.
     *
     * @var array
     */
    protected static $uuidFields = array(
            'time_low' => 0,      /* 32-bit */
            'time_mid' => 0,      /* 16-bit */
            'time_hi' => 0,       /* 16-bit */
            'clock_seq_hi' => 0,  /*  8-bit */
            'clock_seq_low' => 0, /*  8-bit */
            'node' => array()     /* 48-bit */
    );

    /**
     * Swap byte order of a 32-bit number
     *
     * @param integer $x dword
     *
     * @return integer
     */
    static public function swap32($x)
    {
        return (($x & 0x000000ff) << 24) | (($x & 0x0000ff00) << 8) |
        (($x & 0x00ff0000) >> 8) | (($x & 0xff000000) >> 24);
    }

    /**
     * Swap byte order of a 16-bit number
     *
     * @param integer $x word
     *
     * @return integer
     */
    static public function swap16($x)
    {
        return (($x & 0x00ff) << 8) | (($x & 0xff00) >> 8);
    }

    /**
     * Convert.
     *
     * @param string $src source
     *
     * @return string
     */
    static public function convField2byte($src)
    {
        $uuid[0] = ($src['time_low'] & 0xff000000) >> 24;
        $uuid[1] = ($src['time_low'] & 0x00ff0000) >> 16;
        $uuid[2] = ($src['time_low'] & 0x0000ff00) >> 8;
        $uuid[3] = ($src['time_low'] & 0x000000ff);
        $uuid[4] = ($src['time_mid'] & 0xff00) >> 8;
        $uuid[5] = ($src['time_mid'] & 0x00ff);
        $uuid[6] = ($src['time_hi'] & 0xff00) >> 8;
        $uuid[7] = ($src['time_hi'] & 0x00ff);
        $uuid[8] = $src['clock_seq_hi'];
        $uuid[9] = $src['clock_seq_low'];

        for ($i = 0; $i < 6; $i++) {
            $uuid[10+$i] = $src['node'][$i];
        }

        return ($uuid);
    }

    /**
     * Convert.
     *
     * @param string $src source
     *
     * @return string
     */
    static public function convField2string($src)
    {
        $str = sprintf(
            '%08x-%04x-%04x-%02x%02x-%02x%02x%02x%02x%02x%02x',
            ($src['time_low']), ($src['time_mid']), ($src['time_hi']),
            $src['clock_seq_hi'], $src['clock_seq_low'],
            $src['node'][0], $src['node'][1], $src['node'][2],
            $src['node'][3], $src['node'][4], $src['node'][5]
        );
        return ($str);
    }

    /**
     * Convert.
     *
     * @param string $src source
     *
     * @return string
     */
    static public function convField2binary($src)
    {
        $byte = self::convField2byte($src);
        return self::convByte2binary($byte);
    }

    /**
     * Convert.
     *
     * @param string $uuid source
     *
     * @return string
     */
    static public function convByte2field($uuid)
    {
        $field = static::$uuidFields;
        $field['time_low'] = ($uuid[0] << 24) | ($uuid[1] << 16) |
            ($uuid[2] << 8) | $uuid[3];
        $field['time_mid'] = ($uuid[4] << 8) | $uuid[5];
        $field['time_hi'] = ($uuid[6] << 8) | $uuid[7];
        $field['clock_seq_hi'] = $uuid[8];
        $field['clock_seq_low'] = $uuid[9];

        for ($i = 0; $i < 6; $i++) {
            $field['node'][$i] = $uuid[10+$i];
        }
        return ($field);
    }

    /**
     * Convert.
     *
     * @param string $src source
     *
     * @return string
     */
    static public function convByte2string($src)
    {
        $field = self::convByte2field($src);
        return self::convField2string($field);
    }

    /**
     * Convert.
     *
     * @param string $src source
     *
     * @return string
     */
    static public function convByte2binary($src)
    {
        $raw = pack(
            'C16', $src[0], $src[1], $src[2], $src[3],
            $src[4], $src[5], $src[6], $src[7], $src[8], $src[9],
            $src[10], $src[11], $src[12], $src[13], $src[14], $src[15]
        );
        return ($raw);
    }

    /**
     * Convert.
     *
     * @param string $src source
     *
     * @return string
     */
    static public function convString2field($src)
    {
        $parts = sscanf($src, '%x-%x-%x-%x-%02x%02x%02x%02x%02x%02x');
        $field = static::$uuidFields;
        $field['time_low'] = ($parts[0]);
        $field['time_mid'] = ($parts[1]);
        $field['time_hi'] = ($parts[2]);
        $field['clock_seq_hi'] = ($parts[3] & 0xff00) >> 8;
        $field['clock_seq_low'] = $parts[3] & 0x00ff;

        for ($i = 0; $i < 6; $i++) {
            $field['node'][$i] = $parts[4+$i];
        }

        return ($field);
    }

    /**
     * Convert.
     *
     * @param string $src source
     *
     * @return string
     */
    static public function convString2byte($src)
    {
        $field = self::convString2field($src);
        return self::convField2byte($field);
    }

    /**
     * Convert.
     *
     * @param string $src source
     *
     * @return string
     */
    static public function convString2binary($src)
    {
        $byte = self::convString2byte($src);
        return self::convByte2binary($byte);
    }

    /**
     * Generate uuid field.
     *
     * @param string $node node
     * @param string $ns   namespace
     *
     * @return array
     */
    abstract static public function generateField($node = '', $ns = '');

    /**
     * Generate uuid.
     *
     * @param integer $fmt  format
     * @param string  $node node
     * @param string  $ns   namespace
     *
     * @return string
     */
    public static function generate($fmt = self::FMT_BYTE, $node = '', $ns = '')
    {
        $field = static::generateField($node, $ns);
        $uuid = null;
        switch($fmt) {
        case self::FMT_BINARY:
            $uuid = self::convField2binary($field);
            break;

        case self::FMT_BYTE:
            $uuid = self::convField2byte($field);
            break;

        case self::FMT_STRING:
            $uuid = self::convField2string($field);
            break;

        default:
        }

        return $uuid;
    }
}
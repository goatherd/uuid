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

use Goatherd\Uuid\Exception\InvalidArgumentException;
use Goatherd\Uuid\Exception\LogicException;

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
abstract class UuidAbstract implements UuidInterface
{
    /**
     * Uuid meta format used for convertion.
     *
     * @var array
     */
    protected static $uuidFields = array(
        self::FIELD_TIME_LOW => 0,           /* 32-bit */
        self::FIELD_TIME_MID => 0,           /* 16-bit */
        self::FIELD_TIME_Hi => 0,            /* 16-bit */
        self::FIELD_CLOCK_SEQUENCE_LOW => 0, /*  8-bit */
        self::FIELD_CLOCK_SEQUENCE_HI => 0,  /*  8-bit */
        0 => 0, // 6 node words
        1 => 0,
        2 => 0,
        3 => 0,
        4 => 0,
        5 => 0,
    );

    /**
     *
     * @var boolean
     */
    private static $isBigEndian = null;

    /**
     * Swap byte order of a 32-bit number
     *
     * @param integer $x dword
     *
     * @return integer
     */
    public static function swap32($x)
    {
        return self::isBigEndian()
            ? (($x & 0x000000ff) << 24) | (($x & 0x0000ff00) << 8) | (($x & 0x00ff0000) >> 8) | (($x & 0xff000000) >> 24)
            : $x;
    }

    /**
     * Swap byte order of a 16-bit number
     *
     * @param integer $x word
     *
     * @return integer
     */
    public static function swap16($x)
    {
        return self::isBigEndian()
            ? (($x & 0x00ff) << 8) | (($x & 0xff00) >> 8)
            : $x;
    }

    /**
     *
     * @return boolean
     */
    protected static function isBigEndian()
    {
        return null === self::$isBigEndian
            ? self::$isBigEndian = pack('L', 0x6162797A) == pack('N', 0x6162797A)
            : self::$isBigEndian;
    }

    /**
     * Convert.
     *
     * @param string $src source
     *
     * @return string
     */
    public static function convField2byte($src)
    {
        $uuid[0] = ($src[self::FIELD_TIME_LOW] & 0xff000000) >> 24;
        $uuid[1] = ($src[self::FIELD_TIME_LOW] & 0x00ff0000) >> 16;
        $uuid[2] = ($src[self::FIELD_TIME_LOW] & 0x0000ff00) >> 8;
        $uuid[3] = ($src[self::FIELD_TIME_LOW] & 0x000000ff);
        $uuid[4] = ($src[self::FIELD_TIME_MID] & 0xff00) >> 8;
        $uuid[5] = ($src[self::FIELD_TIME_MID] & 0x00ff);
        $uuid[6] = ($src[self::FIELD_TIME_HI] & 0xff00) >> 8;
        $uuid[7] = ($src[self::FIELD_TIME_HI] & 0x00ff);
        $uuid[8] = $src[self::FIELD_CLOCK_SEQUENCE_HI];
        $uuid[9] = $src[self::FIELD_CLOCK_SEQUENCE_LOW];

        for ($i = 0; $i < 6; $i++) {
            $uuid[10+$i] = $src[$i];
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
    public static function convField2string($src)
    {
        $str = sprintf(
            '%08x-%04x-%04x-%02x%02x-%02x%02x%02x%02x%02x%02x',
            $src[self::FIELD_TIME_LOW],
            $src[self::FIELD_TIME_MID],
            $src[self::FIELD_TIME_HI],
            $src[self::FIELD_CLOCK_SEQUENCE_HI],
            $src[self::FIELD_CLOCK_SEQUENCE_LOW],
            $src[0],
            $src[1],
            $src[2],
            $src[3],
            $src[4],
            $src[5]
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
    public static function convField2binary($src)
    {
        $byte = self::convField2byte($src);
        return self::convByte2binary($byte);
    }

    /**
     * Convert.
     *
     * @param string $src source
     *
     * @return string
     */
    public static function convByte2field($src)
    {
        $field = static::$srcFields;
        $field[self::FIELD_TIME_LOW] = ($src[0] << 24) | ($src[1] << 16) | ($src[2] << 8) | $src[3];
        $field[self::FIELD_TIME_MID] = ($src[4] << 8) | $src[5];
        $field[self::FIELD_TIME_HI] = ($src[6] << 8) | $src[7];
        $field[self::FIELD_CLOCK_SEQUENCE_HI] = $src[8];
        $field[self::FIELD_CLOCK_SEQUENCE_LOW] = $src[9];
        for ($i = 0; $i < 6; $i++) {
            $field[$i] = $src[10+$i];
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
    public static function convByte2string($src)
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
    public static function convByte2binary($src)
    {
        $raw = pack('C16', $src[0], $src[1], $src[2], $src[3], $src[4], $src[5], $src[6], $src[7], $src[8], $src[9], $src[10], $src[11], $src[12], $src[13], $src[14], $src[15]);
        return $raw;
    }

    /**
     * Convert.
     *
     * @param string $src source
     *
     * @return string
     */
    public static function convString2field($src)
    {
        $parts = sscanf($src, '%x-%x-%x-%x-%02x%02x%02x%02x%02x%02x');
        $field = static::$uuidFields;
        $field[self::FIELD_TIME_LOW] = ($parts[0]);
        $field[self::FIELD_TIME_MID] = ($parts[1]);
        $field[self::FIELD_TIME_HI] = ($parts[2]);
        $field[self::FIELD_CLOCK_SEQUENCE_HI] = ($parts[3] & 0xff00) >> 8;
        $field[self::FIELD_CLOCK_SEQUENCE_LOW] = $parts[3] & 0x00ff;

        for ($i = 0; $i < 6; $i++) {
            $field[$i] = $parts[4+$i];
        }

        return $field;
    }

    /**
     * Convert.
     *
     * @param string $src source
     *
     * @return string
     */
    public static function convString2byte($src)
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
    public static function convString2binary($src)
    {
        $byte = self::convString2byte($src);
        return self::convByte2binary($byte);
    }

    /** {@inheritdoc} */
    public function generate($format = self::FMT_BYTE, $node = '', $name = '')
    {
        $field = $this->generateField($node, $name);
        return static::convertFieldTo($field, $format);
    }

    /**
     *
     * @param array   $field
     * @param integer $format
     *
     * @return string
     * @throws InvalidArgumentException
     */
    public static function convertFieldTo(array $field, $format)
    {
        switch($format) {
            case static::FMT_BINARY:
                $uuid = self::convField2binary($field);
                break;
            case static::FMT_BYTE:
                $uuid = self::convField2byte($field);
                break;
            case static::FMT_STRING:
                $uuid = self::convField2string($field);
                break;
            default:
                throw new InvalidArgumentException(sprintf('Unsupported format "%s".', $fmt));
        }

        return $uuid;
    }

    /** {@inheritdoc} */
    public function getFields($uuid)
    {
        throw new LogicException('Call to abstract ' . __CLASS__ . ':' . __METHOD__);
    }

    /** {@inheritdoc} */
    public function fromFields(array $fields)
    {
        throw new LogicException('Call to abstract ' . __CLASS__ . ':' . __METHOD__);
    }

    /**
     * Generate uuid field.
     *
     * @param string $node node
     * @param string $ns   namespace
     *
     * @return array
     */
    abstract public function generateField($node = '', $ns = '');
}

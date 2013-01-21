<?php
/**
 * Uuid generator Interface.
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
 * Uuid generator interface.
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
interface UuidInterface
{
    /**@#+
     * UUID formats (bitmask).
     *
     * @var integer
     */
    const FMT_FIELD = 100;
    const FMT_STRING = 101;
    const FMT_BINARY = 102;
    const FMT_QWORD = 1;     // Quad-word, 128-bit (not impl.)
    const FMT_DWORD = 2;     // Double-word, 64-bit (not impl.)
    const FMT_WORD = 4;      // Word, 32-bit (not impl.)
    const FMT_SHORT = 8;     // Short (not impl.)
    const FMT_BYTE = 16;     // Byte
    const FMT_DEFAULT = 16;
    /**@#-*/

    /**
     * Generate uuid.
     *
     * @param integer $fmt  format
     * @param string  $node node
     * @param string  $ns   namespace
     *
     * @return string
     */
    public static function generate($fmt = self::FMT_BYTE, $node = '', $ns = '');

    /**
     * Give array for conversion.
     *
     * @param string $uuid uuid
     *
     * @return array
     */
    public static function getFields($uuid);

    /**
     * Generate uuid from array.
     *
     * @param array $fields uuid fields
     *
     * @return string uuid
     */
    public static function fromFields(array $fields);
}

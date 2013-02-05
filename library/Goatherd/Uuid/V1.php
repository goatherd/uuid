<?php
/**
 * Version 1 uuid generator.
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
 * Version 1 uuid generator (time based).
 *
 * @category Goaterd
 * @package  Goatherd\Uuid
 * @author   Maik Penz <maik@phpkuh.de>
 * @license  https://github.com/goatherd/uuid/blob/master/COPYING
 *           dual licensed as BSDL or Apache 2.0
 * @link     https://github.com/goatherd/uuid
 */
class V1 extends UuidAbstract
{
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
        $uuid = static::$uuidFields;

        /*
         * Get current time in 100 ns intervals. The magic value
         * is the offset between UNIX epoch and the UUID UTC
         * time base October 15, 1582.
         */
        $tp = gettimeofday();
        $time = ($tp['sec'] * 10000000) + ($tp['usec'] * 10) +
            0x01B21DD213814000;

        $uuid[self::FIELD_TIME_LOW] = $time & 0xffffffff;
        // Work around PHP 32-bit bit-operation limits
        $high = intval($time / 0xffffffff);
        $uuid[self::FIELD_TIME_MID] = $high & 0xffff;
        $uuid[self::FIELD_TIME_HI] = (($high >> 16) & 0xfff) | (Factory::UUID_TIME << 12);

        /*
         * We don't support saved state information and generate
         * a random clock sequence each time.
         */
        $uuid[self::FIELD_CLOCK_SEQUENCE_HI] = 0x80 | mt_rand(0, 64);
        $uuid[self::FIELD_CLOCK_SEQUENCE_LOW] = mt_rand(0, 255);

        /*
         * Node should be set to the 48-bit IEEE node identifier, but
         * we leave it for the user to supply the node.
         */
        for ($i = 0; $i < 6; $i++) {
            $uuid[$i] = ord(substr($node, $i, 1));
        }

        return $uuid;
    }
}

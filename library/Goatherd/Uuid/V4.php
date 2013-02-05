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
 * Version 4 uuid generator (random).
 *
 * @category Goaterd
 * @package  Goatherd\Uuid
 * @author   Maik Penz <maik@phpkuh.de>
 * @license  https://github.com/goatherd/uuid/blob/master/COPYING
 *           dual licensed as BSDL or Apache 2.0
 * @link     https://github.com/goatherd/uuid
 */
class V4 extends UuidAbstract
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
        $uuid = self::$uuidFields;

        // order should be compatible to prior version of this generator
        $uuid[self::FIELD_TIME_HI] = (4 << 12) | (mt_rand(0, 0x1000));
        $uuid[self::FIELD_CLOCK_SEQUENCE_HI] = (1 << 7) | mt_rand(0, 128);
        $uuid[self::FIELD_TIME_LOW] = mt_rand(0, 0xffff) + (mt_rand(0, 0xffff) << 16);
        $uuid[self::FIELD_TIME_MID] = mt_rand(0, 0xffff);
        $uuid[self::FIELD_CLOCK_SEQUENCE_LOW] = mt_rand(0, 255);
        for ($i = 0; $i < 6; $i++) {
            $uuid[$i] = mt_rand(0, 255);
        }
        return $uuid;
    }
}

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

use Goatherd\Uuid\UuidInterface;

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
}
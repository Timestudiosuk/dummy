<?php

/**
 * AttributeType
 *
 * PHP version 5
 *
 * @author    Jim Wigginton <terrafrost@php.net>
 * @copyright 2016 Jim Wigginton
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 * @link      http://phpseclib.sourceforge.net
 */
namespace Beehive\phpseclib3\File\ASN1\Maps;

use Beehive\phpseclib3\File\ASN1;
/**
 * AttributeType
 *
 * @author  Jim Wigginton <terrafrost@php.net>
 */
abstract class AttributeType
{
    const MAP = ['type' => ASN1::TYPE_OBJECT_IDENTIFIER];
}
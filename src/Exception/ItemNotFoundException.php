<?php
/**
 * ItemNotFoundException
 *
 *
 * @package      Mooti
 * @subpackage   Container
 * @author       Ken Lalobo <ken@mooti.io>
 */

namespace Mooti\Container\Exception;

use Interop\Container\Exception\NotFoundException;

class ItemNotFoundException extends \Exception implements NotFoundException
{
}
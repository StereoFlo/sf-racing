<?php

declare(strict_types = 1);

namespace App\Common\Domain\Exception;

use Exception;

class DomainException extends Exception implements DomainExceptionInterface
{
}

<?php

declare(strict_types = 1);

namespace App\Common\Domain\State;

interface StateInterface
{
    /**
     * @return mixed
     */
    public function commit(CommandInterface $command);

    /**
     * @return mixed
     */
    public function query(QueryInterface $query);
}

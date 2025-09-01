<?php

namespace src\Domain\Repository;

/**
 * @template T of object
 */
interface RepositoryInterface extends ReadRepositoryInterface, WriteRepositoryInterface, SearchRepositoryInterface
{
}

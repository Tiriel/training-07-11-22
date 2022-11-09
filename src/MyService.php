<?php

namespace App;

use Doctrine\ORM\EntityManagerInterface;

class MyService
{
    public function __construct(
        private EntityManagerInterface $manager,
        private string $confKey
    ) {}

    public function doSomething()
    {
        //
    }
}
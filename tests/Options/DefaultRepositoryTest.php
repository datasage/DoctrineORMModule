<?php

declare(strict_types=1);

namespace DoctrineORMModuleTest\Options;

use DoctrineORMModuleTest\Assets\Entity\EntityWithoutRepository;
use DoctrineORMModuleTest\Assets\RepositoryClass;
use DoctrineORMModuleTest\Framework\TestCase;

class DefaultRepositoryTest extends TestCase
{
    public function testEntityHasDefaultRepositoryInstance(): void
    {
        $entityWithDefaultRepository = EntityWithoutRepository::class;

        $repository = $this->getEntityManager()->getRepository($entityWithDefaultRepository);
        $this->assertInstanceOf(RepositoryClass::class, $repository);
    }
}

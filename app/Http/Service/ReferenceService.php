<?php

namespace App\Http\Service;

use App\Models\Invitation;
use Illuminate\Database\Eloquent\Model;

class ReferenceService
{
    /**
     * @var array
     */
    protected $entityMapping = [];

    public function __construct()
    {
        $this->addEntityMapping(Invitation::class, 'IN', 6);
    }

    /**
     * @param Model|string $entity
     * @return string
     * @throws \Exception
     */
    public function getReference($entity): string
    {
        $entityClass = is_object($entity) ? get_class($entity) : $entity;

        $mapping = $this->getEntityMapping($entityClass);

        return $this->generateRandomString($mapping['code'], $mapping['length']);
    }

    public function addEntityMapping($class, $prefix, $length): self
    {
        $this->entityMapping[$class] = [
            'code' => $prefix,
            'length' => $length,
        ];

        return $this;
    }

    protected function getEntityMapping(string $entityClass): array
    {
        if (!isset($this->entityMapping[$entityClass])) {
            throw new \Exception('Entity not found');
        }

        return $this->entityMapping[$entityClass];
    }

    function generateRandomString(string $prefix, int $length): string
    {

        // md5 the timestamps and returns substring
        // of specified length
        return sprintf('%s%s', $prefix, substr(md5(time()), 0, $length));
    }
}

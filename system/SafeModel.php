<?php

namespace CodeIgniter;

use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\Database\Exceptions\DataException;
use CodeIgniter\Exceptions\ModelException;
use ReflectionException;
use Closure;

class SafeModel extends Model
{
    protected function doInsert(array $data)
    {
        try {
            return parent::doInsert($data);
        } catch (DataException) {
            return false;
        }
    }

    protected function doInsertBatch(?array $set = null, ?bool $escape = null, int $batchSize = 100, bool $testing = false)
    {
        try {
            return parent::doInsertBatch($set, $escape, $batchSize, $testing);
        } catch (DataException) {
            return false;
        }
    }

    protected function doUpdateBatch(?array $set = null, ?string $index = null, int $batchSize = 100, bool $returnSQL = false)
    {
        try {
            return parent::doUpdateBatch($set, $index, $batchSize, $returnSQL);
        } catch (DatabaseException) {
            return false;
        }
    }

    protected function doDelete($id = null, bool $purge = false)
    {
        try {
            return parent::doDelete($id, $purge);
        } catch (DatabaseException) {
            return false;
        }
    }

    public function chunk(int $size, Closure $userFunc)
    {
        try {
            parent::chunk($size, $userFunc);
        } catch (DataException) {}
    }

    public function builder(?string $table = null)
    {
        try {
            return parent::builder($table);
        } catch (ModelException) {
            return null;
        }
    }

    public function insert($data = null, bool $returnID = true)
    {
        try {
            return parent::insert($data, $returnID);
        } catch (ReflectionException) {
            return false;
        }
    }

    public function update($id = null, $data = null): bool
    {
        try {
            return parent::update($id, $data);
        } catch (ReflectionException) {
            return false;
        }
    }

    protected function objectToRawArray($data, bool $onlyChanged = true, bool $recursive = false): ?array
    {
        try {
            return parent::objectToRawArray($data, $onlyChanged, $recursive);
        } catch (ReflectionException) {
            return null;
        }
    }
}
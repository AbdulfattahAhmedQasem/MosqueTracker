<?php

namespace App\Traits;

use App\Exceptions\OptimisticLockException;
use Illuminate\Database\Eloquent\Model;

trait HasOptimisticLocking
{
    /**
     * Boot the trait.
     */
    protected static function bootHasOptimisticLocking(): void
    {
        static::updating(function (Model $model) {
            static::checkVersionConflict($model);
            static::incrementVersion($model);
        });
    }

    /**
     * Check if there's a version conflict.
     *
     * @param Model $model
     * @throws OptimisticLockException
     */
    protected static function checkVersionConflict(Model $model): void
    {
        // Get the original version from the database
        $original = static::query()
            ->where($model->getKeyName(), $model->getKey())
            ->first();

        if (!$original) {
            return;
        }

        $currentVersion = $model->version;
        $databaseVersion = $original->version;

        // If versions don't match, throw an exception
        if ($currentVersion !== $databaseVersion) {
            throw new OptimisticLockException(
                class_basename($model),
                $currentVersion,
                $databaseVersion
            );
        }
    }

    /**
     * Increment the version number.
     *
     * @param Model $model
     */
    protected static function incrementVersion(Model $model): void
    {
        $model->version = ($model->version ?? 0) + 1;
    }

    /**
     * Initialize version for new models.
     */
    protected static function bootHasOptimisticLockingForCreate(): void
    {
        static::creating(function (Model $model) {
            if (!isset($model->version)) {
                $model->version = 1;
            }
        });
    }

    /**
     * Get the current version.
     *
     * @return int
     */
    public function getVersion(): int
    {
        return $this->version ?? 1;
    }

    /**
     * Reload the model with fresh version from database.
     *
     * @return $this
     */
    public function refreshVersion(): static
    {
        $fresh = static::query()->find($this->getKey());
        
        if ($fresh) {
            $this->version = $fresh->version;
        }

        return $this;
    }
}

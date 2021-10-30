<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface EloquentRepositoryInterface 
{
    /**
     * Get All Models
     * 
     * @param array $columns
     * @param array $relations
     * @param int $perPage
     * @param bol $hasPagination
     * @return Collection
     */
    public function all(array $columns = ['*'], array $relations = [], int $perPage = 25, bool $hasPagination = false): Collection;

    /**
     * Get all trashed models
     * 
     * @return Collection
     */
    public function allTrashed(): Collection;

    /**
     * Find Model by id
     * 
     * @param int $modelId
     * @param array $columns
     * @param array $relations
     * @param array $appends
     * @return Model
     */
    public function findById(
        int $modelId,
        array $columns = ['*'],
        array $relations = [],
        array $appends = []
    ): ?Model;

    /**
     * Find trashed model by id
     * 
     * @param int $modelId
     * @return Model
     */
    public function findTrashedById(int $modelId): ?Model;

    /**
     * Find only trashed model by id
     * 
     * @param int $modelId
     * @return Model
     */
    public function findOnlyTrashedById(int $modelId): ?Model;

    /**
     * Create a model
     * 
     * @param array $payload
     * @return Model
     */
    public function create(Array $payload): ?Model;

    /**
     * Update existing model.
     * 
     * @param int $modelId
     * @param array $payload
     * @return bool
     */
    public function update(int $modelId, array $payload): bool;

    /**
     * Delete model by id
     * 
     * @param int $modelId
     * @return bool
     */
    public function deleteById(int $modelId): bool;

    /**
     * Restore model by id
     * 
     * @param int $modelId
     * @return bool
     */
    public function restoreById(int $modelId): bool;

    /**
     * Permanently delete model by id
     * 
     * @param int $modelId
     * @return bool
     */
    public function permanentlyDeleteById(int $modelId): bool;


    

    // /**
    //  * Handle the Model "created" event.
    //  *
    //  * @param  Model  $model
    //  * @return void
    //  */
    // public function created(Model $model): void;

    // /**
    //  * Handle the Model "updated" event.
    //  *
    //  * @param  Model  $model
    //  * @return void
    //  */
    // public function updated(Model $model): void;

    // /**
    //  * Handle the Model "deleted" event.
    //  *
    //  * @param  Model  $model
    //  * @return void
    //  */
    // public function deleted(Model $model): void;

    // /**
    //  * Handle the Model "forceDeleted" event.
    //  *
    //  * @param  Model  $model
    //  * @return void
    //  */
    // public function forceDeleted(Model $model): void;
}
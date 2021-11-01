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
     * @param array $requestByW2ui
     * @return Collection
     */
    public function all(array $columns = ['*'], array $relations = [], int $perPage = 25, bool $hasPagination = false, array $requestByW2ui = []): Collection;

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
     * Validação da criação
     * 
     * @param array $payload
     * @return array $error
     */
    public function validateOnCreate(array $payload): array;
    
    /**
     * Create a model
     * 
     * @param array $payload
     * @return Collection
     */
    public function create(Array $payload): ?Collection;

    
    /**
     * Validação da atualização
     * 
     * @param int $modelId
     * @param array $payload
     * @return array $error
     */
    public function validateOnUpdate(int $modelId, array $payload): array;
    
    /**
     * Update existing model.
     * 
     * @param int $modelId
     * @param array $payload
     * @return array
     */
    public function update(int $modelId, array $payload): array;

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
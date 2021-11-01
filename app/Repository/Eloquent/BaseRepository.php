<?php

namespace App\Repository\Eloquent;

use App\Helpers\Helper;
use App\Repository\EloquentRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class BaseRepository implements EloquentRepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository construct
     * 
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }


    /**
     * Get rules for validation model in create
     * 
     * @return array $rules
     */
    public function getRulesCreate(): array
    {
        return [];
    }

    /**
     * Get rules for validation model in update
     * 
     * @return array $rules
     */
    public function getRulesUpdate(): array
    {
        return [];
    }

    /**
     * Get All Models
     * 
     * @param array $columns
     * @param array $relations
     * @param int $perPage
     * @param bool $hasPagination
     * @param array $requestByW2ui
     * @return Collection $collection
     */
    public function all(
        array $columns = ['*'],
        array $relations = [],
        int $perPage = 25,
        bool $hasPagination = false,
        array $requestByW2ui = []
    ): Collection
    {
        $collection = new Collection();
        try {
            $r = $requestByW2ui;
            if (isset($r['w2ui'])) {
                $limit = $r['limit'] ?? 50;
                $offset = $r['offset'] ?? 0;
                $page = ($offset / $limit) + 1;
                $where = [];
                $data = $this->model->orWhere($where);
                if (isset($r['search'])) {
                    Helper::createWhereByW2uiGrid($r['search'],$r['searchLogic'] ?? 'or', $data);
                }
                if (isset($r['sort'])) {
                    foreach ($r['sort'] as $e) {
                        $data = $data->orderBy($e['field'], $e['direction']);
                    }
                }
                // return new Collection($data->rawSql());
                if (isset($r['withs'])) {
                    $data = $data->with($r['withs']);
                }
                if (isset($r['isSelect'])) {
                    $r['data'] = $data->get($r['isSelect']);
                    $r['total'] = sizeof($r['data']);
                } else {
                    $r = $data->paginate($limit, $r['select'] ?? ['*'], 'page', $page)->toArray();
                }

                $response = [
                    'status' => 'success',
                    'total' => $r['total'] ?? 0,
                    'records' => $r['data'] ?? [],
                    'summary'=> []
                ];
                $collection = new Collection($response);
            } else {
                if ($relations) {
                    $columns = array_merge($columns, ['id']);
                }
                $query = $this->model->with($relations);
                if ($hasPagination) {
                    $collection =  new Collection($query->paginate($perPage, $columns));
                } else {
                    $collection =  $query->get($columns);
                }
            }
        } catch (\Throwable $th) {
            throw new Exception(json_encode($th->getMessage()), 200);
        }
        return $collection;
    }

    /**
     * Get all trashed models
     * 
     * @return Collection
     */
    public function allTrashed(): Collection
    {
        return $this->model->onlyTrashed()->get();
    }

    /**
     * Find Model by id
     * 
     * @param int $modelId
     * @param array $columns
     * @param array $relations
     * @param array $appends
     * @return Model $model
     */
    public function findById(
        int $modelId,
        array $columns = ['*'],
        array $relations = [],
        array $appends = []
    ): ?Model {
        try {
            if ($relations) {
                $columns = array_merge($columns, ['id']);
            }
            $this->model = $this->model->select($columns)->with($relations)->findOrFail($modelId)->append($appends);
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage(), 404);
        }
        return $this->model;
    }

    /**
     * Find trashed model by id
     * 
     * @param int $modelId
     * @return Model
     */
    public function findTrashedById(int $modelId): ?Model
    {
        return $this->model->withTrashed()->findOrFail($modelId);
    }

    /**
     * Find only trashed model by id
     * 
     * @param int $modelId
     * @return Model
     */
    public function findOnlyTrashedById(int $modelId): ?Model
    {
        return $this->model->onlyTrashed()->findOrFail($modelId);
    }


    
    /**
     * Validação da criação
     * 
     * @param array $payload
     * @return array $error
     */
    public function validateOnCreate(array $payload): array
    {
        $error = [];
        $validator = Validator::make($payload, $this->getRulesCreate());
        if ($validator->fails()) {
            $error = ['status'=> false, 'message'=> 'não passou na validação','error' => $validator->errors()->getMessages()];
        }
        return $error;
    }
    /**
     * Create a model
     * 
     * @param array $payload
     * @return Collection
     */
    public function create(array $payload): ?Collection
    {
        try {
            $this->beforeCreate($payload);
            $model = $this->model->create($payload);
            if ($model) {
                $this->created();
            }
            $collection = new Collection([
                'status' => true,
                'message' => 'Criado com sucesso',
                'model' => $model->fresh()
            ]);
            return $collection;
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage(), 400);
        }
    }

    /**
     * Validação da atualização
     * 
     * @param array $payload
     * @return array $error
     */
    public function validateOnUpdate(int $modelId, array $payload): array
    {
        $this->model = $this->findById($modelId);
        $error = [];
        $validator = Validator::make($payload, $this->getRulesUpdate());
        if ($validator->fails()) {
            $error = ['status'=> false, 'message'=> 'não passou na validação','error' => $validator->errors()->getMessages()];
        }
        return $error;
    }
    /**
     * Update existing model.
     * 
     * @param int $modelId
     * @param array $payload
     * @return array
     */
    public function update(int $modelId, array $payload): array
    {
        try {
            // $this->model = $this->findById($modelId);
            $this->beforeCreate($payload);
            $bool = $this->model->update($payload);
            if ($bool) {
                $this->updated();
            }
            $result = ['status' => $bool, 'message' => 'Atualiado com Sucesso.'];
        } catch (\Throwable $th) {
            $result = ['status' => false,'message' => $th->getMessage()];
        }
        return $result;
    }

    /**
     * Delete model by id
     * 
     * @param int $modelId
     * @return bool $bool
     */
    public function deleteById(int $modelId): bool
    {
        try {
            $bool = $this->findById($modelId)->delete();
            if ($bool) {
                $this->deleted();         
            }
            // return $bool;
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Restore model by id
     * 
     * @param int $modelId
     * @return bool
     */
    public function restoreById(int $modelId): bool
    {
        return $this->findOnlyTrashedById($modelId)->restore();
    }

    /**
     * Permanently delete model by id
     * 
     * @param int $modelId
     * @return bool
     */
    public function permanentlyDeleteById(int $modelId): bool
    {
        return $this->findTrashedById($modelId)->forceDelete();
    }


    /**
     * Handle the Model "beforeCreate" event.
     *
     * @return array
     */
    public function beforeCreate(array &$payload): void
    {
    }
    /**
     * Handle the Model "created" event.
     *
     * @return void
     */
    public function created(): void
    {
    }


     /**
     * Handle the Model "beforeUpdate" event.
     *
     * @return array
     */
    public function beforeUpdate(array &$payload): void
    {
    }
    /**
     * Handle the Model "updated" event.
     *
     * @return void
     */
    public function updated(): void
    {
    }

    /**
     * Handle the Model "deleted" event.
     *
     * @return void
     */
    public function deleted(): void
    {
        //
    }

    /**
     * Handle the Model "forceDeleted" event.
     *
     * @return void
     */
    public function forceDeleted(): void
    {
        //
    }
}

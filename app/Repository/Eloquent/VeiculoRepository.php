<?php

namespace App\Repository\Eloquent;

use App\Mail\SendMailAfterCreateProd;
use App\Models\Veiculo;
use App\Repository\VeiculoRepositoryInterface;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class VeiculoRepository extends BaseRepository implements VeiculoRepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository construct
     * 
     * @param Veiculo $model
     */
    public function __construct(Veiculo $model)
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
        return [
            'placa' => [Rule::unique('veiculos', 'placa'), 'required', 'placa'],
            'modelo' => 'required|max:45',
            'cor' => 'max:15',
            'tipo' => Rule::in(['carro', 'moto']),
            'user_id' => 'exists:App\Models\User,id|integer',
        ];
    }

    /**
     * Get rules for validation model in Update
     * 
     * @return array $rules
     */
    public function getRulesUpdate(): array
    {
        return [
            'placa' => [Rule::unique('veiculos', 'placa')->ignore($this->model->id), 'required', 'placa'],
            'modelo' => 'required|max:45',
            'cor' => 'max:15',
            'tipo' => Rule::in(['carro', 'moto']),
            'user_id' => 'exists:App\Models\User,id|integer',
        ];
    }

      /**
     * Handle the Model "beforeCreate" event.
     *
     * @return array
     */
    public function beforeCreate(array &$payload): void
    {
        $payload['user_id'] = 1;
    }
      /**
     * Handle the Model "beforeUpdate" event.
     *
     * @return array
     */
    public function beforeUpdate(array &$payload): void
    {
        $payload['user_id'] = 1;
    }
    /**
     * Handle the Model "created" event.
     *
     * @return void
     */
    public function created(): void
    {
    }

}
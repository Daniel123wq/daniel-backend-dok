<?php

namespace App\Repository\Eloquent;

use App\Models\User;
use App\Repository\UsuarioRepositoryInterface;
use Illuminate\Validation\Rule;

class UsuarioRepository extends BaseRepository implements UsuarioRepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository construct
     * 
     * @param User $model
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * Antes da criação encripta a senha com uma criptografia unidirecional
     * 
     * @param array $payload
     * @return void
     */
    public function beforeCreate(array &$payload): void
    {
        $payload['password'] = bcrypt($payload['password']);
    }

    /**
     * Get rules for validation model in create
     * 
     * @return array $rules
     */
    public function getRulesCreate(): array
    {
        return [
            'email' => 'unique:users|email',
            'nome' => 'max:100'
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
            'email' => [Rule::unique('users', 'email')->ignore($this->model->id), 'email'],
            'nome' => 'max:100'
        ];
    }
}
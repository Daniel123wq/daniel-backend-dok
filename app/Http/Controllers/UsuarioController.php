<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Repository\UsuarioRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;

class UsuarioController extends Controller
{
    private $usuarioRepository;

    public function __construct(UsuarioRepositoryInterface $usuarioRepository)
    {
        $this->usuarioRepository = $usuarioRepository;
    }

    public function index () 
    {
        return response()->json($this->usuarioRepository->all(
            request()->input('columns', ['*']),
            request()->input('relations', []),
            request()->input('per-page', 25),
            request()->input('page') ? true : false,
            request()->all()
        ), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // {
    //     return response()->json($this->usuarioRepository->create($request->all()), 200);
    // }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json($this->usuarioRepository->findById(
            $id,    
            request()->input('columns', ['*']),
            request()->input('relations', []),
        ), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return bool
     */
    public function update(Request $request, $id)
    {
        $response = false;
        $requestAll = $request->all();
        if (!$response = $this->usuarioRepository->validateOnUpdate($id, $requestAll)) {
            $response = $this->usuarioRepository->update($id, $requestAll);
        }
        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $ids = explode(",",$id);
        $allDeleted = true;
        if(!$ids) $allDeleted = false;
        foreach ($ids as  $id) 
        {
            if ((int)$id > 0)
            {
                // \JWTAuth::invalidate(\JWTAuth::fromUser(User::find($id)));

                if(!$s = $this->usuarioRepository->deleteById($id))
                {
                    $allDeleted = $s;
                }
            }
        }
        return response()->json($allDeleted, 200);
    }
}

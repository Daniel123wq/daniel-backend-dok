<?php

namespace App\Http\Controllers;

use App\Repository\VeiculoRepositoryInterface;
use Illuminate\Http\Request;

class VeiculoController extends Controller
{
    protected $veiculoRepository;

    public function __construct(VeiculoRepositoryInterface $veiculoRepository)
    {
        $this->veiculoRepository = $veiculoRepository;
    }

    public function index () 
    {
        return response()->json($this->veiculoRepository->all(
            request()->input('columns', ['*']),
            request()->input('relations', []),
            request()->input('per-page', 25),
            request()->input('page') ? true : false,
        ), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return response()->json($this->veiculoRepository->create($request->all()), 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json($this->veiculoRepository->findById(
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
        return response()->json($this->veiculoRepository->update($id, $request->all()), 200);
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
                if(!$s = $this->veiculoRepository->deleteById($id))
                {
                    $allDeleted = $s;
                }
            }
        }
        return response()->json($allDeleted, 200);
    }
}

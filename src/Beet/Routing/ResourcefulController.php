<?php

namespace Gregoriohc\Beet\Routing;

use Illuminate\Routing\Controller as Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

abstract class ResourcefulController extends Controller
{
    use Resourceful;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function index()
    {
        return $this->service->model()->all();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Model
     */
    public function show($id)
    {
        return $this->service->get($id);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Model
     */
    public function create()
    {
        return $this->service->model();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Model
     */
    public function store(Request $request)
    {
        return $this->service->create($request->all());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Model
     */
    public function edit($id)
    {
        return $this->service->get($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return Model
     */
    public function update(Request $request, $id)
    {
        return $this->service->update($id, $request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Model
     */
    public function destroy($id)
    {
        return $this->service->delete($id);
    }
}

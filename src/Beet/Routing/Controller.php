<?php

namespace Gregoriohc\Beet\Routing;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $module = 'module';

    protected $resource = 'resource';

    protected $model = false;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $resource = substr(class_basename(get_class($this)), 0, -10);
        $this->resource = strtolower($resource);

        $model = 'App\\Models\\' . $resource;
        if (class_exists($model)) {
            $this->model = $model;
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [
            'resource' => $this->resource,
            'modelData' => false,
        ];

        //$data = $this->getRouter()->getRoutes();

        if ($this->model) {
            $modelData = call_user_func_array([$this->model, 'all'], []);
            $data['modelData'] = $modelData->toArray();
        }

        return view($this->module . '.' . $this->resource . '.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (false === $this->model) {
            throw new NotFoundHttpException();
        }

        return view($this->module . '.' . $this->resource . '.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (false === $this->model) {
            throw new NotFoundHttpException();
        }

        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (false === $this->model) {
            throw new NotFoundHttpException();
        }

        return view($this->module . '.' . $this->resource . '.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (false === $this->model) {
            throw new NotFoundHttpException();
        }

        return view($this->module . '.' . $this->resource . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (false === $this->model) {
            throw new NotFoundHttpException();
        }

        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (false === $this->model) {
            throw new NotFoundHttpException();
        }

        //
    }
}

<?php

namespace Gregoriohc\Beet\Routing;

use Carbon\Carbon;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Database\Eloquent\Model;
use View;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @var string $module
     */
    protected $module = 'module';

    /**
     * @var string $resource
     */
    protected $resource = 'resource';

    /**
     * @var Model $model
     */
    protected $model = null;

    /**
     * @var array $model
     */
    protected $modelInfo = null;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $resource = substr(class_basename(get_class($this)), 0, -10);
        $this->resource = strtolower($resource);

        $model = 'App\\Models\\' . $resource;
        if (class_exists($model)) {
            $this->model = new $model;

            $objectModelClass = 'App\\Models\\Object';
            /** @var Model $objectModel */
            $objectModel = new $objectModelClass;
            $modelInfoObject = $objectModel->where('name', '=',$this->resource)->first();
            if ($modelInfoObject) {
                $this->modelInfo = $modelInfoObject->options;
            }
        }

        View::share('module', $this->module);
        View::share('resource', $this->resource);
        View::share('model', $this->model);
        View::share('modelInfo', $this->modelInfo);
    }

    /**
     * Returns an action view
     *
     * @param string $action
     * @param array $data
     * @return View
     */
    private function view($action, $data = []) {
        $view = $this->module . '.' . $this->resource . '.' . $action;
        if (!View::exists($view)) $view = 'beet::' . $this->module . '.base.' . $action;

        return view($view, $data);
    }

    /**
     * Returns a route name from a given action name
     *
     * @param string $action
     * @return View
     */
    private function route($action) {
        return $this->module . '.' . $this->resource . '.' . $action;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $modelData = [];

        if ($this->model instanceof Model) {
            $collection = $this->model->all();
            foreach ($collection as $r => $rowO) {
                $row = $rowO->toArray();
                foreach ($row as $c => $column) {
                    $columnTitle = $c;
                    if ('id' == $columnTitle) {
                    } elseif (in_array($columnTitle, ['created_at', 'updated_at', 'deleted_at'])) {
                        $columnTitle = trans('model.common.column.'.$c.'.title');
                    } else {
                        $columnTitle = trans('model.'.$this->resource.'.column.'.$c.'.title');
                    }
                    if (is_array($column)) $column = '<pre><small>'.print_r($column, true).'</small></pre>';
                    if ($rowO->$c instanceof Carbon) $column = $rowO->$c->formatLocalized('%x %X');
                    $modelData[$r][$columnTitle] = $column;
                }
            }
        }

        return $this->view(__FUNCTION__, ['modelData' => $modelData]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $viewData = [];

        if (is_null($this->model)) {
            throw new NotFoundHttpException();
        }

        return $this->view(__FUNCTION__, $viewData);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!($this->model instanceof Model)) {
            throw new NotFoundHttpException();
        } else {
            $model = $this->model;
            if (!$modelData = $model) {
                throw new NotFoundHttpException();
            }
        }

        return $this->view(__FUNCTION__, ['modelData' => $modelData]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!($this->model instanceof Model)) {
            throw new NotFoundHttpException();
        } else {
            $model = $this->model;
        }

        /** @var Model $modelData */
        $modelData = $model::create($request->all());

        $status = trans('model.'.$this->resource.'.name') . ' #' . $modelData->id . ' ' . trans('model.common.created') . '!';
        return redirect()->route($this->route('index'))->with('status', $status);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!($this->model instanceof Model)) {
            throw new NotFoundHttpException();
        } else {
            $model = $this->model;
            if (!$modelData = $model::find($id)) {
                throw new NotFoundHttpException();
            }
        }

        return $this->view(__FUNCTION__, ['modelData' => $modelData]);
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
        if (!($this->model instanceof Model)) {
            throw new NotFoundHttpException();
        } else {
            $model = $this->model;
            if (!$modelData = $model::find($id)) {
                throw new NotFoundHttpException();
            }
        }

        /** @var Model $modelData */
        $modelData->update($request->all());

        $status = trans('model.'.$this->resource.'.name') . ' #' . $modelData->id . ' ' . trans('model.common.updated') . '!';
        return redirect()->route($this->route('index'))->with('status', $status);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!($this->model instanceof Model)) {
            throw new NotFoundHttpException();
        } else {
            $model = $this->model;
            if (!$modelData = $model::find($id)) {
                throw new NotFoundHttpException();
            }
        }

        /** @var Model $modelData */
        $modelData->delete();

        $status = trans('model.'.$this->resource.'.name') . ' #' . $id . ' ' . trans('model.common.deleted') . '!';
        return redirect()->route($this->route('index'))->with('status', $status);
    }
}

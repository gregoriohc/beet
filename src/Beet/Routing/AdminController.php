<?php

namespace Gregoriohc\Beet\Routing;

use App\Models\Object;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Carbon\Carbon;
use View;

abstract class AdminController extends ResourcefulController
{
    /**
     * Create a new web controller instance.
     */
    public function __construct()
    {
        $this->bootResourceful(self::class);

        $this->middleware('auth');

        $objects = Object::all();
        $appObjects = new Collection();
        /** @var Object $object */
        foreach ($objects as $object) {
            if (!(count($object->inverseRelationships) && 'Object' != $object->name)) {
                $appObjects->add($object);
            }
        }

        View::share('appObjects', $appObjects);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $modelData = parent::index();

        $args = func_get_args();

        $tableData = [];
        $actions = [];

        $actions['create'] = route($this->routeName('create'), $args);

        foreach ($modelData as $r => $rowObject) {
            $row = $rowObject->toArray();
            foreach ($row as $c => $column) {
                $columnTitle = $c;
                if ('id' == $columnTitle) {
                } elseif (in_array($columnTitle, ['created_at', 'updated_at', 'deleted_at'])) {
                    $columnTitle = trans('model.common.column.'.$c.'.title');
                } else {
                    $columnTitle = trans('model.'.snake_case($this->resource).'.column.'.$c.'.title');
                }
                if (false !== stristr(substr($c, -3), '_id') && is_numeric($column)) {
                    $cO = substr($c, 0, -3);
                    $method = camel_case($cO);
                    if (method_exists($rowObject, $method)) {
                        $column = $rowObject->$method->name;
                    }
                }
                if (is_array($column)) $column = '<pre><small>'.print_r($column, true).'</small></pre>';
                if ($rowObject->$c instanceof Carbon) $column = $rowObject->$c->formatLocalized('%x %X');
                $tableData[$r][$columnTitle] = $column;
            }
            $rowActions = [];
            if ($this->service->modelInfo() && $this->service->modelInfo()->relationships) {
                foreach ($this->service->modelInfo()->relationships as $relationship) {
                    if ('has_many' == $relationship->type) {
                        $name = $relationship->foreign->name;
                        $rowActions[snake_case_plural($name)] = [
                            'method' => 'get',
                            'url' => route('admin.'.snake_case($name, '.').'.index', array_merge($args, [$rowObject->id])),
                            'title' => trans('model.'.snake_case($name).'.plural'),
                            'icon' => 'list',
                        ];
                    }
                }
            }
            $rowActions['edit'] = [
                'method' => 'get',
                'url' => route($this->routeName('edit'), array_merge($args, [$rowObject->id])),
                'title' => trans('model.common.edit'),
                'icon' => 'edit',
            ];
            $rowActions['destroy'] = [
                'method' => 'delete',
                'url' => route($this->routeName('destroy'), array_merge($args, [$rowObject->id])),
                'title' => trans('model.common.delete'),
                'icon' => 'times',
                'class' => 'danger',
            ];
            $tableData[$r][trans('model.common.column.actions.title')] = $rowActions;
        }

        return $this->view(__FUNCTION__, ['modelData' => $modelData, 'tableData' => $tableData, 'actions' => $actions]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $modelData = parent::show($id);

        return $this->view(__FUNCTION__, ['modelData' => $modelData]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $modelData = parent::create();

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
        $modelData = parent::store($request);

        $status = trans('model.'.snake_case($this->resource).'.name') . ' #' . $modelData->id . ' ' . trans('model.common.created') . '!';
        return redirect()->route($this->routeName('index'))->with('status', $status);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $modelData = parent::edit($id);

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
        $modelData = parent::update($request, $id);

        $status = trans('model.'.snake_case($this->resource).'.name') . ' #' . $modelData->id . ' ' . trans('model.common.updated') . '!';
        return redirect()->route($this->routeName('index'))->with('status', $status);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        parent::destroy($id);

        $status = trans('model.'.snake_case($this->resource).'.name') . ' #' . $id . ' ' . trans('model.common.deleted') . '!';

        return redirect()->route($this->routeName('index'))->with('status', $status);
    }
}
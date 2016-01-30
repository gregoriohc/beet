@extends('beet::layouts.app')

@section('htmlheader_title')
	{{ trans('model.'.$resource.'.plural') }} {{ trans('model.common.list') }}
@endsection

@section('contentheader_title')
	{{ trans('model.'.$resource.'.plural') }} {{ trans('model.common.list') }}
@endsection

<?php
function actions($resource, $id, $model) {
	$actions = [];
	/** @var \Gregoriohc\Beet\Database\Eloquent\Model $model */
    /*
	foreach ($model->hasMany as $relation) {
        $actions[] = Html::link(route('admin.'.$resource.'.edit', $id), Html::iconFa('list'), ['class' => 'btn btn-sm btn-primary', 'title' => 'model.'.$relation.'.plural']);
	}
    */
    $actions[] = Html::link(route('admin.'.$resource.'.edit', $id), Html::iconFa('edit'), ['class' => 'btn btn-sm btn-primary']);
    $actions[] = Form::open(['route' => ['admin.'.$resource.'.destroy', $id], 'method' => 'delete', 'style' => 'display:inline;'])
                    .Button::danger(Html::iconFa('times'))->small()->submit()
                    .Form::close();
	return implode(' ', $actions);
}
?>

@section('main-content')
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="pull-right">
				{!! Button::primary(Html::iconFa('plus').' '.trans('model.common.create'))->asLinkTo(route('admin.'.$resource.'.create')) !!}
			</div>
			@if($modelData)
			{!!
				Table::withContents($modelData)
					->striped()->hover()
			 		->callback(trans('model.common.column.actions.title'), function ($field, $row) use ($model, $resource) { return actions($resource, $row['id'], $model); })
			 !!}
			@else
			{{ trans('model.common.no_results', ['name' => trans('model.'.$resource.'.plural')]) }}
			@endif
		</div>
	</div>
@endsection
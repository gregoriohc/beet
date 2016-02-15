@extends('beet::layouts.app')

@section('htmlheader_title')
	{{ trans('model.'.snake_case($resource).'.plural') }} {{ trans('model.common.list') }}
@endsection

@section('contentheader_title')
	{{ trans('model.'.snake_case($resource).'.plural') }} {{ trans('model.common.list') }}
@endsection

@section('main-content')
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="pull-right">
				{!! Button::primary(Html::iconFa('plus').' '.trans('model.common.create'))->asLinkTo($actions['create']) !!}
			</div>
			@if($tableData)
			{!!
				Table::withContents($tableData)
					->striped()->hover()
			 		->callback(trans('model.common.column.actions.title'), function ($field, $row) { return Html::buttonsMethodGroup($field); })
			 !!}
			@else
			{{ trans('model.common.no_results', ['name' => trans('model.'.snake_case($resource).'.plural')]) }}
			@endif
		</div>
	</div>
@endsection
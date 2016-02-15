@extends('beet::layouts.app')

@section('htmlheader_title')
    {{ trans('model.'.$resource.'.name') }} {{ trans('model.common.edit') }}
@endsection

@section('contentheader_title')
    {{ trans('model.'.$resource.'.name') }} {{ trans('model.common.edit') }}
@endsection


@section('main-content')
    <div class="panel panel-default">
        <div class="panel-body">
            {!! Form::Model($modelData, ['route' => ['admin.'.$resource.'.update', $modelData->id], 'method' => 'put']) !!}
            @include('beet::admin.base.partials.model_form')
            <div class="pull-right">
                {!! Button::normal(trans('model.common.cancel'))->asLinkTo(route('admin.'.$resource.'.index')) !!}
                {!! Button::primary(trans('model.common.edit'))->submit() !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
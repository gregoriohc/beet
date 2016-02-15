@extends('beet::layouts.app')

@section('htmlheader_title')
    {{ trans('model.'.$resource.'.name') }} {{ trans('model.common.create') }}
@endsection

@section('contentheader_title')
    {{ trans('model.'.$resource.'.name') }} {{ trans('model.common.create') }}
@endsection


@section('main-content')
    <div class="panel panel-default">
        <div class="panel-body">
            {!! Form::Model($modelData, ['route' => ['admin.'.$resource.'.store'], 'method' => 'post']) !!}
            @include('beet::admin.base.partials.model_form')
            <div class="pull-right">
                {!! Button::normal(trans('model.common.cancel'))->asLinkTo(route('admin.'.$resource.'.index')) !!}
                {!! Button::primary(trans('model.common.create'))->submit() !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
@foreach($modelData->getFillable() as $field)
    {!! ControlGroup::generate(Form::label($field, trans('model.'.$resource.'.column.'.$field.'.title')), Form::text($field)) !!}
@endforeach

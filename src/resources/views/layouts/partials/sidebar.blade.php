{{ Html::open('aside', ['class' => 'main-sidebar']) }}
    {{ Html::open('section', ['class' => 'sidebar']) }}
        @if (! Auth::guest())
        {{ Html::open('div', ['class' => 'user-panel']) }}
            {{ Html::open('div', ['class' => 'pull-left image']) }}
                {{ Html::image(asset('/img/user2-160x160.jpg'), 'User Image', ['class' => 'img-circle']) }}
            {{ Html::close() }}
            {{ Html::open('div', ['class' => 'pull-left info']) }}
                {{ Html::tag('p', Auth::user()->name) }}
                {{ Html::link('#', Html::iconFa('circle text-success') . ' Online') }}
            {{ Html::close() }}
        {{ Html::close() }}
        @endif
        {!! Form::open(['method' => 'get', 'class' => 'sidebar-form']) !!}
            {{ Html::open('div', ['class' => 'input-group']) }}
                {{ Form::input('text', 'q', null, ['class' => 'form-control', 'placeholder' => 'Search...']) }}
                {{ Html::open('span', ['class' => 'input-group-btn']) }}
                    {{ Form::button(Html::iconFa('search'), ['type' => 'submit', 'name' => 'search', 'id' => 'search-btn', 'class' => "btn btn-flat"]) }}
                {{ Html::close() }}
            {{ Html::close() }}
        {!! Form::close() !!}

        <ul class="sidebar-menu">
            <li class="header">BUILDER</li>
            <!-- Optionally, you can add icons to the links -->
            <li><a href="{{ route('admin.app.index') }}"><i class='fa fa-link'></i> <span>Dashboard</span></a></li>
            <li class="header">OBJECTS</li>
            @foreach($appObjects as $row)
                <li{!! (Route::getCurrentRoute()->getName() == 'admin.'.snake_case($row->name).'.index' ? ' class="active"' : '') !!}><a href="{{ route('admin.'.snake_case($row->name).'.index') }}"><i class='fa fa-link'></i> <span>{{ trans('model.'.$row->name.'.plural') }}</span></a></li>
            @endforeach
        </ul><!-- /.sidebar-menu -->
    {{ Html::close() }}
{{ Html::close() }}

{{ Html::doctype('html') }}
{{ Html::open('html', ['lang' => 'en']) }}
    {!! Html::template('beet::layouts.partials.htmlheader') !!}
    {{ Html::open('body', ['class' => 'skin-blue sidebar-mini']) }}
        {{ Html::open('div', ['class' => 'wrapper']) }}
            {!! Html::template('beet::layouts.partials.mainheader') !!}
            {!! Html::template('beet::layouts.partials.sidebar') !!}
            {{ Html::open('div', ['class' => 'content-wrapper']) }}
                {!! Html::template('beet::layouts.partials.contentheader') !!}
                {{ Html::open('div', ['class' => 'content']) }}
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    {!! View::yieldContent('main-content') !!}
                {{ Html::close() }}
            {{ Html::close() }}
            {!! Html::template('beet::layouts.partials.controlsidebar') !!}
            {!! Html::template('beet::layouts.partials.footer') !!}
        {{ Html::close() }}
        {!! Html::template('beet::layouts.partials.scripts') !!}
    {{ Html::close() }}
{{ Html::close() }}

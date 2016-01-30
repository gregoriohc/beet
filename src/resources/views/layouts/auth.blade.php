{{ Html::doctype('html') }}
{{ Html::tag('html', [
    Html::template('beet::layouts.partials.htmlheader'),
    View::yieldContent('content'),
]) }}
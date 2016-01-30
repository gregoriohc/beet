{{ Html::tag('section', [
    Html::tag('h1', [
        View::yieldContent('contentheader_title', 'Page Header here'),
        Html::tag('small', View::yieldContent('contentheader_description')),
    ]),
    Html::tag('ol', [
        Html::tag('li', Html::link('#', Html::iconFa('dashboard') . ' Level')),
        Html::tag('li', 'Here', ['class' => 'active']),
    ], ['class' => 'breadcrumb']),
], ['class' => 'content-header']) }}
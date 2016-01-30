{{ Html::tag('footer', [
    Html::tag('div', [
        Html::text('A Laravel 5 web application and REST API builder!'),
    ], ['class' => 'pull-right hidden-xs']),
    Html::tag('strong', [
        Html::text('Copyright &copy; 2016 '),
        Html::link('https://github.com/gregoriohc', 'GHC'),
    ]),
    Html::text('. Created by '),
    Html::link('https://github.com/gregoriohc', 'Gregorio Hernández Caso'),
    Html::text('. See code at '),
    Html::link('https://github.com/gregoriohc/beet', 'Github'),
], ['class' => 'main-footer']) }}
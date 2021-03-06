<?php

namespace Gregoriohc\Beet\View\Html;

use Button;
use Collective\Html\HtmlBuilder as BaseHtmlBuilder;
use Form;

class HtmlBuilder extends BaseHtmlBuilder
{
    private $tagsOpened = [];

    /**
     * Include another template.
     *
     * @param string $template
     *
     * @return string
     */
    public function template($template)
    {
        return \View::make($template, array_except(get_defined_vars(), array('__data', '__path')))->render();
    }

    /**
     * Generate a HTML link.
     *
     * @param string $url
     * @param string $title
     * @param array  $attributes
     * @param bool   $secure
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function link($url, $title = null, $attributes = [], $secure = null)
    {
        $url = $this->url->to($url, [], $secure);

        if (is_null($title) || $title === false) {
            $title = $url;
        }

        return $this->toHtmlString('<a href="' . $url . '"' . $this->attributes($attributes) . '>' . $title . '</a>');
    }

    /**
     * Generate an html tag.
     *
     * @param string $tag
     * @param mixed $content
     * @param array  $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function tag($tag, $content, array $attributes = [])
    {
        $content = is_array($content) ? implode(PHP_EOL, $content) : '' . $content;
        return $this->toHtmlString(
            '<' . $tag . $this->attributes($attributes) . '>' .
            (!is_null($content) ? $this->toHtmlString($content) . '</' . $tag . '>' : '')
        );
    }

    /**
     * Generate an html tag start.
     *
     * @param string $tag
     * @param array  $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function open($tag, array $attributes = [])
    {
        $this->tagsOpened[] = $tag;
        return $this->toHtmlString('<' . $tag . $this->attributes($attributes) . '>');
    }

    /**
     * Generate an html tag end.
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function close()
    {
        return $this->toHtmlString('</' . array_pop($this->tagsOpened) . '>');
    }

    /**
     * Generate an html comment.
     *
     * @param string $condition
     * @param mixed $content
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function commentIf($condition, $content)
    {
        $content = is_array($content) ? implode(PHP_EOL, $content) : $content;
        return $this->toHtmlString(
            '<!--[if ' . $condition . ']>' .
            $content .
            '<![endif]-->'
        );
    }

    /**
     * Generate an html text.
     *
     * @param string $content
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function text($content)
    {
        return $this->toHtmlString($content);
    }

    /**
     * Generate an html fontawesome icon.
     *
     * @param string $icon
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function iconFa($icon)
    {
        return $this->toHtmlString('<i class="fa fa-' . $icon . '"></i>');
    }

    /**
     * Generate an html doctype.
     *
     * @param string $doctype
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function doctype($doctype)
    {
        return $this->toHtmlString('<!DOCTYPE ' . $doctype . '>');
    }

    /**
     * Generate request method button.
     *
     * @param array $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function buttonMethod($options)
    {
        if (!isset($options['class'])) $options['class'] = 'primary';
        if (!isset($options['title'])) $options['title'] = null;
        if (!isset($options['icon'])) $options['icon'] = 'cog';

        switch ($options['method']) {
            case 'get':
                $code = $this->link($options['url'], $this->iconFa($options['icon']), ['class' => 'btn btn-sm btn-'.$options['class'], 'title' => $options['title']]);
                break;
            default:
                $code = $this->toHtmlString(Form::open(['url' => $options['url'], 'method' => $options['method'], 'style' => 'display:inline;'])
                    .Button::setType('btn-'.$options['class'])->withValue($this->iconFa($options['icon']))->addAttributes(['title' => $options['title']])->small()->submit()
                    .Form::close());
                break;
        }

        return $code;
    }

    public function buttonsMethodGroup($buttonsOptions)
    {
        foreach ($buttonsOptions as $button => $options) {
            $buttonsOptions[$button] = $this->buttonMethod($options);
        }

        return $this->toHtmlString(implode(' ', $buttonsOptions));
    }
}
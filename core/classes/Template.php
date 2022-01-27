<?php

namespace Core\Classes;

use Exception;

class Template 
{
    /**
     * Инстанс шаблона.
     */
    private static $instance = null;

    /**
     * Делаем синглтон.
     */
    protected function __construct() { }
    protected function __clone() { }
    public function __wakeup() {
        throw new Exception("Template is singleton.");
    }

    /**
     * Получить инстанс приложения.
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Рендерит header.
     */
    public function header()
    {
        return view('inc.header');
    }

    /**
     * Рендерит footer.
     */    
    public function footer()
    {
        return view('inc.footer');
    }

    /**
     * Рендерит success.
     */
    public function success()
    {
        return view('inc.success');
    }

    /**
     * Рендерит errors.
     */
    public function errors()
    {
        return view('inc.errors');
    }

    /**
     * Рендерит styles.
     */
    public function styles()
    {
        $assets = app()->assets();
        $css = $assets['css'] ?? [];
        return view('inc.styles', compact('css'));
    }

    /**
     * Рендерит scripts.
     */
    public function scripts()
    {
        $assets = app()->assets();
        $js = $assets['js'] ?? [];
        return view('inc.scripts', compact('js'));
    }

    /**
     * Рендерит тэг <link>
     */
    public function linkTag(array|string $style)
    {
        $attributesIndex = 1;

        if (is_array($style) && !empty($style[$attributesIndex])) {
            if ( ! is_array($style[$attributesIndex])) {
                throw new Exception("Attributes must be array type.");
            }

            list($styleUrl, $styleAtts) = $style;

            return $this->buildLinkTag($styleUrl, $styleAtts);
        }

        return $this->buildLinkTag($style);
    }

    /**
     * Рендерит тэг <script>
     */
    public function scriptTag(array|string $script)
    {
        $attributesIndex = 1;

        if (is_array($script) && !empty($script[$attributesIndex])) {
            if ( ! is_array($script[$attributesIndex])) {
                throw new Exception("Attributes must be array type.");
            }

            list($scriptUrl, $scriptAtts) = $script;

            return $this->buildScriptTag($scriptUrl, $scriptAtts);
        }

        return $this->buildScriptTag($script);
    }

    private function buildLinkTag(string $url, array $attributes = [])
    {
        $attributes['rel'] ??= 'stylesheet';
        $attributes['type'] ??= 'text/css';

        return sprintf('<link href="%s" %s>', $url, $this->buildAttributesString($attributes));
    }

    public function buildScriptTag(string $url, array $attributes = [])
    {
        $attributes['type'] ??= 'text/javascript';

        return sprintf('<script src="%s" %s></script>', $url, $this->buildAttributesString($attributes));
    }

    private function buildAttributesString(array $attributes)
    {
        return implode(' ', array_map(fn($att, $key) => sprintf('%s="%s"', $key, $att), $attributes, array_keys($attributes)));
    }
}
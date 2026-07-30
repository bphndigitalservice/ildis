<?php

namespace common\components;

use yii\helpers\HtmlPurifier;

/**
 * Sanitizes user-authored HTML (e.g. Summernote berita body) against stored XSS.
 */
class HtmlSanitizer
{
    /**
     * Allowlist suitable for Summernote-authored berita content.
     * Scripts, event handlers, and javascript: URIs are stripped by HtmlPurifier.
     *
     * @param string|null $html
     * @return string
     */
    public static function purify($html)
    {
        if ($html === null || $html === '') {
            return '';
        }

        return HtmlPurifier::process($html, function ($config) {
            $config->set(
                'HTML.Allowed',
                'p,br,b,strong,i,em,u,s,ul,ol,li,'
                . 'a[href|title|target|rel],'
                . 'h1,h2,h3,h4,h5,h6,blockquote,pre,code,'
                . 'span[style],div[style],'
                . 'img[src|alt|title|width|height],'
                . 'table,thead,tbody,tr,th[colspan|rowspan],td[colspan|rowspan],hr'
            );
            $config->set('Attr.AllowedFrameTargets', ['_blank']);
            $config->set('HTML.Nofollow', true);
            $config->set('URI.AllowedSchemes', [
                'http' => true,
                'https' => true,
                'mailto' => true,
                'data' => true,
            ]);
            $config->set('CSS.AllowedProperties', [
                'text-align',
                'font-weight',
                'font-style',
                'text-decoration',
                'color',
                'background-color',
            ]);
        });
    }
}

<?php

namespace common\tests\unit\components;

use Codeception\Test\Unit;
use common\components\HtmlSanitizer;

class HtmlSanitizerTest extends Unit
{
    public function testStripsScriptTags(): void
    {
        $dirty = 'Hello <script>alert("xss")</script> world';
        $clean = HtmlSanitizer::purify($dirty);

        $this->assertStringNotContainsString('<script', strtolower($clean));
        $this->assertStringNotContainsString('alert', $clean);
        $this->assertStringContainsString('Hello', $clean);
        $this->assertStringContainsString('world', $clean);
    }

    public function testStripsImgOnerrorPayload(): void
    {
        $dirty = '<img src=x onerror=alert("xss-poc")>';
        $clean = HtmlSanitizer::purify($dirty);

        $this->assertStringNotContainsString('onerror', strtolower($clean));
        $this->assertStringNotContainsString('alert', $clean);
    }

    public function testStripsJavascriptUri(): void
    {
        $dirty = '<a href="javascript:alert(1)">click</a>';
        $clean = HtmlSanitizer::purify($dirty);

        $this->assertStringNotContainsString('javascript:', strtolower($clean));
    }

    public function testAllowsSafeFormatting(): void
    {
        $html = '<p>Berita <strong>penting</strong></p><ul><li>satu</li></ul>';
        $clean = HtmlSanitizer::purify($html);

        $this->assertStringContainsString('<p>', $clean);
        $this->assertStringContainsString('<strong>', $clean);
        $this->assertStringContainsString('<ul>', $clean);
        $this->assertStringContainsString('<li>', $clean);
    }

    public function testNullAndEmptyReturnEmptyString(): void
    {
        $this->assertSame('', HtmlSanitizer::purify(null));
        $this->assertSame('', HtmlSanitizer::purify(''));
    }
}

<?php
/**
 * AssetBundle.php
 * @author Revin Roman
 * @link https://rmrevin.ru
 */

namespace backend\assets_b;

use yii\web\AssetBundle;

/**
 * Class AssetBundle
 * @package rmrevin\yii\fontawesome
 */
class FontawesomeAsset extends AssetBundle
{

    /**
     * @inherit
     */
    public $sourcePath = '@vendor/bower/fontawesome';

    /**
     * @inherit
     */
    public $css = [
        'css/font-awesome.min.css',
    ];

    /**
     * Initializes the bundle.
     * Set publish options to copy only necessary files (in this case css and font folders)
     * @codeCoverageIgnore
     */
    public function init()
    {
        parent::init();

        $this->publishOptions['beforeCopy'] = function ($from, $to) {
            return preg_match('%(/|\\\\)(fonts|css)%', $from);
        };
    }
}
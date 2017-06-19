<?php

/**
 * @copyright Copyright &copy; Gogodigital Srls
 * @company Gogodigital Srls - Wide ICT Solutions
 * @website http://www.gogodigital.it
 * @github https://github.com/cinghie/yii2-traits
 * @license GNU GENERAL PUBLIC LICENSE VERSION 3
 * @package yii2-traits
 * @version 0.1.0
 */

namespace cinghie\traits;

use Yii;

/**
 * Trait ImageTrait
 *
 * @property string $title
 */
trait ImageTrait
{

    /**
     * Get Allowed images
     *
     * @return array
     */
    public function getImagesAllowed()
    {
        return Yii::$app->controller->module->imageType;
    }

    /**
     * Get Allowed images in Accept Format
     *
     * @return array
     */
    public function getImagesAccept()
    {
        $imageAccept = [];
        $imagesAllowed = $this->getImagesAllowed();

        foreach ($imagesAllowed as $imageAllowed)
        {
            $imageAccept[] = 'image/'.$imageAllowed;
        }

        return $imageAccept;
    }

}
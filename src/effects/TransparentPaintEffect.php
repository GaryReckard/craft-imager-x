<?php
/**
 * Imager X plugin for Craft CMS
 *
 * Ninja powered image transforms.
 *
 * @link      https://www.spacecat.ninja
 * @copyright Copyright (c) 2020 André Elvan
 */

namespace spacecatninja\imagerx\effects;

use spacecatninja\imagerx\services\ImagerService;
use Imagine\Gd\Image as GdImage;
use Imagine\Imagick\Image as ImagickImage;
use Imagine\Imagick\Imagick;

class TransparentPaintEffect implements ImagerEffectsInterface
{

    /**
     * @param GdImage|ImagickImage $imageInstance
     * @param array                $params
     */
    public static function apply($imageInstance, $params)
    {
        if (ImagerService::$imageDriver === 'imagick') {
            /** @var ImagickImage $imageInstance */
            $imagickInstance = $imageInstance->getImagick();

            if (\is_array($params) && \count($params) === 3) {
                try {
                    $searchColor = $params[0];

                    if ($searchColor === 'auto') {
                        $searchColor = $imagickInstance->getImagePixelColor(0, 0);
                    }

                    $imagickInstance->transparentPaintImage($searchColor, $params[1], Imagick::getQuantum() * $params[2], false);
                } catch (\Throwable $e) {
                    \Craft::error('An error occured when trying to apply transparentpaint effect: '.$e->getMessage(), __METHOD__);
                }
            } else {
                \Craft::error('An incorrect number of parameters were passed to transparentpaint effect.', __METHOD__);
            }
        }
    }
}

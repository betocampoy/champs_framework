<?php

namespace BetoCampoy\ChampsFramework;

use CoffeeCode\Cropper\Cropper;

/**
 * Class Thumb
 *
 * @package BetoCampoy\ChampsFramework
 */
class Thumb
{
    /** @var Cropper */
    private $cropper;

    /** @var string */
    private $uploads;

    /**
     * Thumb constructor.
     */
    public function __construct()
    {
        $this->cropper = new Cropper(CHAMPS_IMAGE_CACHE, CHAMPS_IMAGE_QUALITY['jpg'], CHAMPS_IMAGE_QUALITY['png']);
        $this->uploads = CHAMPS_UPLOAD_DIR;
    }

    /**
     * @param string $image
     * @param int $width
     * @param int|null $height
     * @return string
     */
    public function make(string $image, int $width, ?int $height = null): string
    {
        return $this->cropper->make("{$this->uploads}/{$image}", $width, $height);
    }

    /**
     * @param string|null $image
     */
    public function flush(?string $image = null): void
    {
        if ($image) {
            $this->cropper->flush("{$this->uploads}/{$image}");
            return;
        }

        $this->cropper->flush();
        return;
    }

    /**
     * @return Cropper
     */
    public function cropper(): Cropper
    {
        return $this->cropper;
    }
}
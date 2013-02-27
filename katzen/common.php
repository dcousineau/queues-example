<?php
//Setup composer autoloader
require_once '../vendor/autoload.php';

//Connect to beanstalkd
$pheanstalk = new Pheanstalk_Pheanstalk('127.0.0.1');

/**
 * @see http://www.reddit.com/r/pics/comments/daat6/whole_lotta_boobs_d_de_another_comic/c0yqg1s
 *
 * @param Imagick $image
 * @return number
 */
function image_entropy(Imagick $image) {
    $hist = $image->getimagehistogram();

    $size = array_sum(array_map(function($p) { return $p->getcolorcount(); }, $hist));

    $hist_filt = [];
    foreach ($hist as $p) {
        $avg = (float)$p->getcolorcount() / $size;

        if ($avg == 0) continue;

        $hist_filt[] = $avg * log($avg, 2);
    }

    return array_sum($hist_filt);
}

/**
 * @see http://www.reddit.com/r/pics/comments/daat6/whole_lotta_boobs_d_de_another_comic/c0yqg1s
 *
 * @param Imagick $image
 * @return Imagick
 */
function square_image(Imagick $image) {
    $geo = $image->getimagegeometry();

    if ($geo['height'] == $geo['width']) return $image;

    $rotated = false;
    if ($geo['height'] < $geo['width']) {
        $image->rotateimage(new ImagickPixel('none'), 90);
        $rotated = true;
        $geo = $image->getimagegeometry();
    }

    while ($geo['height'] > $geo['width']) {
        $slice_height = min($geo['height'] - $geo['width'], 10);


        $top = clone $image;
        $top->cropImage($geo['width'], $slice_height, 0, 0);

        $bottom = clone $image;
        $bottom->cropimage($geo['width'], $slice_height, 0, $geo['height'] - $slice_height);


        if (image_entropy($bottom) < image_entropy($top)) {
            $image->cropImage($geo['width'], $geo['height'] - $slice_height, 0, 0);
        } else {
            $image->cropImage($geo['width'], $geo['height'] - $slice_height, 0, $slice_height);
        }

        $geo = $image->getimagegeometry();
    }

    if ($rotated) {
        $image->rotateimage(new ImagickPixel('none'), -90);
    }

    return $image;
}
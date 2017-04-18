<?php
use App\service\Image;

require_once 'init.php';

Image::resizeImage(800, 600, "image.jpg", true);
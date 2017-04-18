<?php
namespace App\service;
use Intervention\Image\ImageManagerStatic;

class Image {
	public static function resizeImage($width,$height,$path,$keep_ratio){
		try{
			ImageManagerStatic::configure(array('driver' => 'gd'));
			$image = ImageManagerStatic::make($path);
			$height_old = $image->getHeight();
			$width_old = $image->getWidth();
			
			$final_width = 0;
			$final_height = 0;
			if($width > 0 || $height > 0){
				if ($keep_ratio) {
					if ($width == 0)
						$factor = $height / $height_old;
					else if ($height == 0)
						$factor = $width / $width_old;
					else
						$factor = min ( $width / $width_old, $height / $height_old );
					$final_width = round ( $width_old * $factor );
					$final_height = round ( $height_old * $factor );
				} else {
					$final_width = ($width <= 0) ? $width_old : $width;
					$final_height = ($height <= 0) ? $height_old : $height;
				}
				if (is_file ( $path ))
					chmod ( $path, 0777 );
				$image->resize($final_width, $final_height)->encode("jpg", 75)->save($path);
			}
			return true;
		}catch (\Exception $e){
			return false;
		}
	}
}
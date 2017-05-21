<?php
use Intervention\Image\ImageManagerStatic as Image;
class Captcha {
	public static function getCaptcha(){
		try{
			$image = @imagecreatetruecolor(160, 60) or die("Cannot Initialize new GD image stream");
			if($image !== false){
				$background = imagecolorallocate($image, 0x66, 0xCC, 0xFF);
				imagefill($image, 0, 0, $background);
				
				$linecolor = imagecolorallocate($image, 0x33, 0x99, 0xCC);
				$textColors = array();
				$textColors[] = imagecolorallocate($image, 0, 0, 0);
				$textColors[] = imagecolorallocate($image, 245, 245, 245);
				$textColors[] = imagecolorallocate($image, 255, 0, 255);
				$textColors[] = imagecolorallocate($image, 255, 80, 10);
				
				for($i=0; $i < 10; $i++) {
					imagesetthickness($image, rand(1,3));
					imageline($image, rand(0,160), 0, rand(0,160), 60, $linecolor);
				}
				
				$fonts = array();
				$fonts[] = dirname(__DIR__)."/public/fonts/DejaVuSerif-Bold.ttf";
				$fonts[] = dirname(__DIR__)."/public/fonts/DejaVuSans-Bold.ttf";
				$fonts[] = dirname(__DIR__)."/public/fonts/DejaVuSansMono-Bold.ttf";
				
				$alphaNums = 'ABCDEFGHIJKLM12345nopqrstuvwxyzNOPQRSTUVWXYZabcdefghijklm67890';
				$len = strlen($alphaNums);
				$captcha = '';
				for($x = 10; $x <= 130; $x += 30) {
					$captcha .= ($digit = $alphaNums[rand(0, $len-1)]);
					imagettftext($image, 20, rand(-10,10), $x, rand(30, 52), $textColors[array_rand($textColors)], $fonts[array_rand($fonts)], $digit);
				}
				
				$_SESSION['captcha'] = $captcha;
				
				imagepng($image);
				imagedestroy($image);
			}
		}catch (\Throwable $t){
			FlashMessage::setMessage('Não foi possível carregar a imagem.', FlashType::ERROR, "/");
		}
	}
}
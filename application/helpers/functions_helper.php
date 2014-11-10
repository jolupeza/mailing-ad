<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	if (!function_exists('slug')) {
		function slug($string) {
			$characters = array(
				"Á" => "A", "Ç" => "c", "É" => "e", "Í" => "i", "Ñ" => "n", "Ó" => "o", "Ú" => "u",
				"á" => "a", "ç" => "c", "é" => "e", "í" => "i", "ñ" => "n", "ó" => "o", "ú" => "u",
				"à" => "a", "è" => "e", "ì" => "i", "ò" => "o", "ù" => "u"
			);
			$string = strtr($string, $characters);
			$string = strtolower(trim($string));
			$string = preg_replace("/[^a-z0-9-]/", "-", $string);
			$string = preg_replace("/-+/", "-", $string);
			if(substr($string, strlen($string) - 1, strlen($string)) === "-") {
				$string = substr($string, 0, strlen($string) - 1);
			}
			return $string;
		}
	}

	// Función para crear minuaturas o redimesiones de imágenes
	if (!function_exists('create_thumbnail')) {
		function create_thumbnail($filename, $width, $height, $crop = 0) {
			$CI =& get_instance();

			$config['image_library'] = 'gd2';
	        //CARPETA EN LA QUE ESTÁ LA IMAGEN A REDIMENSIONAR
	        $config['source_image'] = 'ad-content/uploads/'.$filename;
	        $config['create_thumb'] = TRUE;
	        $crop = ($crop == 0) ? TRUE : FALSE;
	        $config['maintain_ratio'] = $crop;
	        //CARPETA EN LA QUE GUARDAMOS LA MINIATURA
	        $config['new_image']='ad-content/thumbs/';
	        $config['thumb_marker'] = '-' . $width . 'x' . $height;
	        $config['width'] = $width;
	        $config['height'] = $height;
	        $CI->image_lib->initialize($config);
	        $CI->image_lib->resize();
		}
	}

	// Helper para eliminar carpeta que tenga contenido
	if (!function_exists('rrmdir')) {
		function rrmdir($dir) {
	   		if (is_dir($dir)) {
	     		$objects = scandir($dir);

	     		foreach ($objects as $object) {
	       			if ($object != "." && $object != "..") {
	         			if (filetype($dir . "/" . $object) == "dir") {
	         				rrmdir($dir . "/" . $object);
	         			} else {
	         				unlink($dir . "/" . $object);
	         			}
	       			}
	     		}

	     		reset($objects);
	     		rmdir($dir);
	   		}
		}
	}

	// Helper para generar código aleatorio
	if (!function_exists('randomString')) {
		function randomString($length = 3)
		{
			$base = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
			$max = strlen($base) - 1;
			$code = '';

			while (strlen($code) < $length) {
				$code .= $base{mt_rand(0, $max)};
			}

			return $code;
		}
	}

	if (!function_exists('format_date')) {
		function format_date($date) {
			$nameDay = array('Domingo', 'Lunes', 'Marte', 'Miercoles', 'Jueves', 'Viernes', 'Sábado');
			$nameMonth = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Setiembre', 'Octubre', 'Noviembre', 'Diciembre');

			$date = ($date != '' && !empty($date)) ? strtotime($date) : date('Y-m-d H:i:s');

			$day = $nameDay[date('w', $date)];
			$month = $nameMonth[date('n', $date) - 1];
			return 'Lima, ' . date('d', $date) . ' de ' . $month . ' del ' . date('Y', $date);
		}
	}

	if (!function_exists('elapsed_time')) {
		function elapsed_time($seconds) {
			$minutes = $seconds / 60;
			$hours = floor($minutes / 60);
			$days = $hours % 24;
			$minutes2 = $minutes % 60;
			$seconds_2 = $seconds % 60 % 60 % 60;
			if ($minutes2 < 10) $minutes2 = '0' . $minutes2;
			if ($seconds_2 < 10) $seconds_2 = '0' . $seconds_2;

			if ($seconds < 60) { /* seconds */
				$resultado = round($seconds) . ' segundos';
			} elseif ($seconds > 60 && $seconds < 3600) {/* Minutos  */
				$resultado = $minutes2 . ' minutos';
				//$resultado = $minutes2 . ' minutos' . $seconds_2 . ' segundos ';
			} elseif ($minutes > 60 && $minutes < 3600) {/* horas */
				$resultado = $hours . ' horas ' . $minutes2 . ' minutos ';
				// $resultado = $hours . ' horas ' . $minutes2 . ' minutos ' . $seconds_2 . ' segundos';
			} else {
				$resultado = $days . ' días ' . $hours . ' horas ' . $minutes2 . ' minutos ';
				//$resultado = $days . ' días ' . $hours . ' horas ' . $minutes2 . ' minutos ' . $seconds_2 . ' segundos';
			}

			return $resultado;
		}
	}

/* End of file functions_helper.php */
/* Location: ./application/helpers/functions_helper.php */ ?>
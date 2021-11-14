<?php

#Failinime loomine (see tähendab, et on vaja ka vastavat muutujat, mis peab põhiprogrammile väljastpoolt kättesaadav olema, põhiprogramm peab seda siis faili salvestamisel kasutada saama).

    class Photoupload {
        private $photo_to_upload;
        private $file_type;
        private $my_temp_image;
        private $my_new_image;
		public $max_size=1024*1024;
        private $photo_prefix="vp";
        public $file_name;
        public $err=False;
        
        function __construct($photo){
            $this->photo_to_upload = $photo;
            $this->file_type = $this->img_filetype($this->photo_to_upload["size"],$this->max_size);//hiljem teeb klass selle ise kindlaks
            $this->my_temp_image = $this->create_image_from_file($this->photo_to_upload["tmp_name"] ,$this->file_type);
        }
        
        function __destruct(){
            if(isset($this->my_temp_image)){
                imagedestroy($this->my_temp_image);
            }
        }

        public function make_fname()
        {
            $time_stamp = microtime(1) * 10000;
		    $file_name = $this->photo_prefix ."_". $time_stamp . '.' . $this->file_type;
            return $file_name;
        }
        
        private function create_image_from_file($file){
            $my_temp_image = null;
            //teen graafikaobjekti, image objekti
            if($this->file_type == "jpg"){
                $my_temp_image = imagecreatefromjpeg($file);
            }
            if($this->file_type == "png"){
                $my_temp_image = imagecreatefrompng($file);
            }
            if($this->file_type == "gif"){
                $my_temp_image = imagecreatefromgif($file);
            }
            
            return $my_temp_image;
        }
        
        public function resize_photo($w, $h, $keep_orig_proportion = true){
            $image_w = imagesx($this->my_temp_image);
            $image_h = imagesy($this->my_temp_image);
            $new_w = $w;
            $new_h = $h;
            $cut_x = 0;
            $cut_y = 0;
            $cut_size_w = $image_w;
            $cut_size_h = $image_h;
            
            if($w == $h){
                if($image_w > $image_h){
                    $cut_size_w = $image_h;
                    $cut_x = round(($image_w - $cut_size_w) / 2);
                } else {
                    $cut_size_h = $image_w;
                    $cut_y = round(($image_h - $cut_size_h) / 2);
                }	
            } elseif($keep_orig_proportion){//kui tuleb originaaproportsioone säilitada
                if($image_w / $w > $image_h / $h){
                    $new_h = round($image_h / ($image_w / $w));
                } else {
                    $new_w = round($image_w / ($image_h / $h));
                }
            } else { //kui on vaja kindlasti etteantud suurust, ehk pisut ka kärpida
                if($image_w / $w < $image_h / $h){
                    $cut_size_h = round($image_w / $w * $h);
                    $cut_y = round(($image_h - $cut_size_h) / 2);
                } else {
                    $cut_size_w = round($image_h / $h * $w);
                    $cut_x = round(($image_w - $cut_size_w) / 2);
                }
            }
                
            //loome uue ajutise pildiobjekti
            $this->my_new_image = imagecreatetruecolor($new_w, $new_h);
            //säilitame png piltide puhul läbipaistvuse
            imagesavealpha($this->my_new_image, true);
            $trans_color = imagecolorallocatealpha($this->my_new_image, 0, 0, 0, 127);
            imagefill($this->my_new_image, 0, 0, $trans_color);
            
            imagecopyresampled($this->my_new_image, $this->my_temp_image, 0, 0, $cut_x, $cut_y, $new_w, $new_h, $cut_size_w, $cut_size_h);
        }
        
        public function add_watermark($watermark_file){
            $watermark = imagecreatefrompng($watermark_file);
            $watermark_width = imagesx($watermark);
            $watermark_height = imagesy($watermark);
            $watermark_x = imagesx($this->my_new_image) - $watermark_width - 10;
            $watermark_y = imagesy($this->my_new_image) - $watermark_height - 10;
            imagecopy($this->my_new_image, $watermark, $watermark_x, $watermark_y, 0, 0, $watermark_width, $watermark_height);
            imagedestroy($watermark);
        }
        
        public function save_image($target){
            $notice = null;
            
            if($this->file_type == "jpg"){
                if(imagejpeg($this->my_new_image, $target, 90)){
                    $notice = "salvestamine õnnestus!";
                } else {
                    $notice = "salvestamisel tekkis tõrge!";
                    $this->err=True;
                }
            }
            
            if($this->file_type == "png"){
                if(imagepng($this->my_new_image, $target, 6)){
                    $notice = "salvestamine õnnestus!";
                } else {
                    $notice = "salvestamisel tekkis tõrge!";
                    $this->err=True;
                }
            }
            
            if($this->file_type == "gif"){
                if(imagegif($this->my_new_image, $target)){
                    $notice = "salvestamine õnnestus!";
                } else {
                    $notice = "salvestamisel tekkis tõrge!";
                    $this->err=True;
                }
            }
            imagedestroy($this->my_new_image);
            return $notice;
        }
		
		public function img_filetype($img_size, $size_lim)
		{
			$data_arr = getimagesize($this->photo_to_upload["tmp_name"]);
			# array(6)
			# [0]=> int(244) [1]=> int(232) [2]=> int(3)
			# [3]=> string(24) "width="244" height="232""
			# ["bits"]=> int(8) ["mime"]=> string(9) "image/png"
			if ($data_arr !== false) {
				if ($data_arr["mime"] == "image/jpeg") {
					$ftype = "jpg";
				} elseif ($data_arr["mime"] == "image/png") {
					$ftype = "png";
				} elseif ($data_arr["mime"] == "image/gif") {
					$ftype = "gif";
				} else {
					$picnotice = "Valitud pilt ei ole sobivas laiendis";
                    $this->err=True;
					return $picnotice;
				}
				if ($img_size > $size_lim) {
					$picnotice = "Valitud fail liiga suur (üle 1mb)";
                    $this->err=True;
					return $picnotice;
				}
			} else {
				$picnotice = "Pole pilt!";
                $this->err=True;
				return $picnotice;
			}
			return $ftype;
		}
        
    }//class lõppeb
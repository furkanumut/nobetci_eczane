<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
/**
 * Class 		Nöbetçi Eczane
 * @category	PHP Data Bot
 * @author		Furkan Umut Ceylan
 * @license 	https://www.gnu.org/licenses/gpl.txt  GPL License 3
 * @mail 		facfur3@gmail.com
 * @date 		23.10.2018
 */


class nobetci_eczane {

	public $il; //kullanıcıdan gelecek olan il
	private $veri; //siteden gelecek icerik

	public function il($il, $tur="json"){
		if ($il !=NULL) {
			$site="https://nobet.org/".$il."/nobetci-eczaneler.html";
			$ziyaret=self::Curl($site);

		//Filitreleme işlemleri
			$ezc_ad='@<strong >(.*?)</strong>@si';
			$ezc_adres='@</a></span>--><span(.*?)>(.*?)<br><a href="tel:@si';
			$ezc_ilce='@ki Nöbetçi Eczaneler" alt="(.*?) (.*?) Nöbetçi Eczaneler,@si';
			$ecz_tel='@<br><a href="tel:(.*?)" >@si';
			preg_match_all($ezc_ad, $ziyaret, $ezc_ad);
			preg_match_all($ezc_adres, $ziyaret, $ezc_adres); 
			preg_match_all($ezc_ilce, $ziyaret, $ezc_ilce); unset($ezc_ilce[0]); 
			preg_match_all($ecz_tel, $ziyaret, $ecz_tel); 

			for ($i=0; $i < count($ezc_adres[1]); $i++) { 
				$veri["$i"]=array(
					"ad" => $ezc_ad[1][$i],
					"adres" => $ezc_adres[2][$i],
					"ilce" => $ezc_ilce[1][$i],
					"telefon" => $ecz_tel[1][$i]
				);
			}

			if ($tur=="json") {
				return json_encode($veri,JSON_UNESCAPED_UNICODE);
			}
			elseif ($tur=="text") {
				$text="";
				foreach ($veri as $key => $value) {
					$text.= $key."->".$value['ad']."|||".$value['adres']."|||".$value['ilce']."|||".$value['telefon']." &&&\n ";
				}
				return $text;
			 } else{
				return $veri;
			}

		} else{
			echo "Şehir belirtilmedi...";
		}
	}

	private function Curl( $url, $proxy = NULL )
	{
		$options = array ( CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HEADER => false,
			CURLOPT_ENCODING => "",
			CURLOPT_AUTOREFERER => true,
			CURLOPT_CONNECTTIMEOUT => 30,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_SSL_VERIFYPEER => false
		);

		$ch = curl_init("$url");
		curl_setopt_array( $ch, $options );
		$content = curl_exec( $ch );
		$err = curl_errno( $ch );
		$errmsg = curl_error( $ch );
		$header = curl_getinfo( $ch );



		curl_close( $ch );

		$header[ 'errno' ] = $err;
		$header[ 'errmsg' ] = $errmsg;
		$header[ 'content' ] = $content;

		return str_replace( array ( "\n", "\r", "\t" ), NULL, $header[ 'content' ] );
	}
}

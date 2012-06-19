<?php 

$languages_array = array("af"=>"Afrikaans","sq"=>"Albanian","am"=>"Amharic","ar"=>"Arabic","hy"=>"Armenian","az"=>"Azerbaijani","bjs"=>"Bajan","rm"=>"Balkan Gipsy","eu"=>"Basque","bem"=>"Bemba","bn"=>"Bengali","be"=>"Bielarus","bi"=>"Bislama","bs"=>"Bosnian","br"=>"Breton","bg"=>"Bulgarian","my"=>"Burmese","ca"=>"Catalan","cb"=>"Cebuano","ch"=>"Chamorro","zh"=>"Chinese (Simplified)","zh"=>"Chinese Traditional","zdj"=>"Comorian (Ngazidja)","cop"=>"Coptic","aig"=>"English (Antigua and Barbuda)","bah"=>"English (Bahamas)","gcl"=>"English (Grenadian)","gyn"=>"English (Guyanese)","xx"=>"English (Jamaican)","svc"=>"English (Vincentian)","vic"=>"English (Virgin Islands)","ht"=>"French (Haitian)","acf"=>"French (Saint Lucian)","crs"=>"French (Seselwa)","pov"=>"Portuguese (Upper Guinea)","hr"=>"Croatian","cs"=>"Czech","da"=>"Danish","nl"=>"Dutch","dz"=>"Dzongkha","en"=>"English","eo"=>"Esperanto","et"=>"Estonian","fn"=>"Fanagalo","fo"=>"Faroese","fi"=>"Finnish","fr"=>"French","gl"=>"Galician","ka"=>"Georgian","de"=>"German","el"=>"Greek","XN"=>"Greek (Classical)","gu"=>"Gujarati","ha"=>"Hausa","XN"=>"Hawaiian","he"=>"Hebrew","hi"=>"Hindi","hu"=>"Hungarian","is"=>"Icelandic","id"=>"Indonesian","kl"=>"Inuktitut (Greenlandic)","ga"=>"Irish Gaelic","it"=>"Italian","ja"=>"Japanese","jw"=>"Javanese","kea"=>"Kabuverdianu","kab"=>"Kabylian","ka"=>"Kannada","kk"=>"Kazakh","km"=>"Khmer","rw"=>"Kinyarwanda","rn"=>"Kirundi","ko"=>"Korean","ku"=>"Kurdish","ku"=>"Kurdish Sorani","ky"=>"Kyrgyz","lo"=>"Lao","la"=>"Latin","lv"=>"Latvian","lt"=>"Lithuanian","lb"=>"Luxembourgish","mk"=>"Macedonian","mg"=>"Malagasy","ms"=>"Malay","dv"=>"Maldivian","mt"=>"Maltese","gv"=>"Manx Gaelic","mi"=>"Maori","mh"=>"Marshallese","men"=>"Mende","mn"=>"Mongolian","mfe"=>"Morisyen","ne"=>"Nepali","niu"=>"Niuean","no"=>"Norwegian","ny"=>"Nyanja","ur"=>"Pakistani","pau"=>"Palauan","pa"=>"Panjabi","pap"=>"Papiamentu","ps"=>"Pashto","fa"=>"Persian","pis"=>"Pijin","pl"=>"Polish","pt"=>"Portuguese","pot"=>"Potawatomi","qu"=>"Quechua","ro"=>"Romanian","ru"=>"Russian","sm"=>"Samoan","sg"=>"Sango","gd"=>"Scots Gaelic","sr"=>"Serbian","sn"=>"Shona","si"=>"Sinhala","sk"=>"Slovak","sl"=>"Slovenian","so"=>"Somali","st"=>"Sotho Southern","es"=>"Spanish","srn"=>"Sranan Tongo","sw"=>"Swahili","sv"=>"Swedish","de"=>"Swiss German","syc"=>"Syriac (Aramaic)","tl"=>"Tagalog","tg"=>"Tajik","tmh"=>"Tamashek (Tuareg)","ta"=>"Tamil","te"=>"Telugu","tet"=>"Tetum","th"=>"Thai","bo"=>"Tibetan","ti"=>"Tigrinya","tpi"=>"Tok Pisin","tkl"=>"Tokelauan","to"=>"Tongan","tn"=>"Tswana","tr"=>"Turkish","tk"=>"Turkmen","tvl"=>"Tuvaluan","uk"=>"Ukrainian","ppk"=>"Uma","uz"=>"Uzbek","vi"=>"Vietnamese","wls"=>"Wallisian","cy"=>"Welsh","wo"=>"Wolof","xh"=>"Xhosa","yi"=>"Yiddish","zu"=>"Zulu");

define(LANGUAGES, serialize($languages_array));

$api_lang_arr = array();

function tr_box_scripts() {
	wp_deregister_script( 'jquery' );
	wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js');
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'tr-box-request', plugin_dir_url( __FILE__ ) . 'js/api_ajax.js', array( 'jquery' ) );
	wp_localize_script( 'tr-box-request', 'tr_box_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php'),'security_check' => wp_create_nonce( 'tr_box_check' ), ) );

}    


function tr_box_translate ($atts)
	{

		extract(shortcode_atts( array(
		'languages' => 'default',
		'width' => '100%',
		'height' => '110px'
	), $atts ) );
		if ($languages=='default') {
			$languages = array_values(unserialize(LANGUAGES));
		}
		else {
		$languages = explode(',', $languages);
		foreach ($languages as $key => $value) {
			$value = ucfirst(trim($value));
			if (in_array(ucfirst($value), unserialize(LANGUAGES))) {
				$languages[$key] = $value;
			} else {unset($languages[$key]);}
		}
		}
// TODO move it to a js file
		// $trbox_nonce = wp_create_nonce('tr-box');
		// var_export($trbox_nonce);

		echo "<textarea id='text_to' style='width:{$width}; height:{$height};'></textarea>";
		$api_lang_arr = array_flip(unserialize(LANGUAGES));
		echo "<select id=\"from\">";
		foreach ($languages as $value) {
			echo	"<option value=\"{$api_lang_arr[$value]}\">$value</option>";	
		}
		echo "</select><a href='javascript:swap_langs()'>".__('To:')."</a>";
		echo "<select id=\"to\">";
		foreach ($languages as $key => $value) {
			echo	"<option value=\"{$api_lang_arr[$value]}\">$value</option>";	
		}
		echo "</select>";
		echo "&nbsp;&nbsp;&nbsp;<input type=\"submit\" id='translate' style='height:27px' value=\"".__('Translate')."\" onclick=\"get_translation($('#text_to').val(),$('#from').val(),$('#to').val(),'{$width}','{$height}');return false;\"><br>";
		echo base64_decode(get_option('trbox_link'));
	}
function tr_box_ajax_call(
){
	$nonce = $_POST['security_check'];

    // check to see if the submitted nonce matches with the
    // generated nonce we created earlier
   if ( ! wp_verify_nonce($nonce, 'tr_box_check' ))
   	{	die ( 'CSRF Check Failed !');	}
 
	$text = $_POST['text_to_translate'];
	$from = $_POST['from_language'];
	$to = $_POST['to_language'];
	$ch = curl_init();
	$text = urlencode($text);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	// curl_setopt($ch,CURLOPT_HTTPHEADER,array (
	//         "Content-Type: text/xml; charset=utf-8",
	//     ));
	curl_setopt($ch, CURLOPT_URL, "http://mymemory.translated.net/api/get?q={$text}&langpair={$from}|{$to}");
	$response = curl_exec($ch);
	header( "Content-Type: application/json" );
	echo $response;
	exit;
}

function translation_box_options (){
	add_options_page('Translation Box','Translation Box','manage_options', 'translation_box_options','translation_box_page');
	update_option('trbox_link', 'PGEgaHJlZj0iaHR0cDovL3d3dy50cmFuc2xhdG9yYm94LmNvbS8iIHRhcmdldD0iX2JsYW5rIiBzdHlsZT0icG9zaXRpb246cmVsYXRpdmU7bGVmdDo4MCUiPlRyYW5zbGF0b3Jib3g8L2E+');
}

function translation_box_page()
{	
  wp_enqueue_script( 'tr-box-request', plugin_dir_url( __FILE__ ) . 'js/api_ajax.js', array( 'jquery' ) );
  echo "
  <div class=\"wrap\" > 
  <?php screen_icon(); ?> 
  <h2>".__('Help Page of Translation Box')."</h2><br>
  <p class='description'>".__('Thanks to this plug with one easy shortcode you are able to transform every post or page into a translation area')."</p>

  <p class='description'>".__('The next shortcode is example of the simple usage of Translation Box:')."<br>
  		<p class='box-short-code'><strong>[translation_box languages=\"english,russian,german,spanish,french,chinese\"  width=\"100%\" height=\"200px\"]</strong></p>
  		<ol>
  		<li>".__('The shortcode is')." <strong>[translation_box]</strong>.".__(' If you use it by itself it will default to showing all the languages from the full list at the end of the section and also will have')." <strong>".__('width')."</strong> ".__('of 100% and')." <strong>".__('height')."</strong> ".__('of 110px').".</li>
  		<li>".__('The first attribute is called <strong>languages</strong> and is equal to the list of languages (for full list of supported langugages check the bottom section !) you would like to include in your translation box. Make sure you use comma for separation of different languages').".</li>
  		<li>".__('The second attribute is called <strong>width</strong> and it is used for setting up the width of the translation boxes. It can accept values in %, px, em, etc.')." .</li>
  		<li>".__('The third attribute is called <strong>height</strong> and it is used for setting up the width of the translation boxes. It can accept values in %, px, em, etc.')." .</li>
  		</ol>
  		<p>
  			<h3>".__('Full list of supported languages:')."</h3>
  			<table border='1' cellpadding='2'>
  				<tbody>
  					<tr>
						<td>Afrikaans</td>
						<td>Albanian</td>
						<td>Amharic</td>
						<td>Arabic</td>
						<td>Armenian</td>
						<td>Azerbaijani</td>
						<td>Bajan</td>
						<td>Balkan Gipsy</td>
						<td>Basque</td>
						<td>Bemba</td>
  					</tr>
  					<tr>
						<td>Bengali</td>
						<td>Bielarus</td>
						<td>Bislama</td>
						<td>Bosnian</td>
						<td>Breton</td>
						<td>Bulgarian</td>
						<td>Burmese</td>
						<td>Catalan</td>
						<td>Cebuano</td>
						<td>Chamorro</td>
  					</tr>
  					<tr>
						<td>Chinese (Simplified)</td>
						<td>Chinese (Traditional)</td>
						<td>Comorian (Ngazidja)</td>
						<td>Coptic</td>
						<td>English (Antigua and Barbuda)</td>
						<td>English (Bahamas)</td>
						<td>English (Grenadian)</td>
						<td>English (Guyanese)</td>
						<td>English (Jamaican)</td>
						<td>English (Vincentian)</td>
  					</tr>
  					<tr>
						<td>English (Virgin Islands)</td>
						<td>French (Haitian)</td>
						<td>French (Saint Lucian)</td>
						<td>French (Seselwa)</td>
						<td>Portuguese (Upper Guinea)</td>
						<td>Croatian</td>
						<td>Czech</td>
						<td>Danish</td>
						<td>Dutch</td>
						<td>Dzongkha</td>
  					</tr>
  					<tr>
						<td>English</td>
						<td>Esperanto</td>
						<td>Estonian</td>
						<td>Fanagalo</td>
						<td>Faroese</td>
						<td>Finnish</td>
						<td>French</td>
						<td>Galician</td>
						<td>Georgian</td>
						<td>German</td>
  					</tr>
  					<tr>
						<td>Greek</td>
						<td>Greek (Classical)</td>
						<td>Gujarati</td>
						<td>Hausa</td>
						<td>Hawaiian</td>
						<td>Hebrew</td>
						<td>Hindi</td>
						<td>Hungarian</td>
						<td>Icelandic</td>
						<td>Indonesian</td>
  					</tr>
  					<tr>
						<td>Inuktitut (Greenlandic)</td>
						<td>Irish Gaelic</td>
						<td>Italian</td>
						<td>Japanese</td>
						<td>Javanese</td>
						<td>Kabuverdianu</td>
						<td>Kabylian</td>
						<td>Kannada</td>
						<td>Kazakh</td>
						<td>Khmer</td>
  					</tr>
  					<tr>
						<td>Kinyarwanda</td>
						<td>Kirundi</td>
						<td>Korean</td>
						<td>Kurdish</td>
						<td>Kurdish Sorani</td>
						<td>Kyrgyz</td>
						<td>Lao</td>
						<td>Latin</td>
						<td>Latvian</td>
						<td>Lithuanian</td>
  					</tr>
  					<tr>
						<td>Luxembourgish</td>
						<td>Macedonian</td>
						<td>Malagasy</td>
						<td>Malay</td>
						<td>Maldivian</td>
						<td>Maltese</td>
						<td>Manx Gaelic</td>
						<td>Maori</td>
						<td>Marshallese</td>
						<td>Mende</td>
  					</tr>
  					<tr>
						<td>Mongolian</td>
						<td>Morisyen</td>
						<td>Nepali</td>
						<td>Niuean</td>
						<td>Norwegian</td>
						<td>Nyanja</td>
						<td>Pakistani</td>
						<td>Palauan</td>
						<td>Panjabi</td>
						<td>Papiamentu</td>
  					</tr>
  					<tr>
						<td>Pashto</td>
						<td>Persian</td>
						<td>Pijin</td>
						<td>Polish</td>
						<td>Portuguese</td>
						<td>Potawatomi</td>
						<td>Quechua</td>
						<td>Romanian</td>
						<td>Russian</td>
						<td>Samoan</td>
  					</tr>
  					<tr>
						<td>Sango</td>
						<td>Scots Gaelic</td>
						<td>Serbian</td>
						<td>Shona</td>
						<td>Sinhala</td>
						<td>Slovak</td>
						<td>Slovenian</td>
						<td>Somali</td>
						<td>Sotho Southern</td>
						<td>Spanish</td>
  					</tr>
  					<tr>
						<td>Sranan Tongo</td>
						<td>Swahili</td>
						<td>Swedish</td>
						<td>Syriac (Aramaic)</td>
						<td>Tagalog</td>
						<td>Tajik</td>
						<td>Tamashek (Tuareg)</td>
						<td>Tamil</td>
						<td>Telugu</td>
						<td>Tetum</td>
  					</tr>
  					<tr>
						<td><input type='checkbox' name='langs' value='$'>Thai</td>
						<td><input type='checkbox' name='langs' value='$'>Tibetan</td>
						<td><input type='checkbox' name='langs' value='$'>Tigrinya</td>
						<td><input type='checkbox' name='langs' value='$'>Tok Pisin</td>
						<td><input type='checkbox' name='langs' value='$'>Tokelauan</td>
						<td><input type='checkbox' name='langs' value='$'>Tongan</td>
						<td><input type='checkbox' name='langs' value='$'>Tswana</td>
						<td><input type='checkbox' name='langs' value='$'>Turkish</td>
						<td><input type='checkbox' name='langs' value='$'>Turkmen</td>
						<td><input type='checkbox' name='langs' value='$'>Tuvaluan</td>
  					</tr>
  					<tr>
						<td><input type='checkbox' name='langs' value='$'>Ukrainiazn</td>
						<td><input type='checkbox' name='langs' value='$'>Uma</td>
						<td><input type='checkbox' name='langs' value='$'>Uzbek</td>
						<td><input type='checkbox' name='langs' value='$'>Vietnamese</td>
						<td><input type='checkbox' name='langs' value='$'>Wallisian</td>
						<td><input type='checkbox' name='langs' value='$'>Welsh</td>
						<td><input type='checkbox' name='langs' value='$'>Wolof</td>
						<td><input type='checkbox' name='langs' value='$'>Xhosa</td>
						<td><input type='checkbox' checked='checked' name='langs' value='yd'>Yiddish</td>
						<td><input type='checkbox' checked='checked' name='langs' value='zl'>Zulu</td>
  					</tr> 
  				</tbody>
  			</table>
  		</p>
  </p>
  </div>
  <input type='submit' id='shortcode-generator' value='Generate Shortcode' onclick='generate_shortcode()'/><br><br>
  <textarea readonly='readonly'></textarea>
  <script type=\"text/javascript\" >
	  jQuery(document).ready(function() {
	  	var allLangs = new Array();
			jQuery('input:checkbox[name=langs]:checked').each(function()
				{
					allLangs.push(jQuery(this).val());
    				
    			});
				console.log(allLangs);
		});
	</script>";
}
<?php

namespace App\Controllers;

// require_once 'vendor/aws/vendor/autoload.php';
require_once 'vendor/autoload.php';

use Aws\Polly\PollyClient;
use Aws\Exception\AwsException;


class Home extends BaseController
{
	public function index()
	{
		return view('main');
	}
	public function test()
	{
	}
	public function process_text()
	{
		$request = \Config\Services::request();
		$text = $request->getPost('text');
		// echo $text;
		$config = [
			'version' => 'latest',
			'region' => 'us-east-1', //region
			'credentials' => [
				'key' => 'AKIAT4G5L7NIFHQ2RVHB',
				'secret' => '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',
			]
		];
		$client = new PollyClient($config);

		$args = [
			'OutputFormat' => 'mp3',
			'Text' => "<speak><prosody rate='medium'>$text</prosody></speak>",
			'TextType' => 'ssml',
			'VoiceId' => "Aditi",
		];



		$result = $client->synthesizeSpeech($args);
		$resultData = $result->get('AudioStream')->getContents();

		header('Content-length: ' . strlen($resultData));
		header('Content-Disposition: attachment; filename="polly-text-to-speech.mp3"');
		header('X-Pad: avoid browser bug');
		header('Cache-Control: no-cache');
		echo $resultData;


		// $size = strlen($resultData); // File size
		// $length = $size; // Content length
		// $start = 0; // Start byte
		// $end = $size - 1; // End byte
		// header('Content-Transfer-Encoding:chunked');
		// header("Content-Type: audio/mpeg");
		// header("Accept-Ranges: 0-$length");
		// header("Content-Range: bytes $start-$end/$size");
		// header("Content-Length: $length");
		// echo $resultData;
		return view('main');
	}
	public function process()
	{
		$request = \Config\Services::request();
		$text = $request->getPost('text');
		$polly = new PollyClient([
			'version' => 'latest',
			'region' => 'us-east-1', //region
			'credentials' => [
				'key' => 'AKIAT4G5L7NIFHQ2RVHB',
				'secret' => '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',
			]
		]);
		$text = $_POST['text'];

		$result = $polly->synthesizeSpeech([
			'OutputFormat' => 'mp3',
			'Text' => $text,
			'VoiceId' => "Aditi",
		]);

		$newdata =  $result->get("AudioStream");

		echo base64_encode($newdata);

		// $myfile = fopen("public/test.mp3", "w");
		// fwrite($myfile,$newdata);
		// fclose($myfile);
		// echo "public/test.mp3";


	}
	public function describe_voices()
	{
		$client = new PollyClient([
			'region' => 'us-east-1',
			'version' => 'latest',
			'credentials' => array(
				'key' => 'AKIAT4G5L7NIFHQ2RVHB',
				'secret'  => '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',
			  )
		]);
		
		$engines = ['standard','neural'];
		
		$result = $client->describeVoices([
			'Engine' => 'standard',
			'IncludeAdditionalLanguageCodes' => true || false,
			'LanguageCode' => 'en-US',
		]);
		
		$voices = json_encode($result['Voices']);
		echo $voices;
		


	}
	public function get_languages()
	{
		$languages = array('arb', 'cmn-CN', 'cy-GB', 'da-DK', 'de-DE', 'en-AU', 'en-GB', 'en-GB-WLS', 'en-IN', 'en-US', 'es-ES', 'es-MX', 'es-US', 'fr-CA', 'fr-FR', 'is-IS', 'it-IT', 'ja-JP', 'hi-IN', 'ko-KR', 'nb-NO', 'nl-NL', 'pl-PL', 'pt-BR', 'pt-PT', 'ro-RO', 'ru-RU', 'sv-SE', 'tr-TR', 'en-NZ', 'en-ZA');

		$lang = json_encode($languages);
		// $a = json_decode($lang);
		echo $lang;
		// echo ;

	}
}

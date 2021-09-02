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
	
	
	public function process()
	{


		$request = \Config\Services::request();
		$text = $request->getPost('text');
		$voice = $request->getPost('voice');
		$lang = $request->getPost('language');
		$polly = new PollyClient([
			'version' => 'latest',
			'region' => 'us-east-1', //region
			'credentials' => [
				'key' => 'AKIAT4G5L7NIFHQ2RVHB',
				'secret' => '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',
			]
		]);


		$result = $polly->synthesizeSpeech([
			'OutputFormat' => 'mp3',
			'Text' => $text,
			'VoiceId' => $voice,
			"LanguageCode" => $lang,
			// "Engine" => 'neural'
		]);

		$newdata =  $result->get("AudioStream");

		echo base64_encode($newdata);
		

		


	}
	public function describe_voices()
	{
		// This fUnction takes the input of language code and engine type  ['standard','neural'] and returns voices availabe with the condition given
		$request = \Config\Services::request();
		$language = $request->getPost('language');
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
			'LanguageCode' => "$language",
			// "Engine" => 'neural'
		]);
		
		$voices = json_encode($result['Voices']);
		echo $voices;
		


	}
	public function get_languages()
	{
		// This returns the languages supported by AWS Polly
		$languages = array('arb', 'cmn-CN', 'cy-GB', 'da-DK', 'de-DE', 'en-AU', 'en-GB', 'en-GB-WLS', 'en-IN', 'en-US', 'es-ES', 'es-MX', 'es-US', 'fr-CA', 'fr-FR', 'is-IS', 'it-IT', 'ja-JP', 'hi-IN', 'ko-KR', 'nb-NO', 'nl-NL', 'pl-PL', 'pt-BR', 'pt-PT', 'ro-RO', 'ru-RU', 'sv-SE', 'tr-TR', 'en-NZ', 'en-ZA');
		$lang = json_encode($languages);
		echo $lang;


	}
}

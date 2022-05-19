<?php

declare(strict_types=1);

namespace davidglitch04\PluginUpdater\Utils;

use davidglitch04\PluginUpdater\UpdateGenerator;
use Error;
use pocketmine\scheduler\AsyncTask;
use function curl_close;
use function curl_errno;
use function curl_error;
use function curl_exec;
use function curl_getinfo;
use function curl_init;
use function curl_setopt;
use function fclose;
use function fopen;

class DownloadFile extends AsyncTask {
    /** @var string $url */
	protected string $url;
    /** @var string $path */
	protected string $path;

    /**
     * @param UpdateGenerator $generator
     * @param string $url
     * @param string $path
     */
	public function __construct(UpdateGenerator $generator, string $url, string $path) {
		$this->url = $url;
		$this->path = $path;
		$this->storeLocal("generator", $generator); //4.0 compatible.
	}

    /**
     * @return void
     */
	public function onRun() : void {
		$file = fopen($this->path, 'w+');
		if ($file === false) {
			throw new Error('Could not open: ' . $this->path);
		}
		$ch = curl_init($this->url);
		curl_setopt($ch, CURLOPT_FILE, $file);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60); //give it 1 minute.
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_exec($ch);
		if (curl_errno($ch)) {
			throw new Error(curl_error($ch));
		}
		$statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		fclose($file);
		$this->setResult($statusCode);
	}

    /**
     * @return void
     */
	public function onCompletion() : void {
        $generator = $this->fetchLocal("generator");
		Utils::handleDownload($generator, $this->path, $this->getResult());
	}
}

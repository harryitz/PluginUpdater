<?php

declare(strict_types=1);

namespace davidglitch04\PluginUpdater\Utils;

use davidglitch04\PluginUpdater\UpdateGenerator;
use pocketmine\scheduler\AsyncTask;
use function curl_error;
use function curl_exec;
use function curl_getinfo;
use function curl_init;
use function curl_setopt;
use function json_decode;

class GetUpdateInfo extends AsyncTask {
    /** @var string $url */
    protected string $url;

    /**
     * @param UpdateGenerator $generator
     */
    public function __construct(UpdateGenerator $generator) {
        $this->url = $generator->getURL();
        $this->storeLocal("generator", $generator); //4.0 compatible.
    }

    /**
     * @return void
     */
    public function onRun() : void {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($curl);
        $curlerror = curl_error($curl);
        $responseJson = json_decode($response, true);
        $error = '';
        if ($curlerror != "") {
            $error = "Unknown error occurred, code:" . curl_getinfo($curl, CURLINFO_HTTP_CODE);
        } elseif (curl_getinfo($curl, CURLINFO_HTTP_CODE) != 200) {
            $error = $responseJson['message'];
        }
        $result = ["Response" => $responseJson, "Error" => $error, "httpCode" => curl_getinfo($curl, CURLINFO_HTTP_CODE)];
        $this->setResult($result);
    }

    /**
     * @return void
     */
    public function onCompletion() : void {
        $generator = $this->fetchLocal("generator");
        Utils::handleUpdateInfo($generator, $this->getResult());
    }
}
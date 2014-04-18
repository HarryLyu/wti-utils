<?php

namespace LinguaLeo\Tools\wti\Tasks;

use LinguaLeo\Tools\Task;
use LinguaLeo\wti\WtiApi;

abstract class WtiTask extends Task
{

    /**
     * @var WtiApi
     */
    protected $wti;

    protected function prepareFolder($folder) {
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }
    }

    /**
     * @param $apiKey
     * @param bool $initProjectInfo
     * @return WtiApi
     */
    protected function getWti($apiKey, $initProjectInfo = true)
    {
        return new WtiApi($apiKey, $initProjectInfo);
    }

    protected function getPassedApiKey()
    {
        try {
            $apiKey = $this->getArgument(0);
        } catch (\InvalidArgumentException $exception) {
            $this->error('Please provide an API key!');
            exit;
        }
        return $apiKey;
    }


    public static function deleteDir($dirPath) {
        if (! is_dir($dirPath)) {
            throw new InvalidArgumentException("$dirPath must be a directory");
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }
}
<?php

namespace LinguaLeo\Tools\wti\Tasks;

require __DIR__ . '/../../../../../vendor/autoload.php';

use LinguaLeo\Tools\Task;
use LinguaLeo\wti\WtiApi;

class SaveWtiCommentsTask extends Task
{

    protected $outputFolder;

    /**
     * @var WtiApi
     */
    protected $wti;

    protected function init()
    {
        $this->outputFolder = 'comments' . DIRECTORY_SEPARATOR;

        $this->writeLine('Initing project');
        $this->wti = $this->getWti($this->getPassedApiKey());
    }

    protected function execute()
    {
        $this->saveComments();
    }

    protected function saveComments() {
        $this->deleteDir($this->getFolderName());

        foreach ($this->wti->getProjectInfo()->project_files as $projectFile) {
            $comments = array();

            if ($projectFile->master_project_file_id !== null) {
                continue;
            }

            $strings = $this->wti->getStringsByKey(null, $projectFile->id);

            foreach ($strings as $string) {
                if (!$string->dev_comment && !$string->labels) {
                    continue;
                }

                if ($string->dev_comment) {
                    $comments[$string->key] = $string->dev_comment;
                }
                if ($string->labels) {
                    $comments[$string->key] = $string->labels;
                }
            }

            $this->saveCommentsFile($comments, $projectFile);
        }
    }

    protected function prepareFolder($folder) {
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }
    }

    protected function saveCommentsFile($comments, $file) {
        if (!count($comments)) {
            return;
        }

        foreach ($comments as $key => $value) {
            $comments[$key] = utf8_decode($value);
        }

        $filePath = $this->getFolderName() . DIRECTORY_SEPARATOR . $file->name;

        $this->prepareFolder(pathinfo($filePath, PATHINFO_DIRNAME));

        file_put_contents($filePath, json_encode($comments));
    }

    protected function getFolderName() {
        return $this->outputFolder . $this->wti->getProjectInfo()->id;
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

    private function getPassedApiKey()
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

(new SaveWtiCommentsTask())->run($argv);
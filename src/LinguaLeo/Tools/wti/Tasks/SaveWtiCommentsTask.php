<?php

namespace LinguaLeo\Tools\wti\Tasks;

require __DIR__ . '/../../../../../vendor/autoload.php';

use LinguaLeo\Tools\wti\Tasks;

class SaveWtiCommentsTask extends WtiTask
{

    protected $outputFolder;

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

    protected function getFolderName() {
        return $this->outputFolder . $this->wti->getProjectInfo()->id;
    }

    protected function saveComments() {
        if (is_dir($this->getFolderName())) {
            $this->deleteDir($this->getFolderName());
        }

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
}

$task = new SaveWtiCommentsTask();
$task->run($argv);
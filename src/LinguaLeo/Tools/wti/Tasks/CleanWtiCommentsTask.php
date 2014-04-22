<?php

namespace LinguaLeo\Tools\wti\Tasks;

require __DIR__ . '/../../../../../vendor/autoload.php';

use LinguaLeo\Tools\wti\Tasks;

class CleanWtiCommentsTask extends WtiTask
{

    protected function init()
    {
        $this->writeLine('Initing project');
        $this->wti = $this->getWti($this->getPassedApiKey());
    }

    protected function execute()
    {
        $this->cleanComments();
    }

    protected function cleanComments() {
        foreach ($this->wti->getProjectInfo()->project_files as $projectFile) {

            if ($projectFile->master_project_file_id !== null) {
                continue;
            }

            $strings = $this->wti->getStringsByKey(null, $projectFile->id);

            foreach ($strings as $string) {
                if (!$string->dev_comment) {
                    continue;
                }

                if ($comment = $string->dev_comment) {
                    if (preg_match_all('/^\: \d*$/', $comment)) {
                        $this->writeLine($string->key . ' / '. $comment);
                        $this->wti->updateString($string->id, ['dev_comment' => '']);
                    }
                }
            }
        }
    }
}

$task = new CleanWtiCommentsTask();
$task->run($argv);
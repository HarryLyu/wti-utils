<?php

namespace LinguaLeo\Tools\wti\Tasks;

require __DIR__ . '/../../../../../vendor/autoload.php';

class SaveSegmentsNotInFileTask extends WtiTask
{

    protected $outputFolder;

    protected function init()
    {
        $this->outputFolder = 'segments' . DIRECTORY_SEPARATOR;

        $this->writeLine('Initing project');
        $this->wti = $this->getWti($this->getPassedApiKey());
    }

    protected function execute()
    {
        $strings = $this->wti->listStrings(['filters' => ['file' => 'null']]);

        if (!count($strings)) {
            return;
        }

        if (is_object($strings) && $strings->error) {
            $this->writeLine($strings->error);
            exit;
        }

        $translations = [];

        foreach ($strings as $string) {
            if ($string->status === 'Obsolete') {
                continue;
            }
            $translation = $this->wti->getTranslation($string->id, $this->wti->getProjectInfo()->source_locale->code);
            $translations[$string->key] = $translation->text;
        }

        if (!count($translations)) {
            $this->writeLine('There are no segments not in files!');
            exit;
        }

        $folderName = $this->getFolderName();
        $filePath = $folderName . DIRECTORY_SEPARATOR . 'not_in_file.json';

        if (is_dir($folderName)) {
            $this->deleteDir($folderName);
        }

        $this->prepareFolder($folderName);

        file_put_contents($filePath, json_encode($translations));

        $this->writeLine($filePath . ' done');
    }

    protected function getFolderName() {
        $path = [
            $this->outputFolder . $this->wti->getProjectInfo()->id,
            $this->wti->getProjectInfo()->source_locale->code
        ];
        return implode(DIRECTORY_SEPARATOR, $path);
    }
}

$task = new SaveSegmentsNotInFileTask();
$task->run($argv);
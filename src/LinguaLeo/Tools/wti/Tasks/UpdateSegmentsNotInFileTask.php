<?php

namespace LinguaLeo\Tools\wti\Tasks;

require __DIR__ . '/../../../../../vendor/autoload.php';

class UpdateSegmentsNotInFileTask extends WtiTask
{

    protected $outputFolder;

    protected function init()
    {
        $this->outputFolder = 'segments' . DIRECTORY_SEPARATOR;

        $this->writeLine('Initing project');
        $this->wti = $this->getWti($this->getPassedApiKey(), false);
    }

    protected function execute()
    {
        $locales = $this->wti->getProjectInfo()->target_locales;
        $sourceLocale = $this->wti->getProjectInfo()->source_locale;

        foreach ($locales as $locale) {
            if ($locale->code == $sourceLocale->code) {
                continue;
            }

            $folderName = $this->getFolderName($locale->code);
            $filePath = $folderName . DIRECTORY_SEPARATOR . 'not_in_file.json';
            if (file_exists($filePath)) {
                $fileStrings = json_decode(file_get_contents($filePath), true);
                foreach ($fileStrings as $key => $value) {
                    $fetchedStrings = $this->wti->getStringsByKey($key, null);

                    foreach ($fetchedStrings as $fetchedString) {
                        if ($fetchedString->file->id != null) {
                            continue;
                        }

                        $this->writeLine(implode(' / ', [$fetchedString->id, $fetchedString->key, $locale->code, 'updated']));
                        $this->wti->addTranslate($fetchedString->id, $locale->code, $value);
                    }
                }
            }
        }
    }

    protected function getFolderName($locale) {
        $path = [
            $this->outputFolder . $this->wti->getProjectInfo()->id,
            $locale
        ];
        return implode(DIRECTORY_SEPARATOR, $path);
    }
}

(new UpdateSegmentsNotInFileTask())->run($argv);
<?php

namespace LinguaLeo\Tools\wti\Tasks;

require __DIR__ . '/../../../../../vendor/autoload.php';

use LinguaLeo\Tools\Task;
use LinguaLeo\wti\WtiApi;

class DeleteAllUsersForLocaleTask extends Task
{

    /**
     * @var WtiApi
     */
    protected $wti;

    protected function init()
    {
        $this->writeLine('Initing project');
        $this->wti = $this->getWti($this->getPassedApiKey());
    }

    protected function execute()
    {
        $locale = $this->getArgument(1);

        $this->writeLine('Are you sure to delete all users for locale "' . $locale. '"? Type locale name to proceed: ');

        if ($this->read() != $locale) {
            exit;
        }

        foreach ($this->wti->listUsers(['role' => 'translator']) as $user) {
            if ($user->locale != $locale) {
                continue;
            }

            if ($user->type == 'invitation') {
                $this->wti->removeInvitation($user->id);
                $this->writeLine($user->email . ' / ' . $user->type . ' - deleted');
            } elseif ($user->type == 'membership') {
                $this->wti->removeMembership($user->id);
                $this->writeLine($user->email . ' / ' . $user->type . ' - deleted');
            }
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
}

(new DeleteAllUsersForLocaleTask())->run($argv);
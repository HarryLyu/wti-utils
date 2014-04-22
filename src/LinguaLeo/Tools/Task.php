<?php

namespace LinguaLeo\Tools;

abstract class Task
{

    abstract protected function init();
    abstract protected function execute();
    protected $argv;

    public function run(array $argv)
    {
        $this->argv = $argv;
        $this->init();
        $this->execute();
    }

    protected function writeLine($string)
    {
        echo $string . PHP_EOL;
    }

    protected function error($string)
    {
        echo 'ERR ' . $string . PHP_EOL;
    }

    protected function write($string)
    {
        echo $string;
    }

    protected function read()
    {
        return fgets(STDIN);
    }

    protected function getArgument($index)
    {
        $index = $index + 1;
        if (isset($this->argv[$index])) {
            return $this->argv[$index];
        }
        throw new \InvalidArgumentException("Argument with index \"{$index}\" does not exist.");
    }

} 
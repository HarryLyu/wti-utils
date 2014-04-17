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
        \cli\line($string);
    }

    protected function error($string)
    {
        \cli\err($string);
    }

    protected function write($string)
    {
        \cli\out($string);
    }

    protected function read()
    {
        return \cli\input();
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
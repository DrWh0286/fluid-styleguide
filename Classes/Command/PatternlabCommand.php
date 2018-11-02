<?php
/**
 * Created by PhpStorm.
 * User: sebastian
 * Date: 27.10.18
 * Time: 15:35
 */
namespace Pluswerk\Styleguide\Command;

use PatternLab\Generator;
use PatternLab\Saying;
use Pluswerk\Styleguide\Console;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use \PatternLab\Config;
use \PatternLab\Dispatcher;
use \PatternLab\Timer;

/**
 * Class PatternlabCommand
 * @package Pluswerk\Styleguide\Command
 */
class PatternlabCommand extends Command
{
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     * @throws \Exception
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->startPatternlabCommand();
    }

    /**
     * Configure the command by defining the name, options and arguments
     */
    public function configure()
    {
        $this->setDescription('Show entries from the sys_log database table of the last 24 hours.');
        $this->setHelp('Prints a list of recent sys_log entries.' . LF . 'If you want to get more detailed information, use the --verbose option.');
        $this->addOption('generate');
    }

    /**
     * @return string
     * @throws \Exception
     */
    private function retrieveBaseDirectory(): string
    {
        if (preg_match('/^(\/.*)\/.*\/typo3/', PATH_typo3, $hits)) {
            return $hits[1];
        }
        throw new \Exception('Application base path could not be retrieved!');
    }

    /**
     * @throws \Exception
     */
    private function startPatternlabCommand():void
    {
        // start the timer
        Timer::start();

        // load the options
        Console::init();
        Config::init($this->retrieveBaseDirectory());

        // initialize the dispatcher & note that the config has been loaded
        Dispatcher::init();
        Dispatcher::getInstance()->dispatch("config.configLoadEnd");

        // run the console
        Console::run();
    }
}
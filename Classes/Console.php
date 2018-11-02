<?php
/**
 * Created by PhpStorm.
 * User: sebastian
 * Date: 27.10.18
 * Time: 17:09
 */

namespace Pluswerk\Styleguide;

use \PatternLab\Console as PatternlabConsole;
use PatternLab\Dispatcher;

class Console extends PatternlabConsole
{
    public static function run()
    {
        // send out an event
        $event = new PatternlabConsole\Event($options = array());
        Dispatcher::getInstance()->dispatch("console.loadCommandsStart",$event);

        // loadCommands
        self::loadCommands();

        // send out an event
        Dispatcher::getInstance()->dispatch("console.loadCommandsEnd",$event);

        // get what was passed on the command line
        self::$options = getopt(self::$optionsShort,self::$optionsLong);

        // test and run the given command
        $commandFound = false;
        $commandSent  = 'export';
        foreach (self::$commandInstances as $command) {
            if ($command->test($commandSent)) {
                $command->run();
                $commandFound = true;
            }
        }

        // no command was found so just draw the help by default
        if (!$commandFound) {

            self::writeHelp();

        }

    }
}
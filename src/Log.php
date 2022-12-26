<?php


namespace BetoCampoy\ChampsFramework;

use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\SlackWebhookHandler;

class Log
{

    /** @var Logger */
    private $log;

    public function __construct(?string $channel = 'main')
    {

        $recyclePeriod = CHAMPS_LOG_RECYCLE;
        $logLevel = CHAMPS_LOG_LEVEL;
        $logFolder = __CHAMPS_DIR__ ."/". CHAMPS_STORAGE_ROOT_FOLDER."/".CHAMPS_STORAGE_LOG_FOLDER;

        // create a log channel
        $this->log = new Logger($channel);

        // config file handler
        $logFile = CHAMPS_LOG_FILE_NAME.".log" ;
        $fileLog = new RotatingFileHandler(
          "{$logFolder}/{$logFile}",
          $recyclePeriod,
          constant(Logger::class."::{$logLevel}")
        );
        //        $fileLog->setFormatter($formatter);
        $this->log->pushHandler($fileLog);

        if(CHAMPS_LOG_SLACK_ACTIVE){

            $slack = new SlackWebhookHandler(
              CHAMPS_LOG_SLACK_WEBHOOK,
              CHAMPS_LOG_SLACK_CHANNEL,
              null,
              true,
              null,
              true,
              true,
              constant(Logger::class."::{$logLevel}")
            );
            //            $slack->setFormatter($formatter);

            $this->log->pushHandler($slack);
        }
    }

    /**
     * EMERGENCY (600): Emergency: system is unusable.
     *
     * @param string     $message
     * @param array|null $data
     */
    public function emergency(string $message, ?array $data = []) :void
    {
        $this->log->emergency($message, $data);
    }

    /**
     * ALERT (550): Action must be taken immediately. Example: Entire website down, database unavailable, etc.
     *      This should trigger the SMS alerts and wake you up.
     *
     * @param string     $message
     * @param array|null $data
     */
    public function alert(string $message, ?array $data = []):void
    {
        $this->log->alert($message, $data);
    }

    /**
     * CRITICAL (500): Critical conditions. Example: Application component unavailable, unexpected exception.
     *
     * @param string     $message
     * @param array|null $data
     */
    public function critical(string $message, ?array $data = [])
    {
        $this->log->critical($message, $data);
    }

    /**
     * ERROR (400): Runtime errors that do not require immediate action but should typically be logged and monitored.
     *
     * @param string     $message
     * @param array|null $data
     */
    public function error(string $message, ?array $data = [])
    {
        $data = $data ?? [];
        $this->log->error($message, $data);
    }

    /**
     * WARNING (300): Exceptional occurrences that are not errors.
     *      Examples: Use of deprecated APIs, poor use of an API,
     *      undesirable things that are not necessarily wrong.
     *
     * @param string     $message
     * @param array|null $data
     */
    public function warning(string $message, ?array $data = [])
    {
        $this->log->warning($message, $data);
    }

    /**
     * NOTICE (250): Normal but significant events.
     *
     * @param string     $message
     * @param array|null $data
     */
    public function notice(string $message, ?array $data = [])
    {
        $this->log->notice($message, $data);
    }

    /**
     * INFO (200): Interesting events. Examples: User logs in, SQL logs.
     *
     * @param string     $message
     * @param array|null $data
     */
    public function info(string $message, ?array $data = [])
    {
        $this->log->info($message, $data);
    }

    /**
     * DEBUG (100): Detailed debug information.
     *
     * @param string     $message
     * @param array|null $data
     */
    public function debug(string $message, ?array $data = [])
    {
        $this->log->debug($message, $data);
    }

}
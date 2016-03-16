<?php namespace Metricso\UnixSignalHandler;

class SignalHandler
{
    /**
     * @var array
     */
    private static $handlers = [];
    /**
     * @var array
     */
    private static $signals = [
        SIGINT => 'SIGINT',
        SIGTERM => 'SIGTERM'
    ];

    public static function register()
    {
        foreach (self::$signals as $signal => $signalName) {
            pcntl_signal($signal, function () use ($signal, $signalName) {
                if (!isset(self::$handlers[$signal])) {
                    return;
                }

                self::executeHandler($signal, $signalName, array_reverse(self::$handlers[$signal]));
            });
        }
    }

    /**
     * @param SignalHandlerIdentifier $identifier
     * @param callable $handler
     * @param array $signals
     */
    public static function addHandler(SignalHandlerIdentifier $identifier, callable $handler, array $signals = [])
    {
        foreach ($signals as $signal) {
            self::$handlers[$signal][$identifier->getIdentifier()] = $handler;
        }
    }

    /**
     * @param SignalHandlerIdentifier $identifier
     * @param array $signals
     */
    public static function removeHandler(SignalHandlerIdentifier $identifier, array $signals = [])
    {
        foreach ($signals as $signal) {
            if (isset(self::$handlers[$signal][$identifier->getIdentifier()])) {
                unset(self::$handlers[$signal][$identifier->getIdentifier()]);
            }
        }
    }

    /**
     * @param int $signal
     * @param string $signalName
     * @param array|callable $handler
     */
    private static function executeHandler($signal, $signalName, $handler)
    {
        if (is_array($handler)) {
            foreach ($handler as $value) {
                self::executeHandler($signal, $signalName, $value);
            }
        }

        $handler($signal, $signalName);
    }
}
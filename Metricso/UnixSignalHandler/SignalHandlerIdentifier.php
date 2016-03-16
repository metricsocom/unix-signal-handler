<?php namespace Metricso\SignalHandler;

class SignalHandlerIdentifier
{
    /**
     * @var string
     */
    private $identifier;

    /**
     * SignalHandlerIdentifier constructor.
     *
     * @param null $identifier
     */
    public function __construct($identifier = null)
    {
        if (!$identifier) {
            $identifier = uniqid();
        }

        $this->identifier = $identifier;
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }
}
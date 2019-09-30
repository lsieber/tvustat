<?php
namespace tvustat;

class QuerryOutcome
{

    private $message;

    private $success;

    /**
     *
     * @param string $message
     * @param bool $success
     */
    public function __construct(string $message, bool $success)
    {
        $this->message = $message;
        $this->success = $success;
    }

    /**
     *
     * @return array
     */
    public function getJSONArray()
    {
        return array(
            "message" => $this->message,
            "success" => $this->success
        );
    }

    /**
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     *
     * @return bool
     */
    public function getSuccess()
    {
        return $this->success;
    }
}


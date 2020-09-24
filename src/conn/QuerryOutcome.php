<?php
namespace tvustat;

class QuerryOutcome
{

    private $message;

    private $success;

    private $custumValues = array();

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

    public static function empty(bool $success = FALSE)
    {
        return new self("", $success);
    }

    /**
     *
     * @return array
     */
    public function getJSONArray()
    {
        $array = array(
            "message" => $this->message,
            "success" => $this->success
        );
        foreach ($this->custumValues as $key => $value) {
            $array[$key] = $value;
        }
        return $array;
    }

    /**
     *
     * @param string $key
     * @param mixed $value,
     *            has to be able to convert to a String
     */
    public function putCustomValue(string $key, $value)
    {
        $this->custumValues[$key] = strval($value);
    }

    public function getCustomValue(string $key)
    {
        return $this->custumValues[$key];
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

    /**
     *
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     *
     * @param bool $success
     */
    public function setSuccess($success)
    {
        $this->success = $success;
    }
}


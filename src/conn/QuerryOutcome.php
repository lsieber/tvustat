<?php
namespace tvustat;

class QuerryOutcome
{

    private $message;

    private $success;

    private $custumValues =  array();
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
        $array =  array(
            "message" => $this->message,
            "success" => $this->success
        );
        foreach ($this->custumValues as $key=>$value) {
            $array[$key] = $value;
        }
        return $array;
    }
    
    /**
     * 
     * @param string $key
     * @param mixed $value, has to be able to convert to a String
     */
    public function putCustomValue(string $key, $value){
         $this->custumValues[$key] = strval($value);
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


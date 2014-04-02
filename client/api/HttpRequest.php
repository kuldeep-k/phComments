<?php

class HttpRequest
{
    protected $request_url;
    protected $request_method;
    protected $request_data;
    const REQUEST_METHOD_POST = "POST";
    const REQUEST_METHOD_GET = "GET";
    const REQUEST_METHOD_DELETE = "DELETE";
    const REQUEST_METHOD_PUT = "PUT";
    public function setRequestUrl($request_url)
    {
        $this->request_url = $request_url;
    }

    public function setRequestMethod($request_method)
    {
        $this->request_method = $request_method;
    }

    public function setRequestData($request_data)
    {
        $this->request_data = $request_data;
    }

    public function getRequestInfo()
    {
        return $this->request_info;
    }

    public function execute()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $request_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        
        switch($this->request_method)
        {
            case self::REQUEST_METHOD_POST :
            case self::REQUEST_METHOD_GET :
            case self::REQUEST_METHOD_DELETE :
            case self::REQUEST_METHOD_PUT :
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 1);    
                break;
        }

        switch($this->request_method)
        {
            case self::REQUEST_METHOD_POST :
            case self::REQUEST_METHOD_PUT :
                curl_setopt($ch, CURLOPT_POSTFIELDS, $this->request_data);
                break;
        }

        $ch = curl_exec($ch);
        $this->request_info = curl_getinfo($ch);
        curl_close($curl_handle);
    }    
}
class chCommentsRest
{
           
}

?>

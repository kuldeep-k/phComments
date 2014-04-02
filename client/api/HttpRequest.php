<?php

class HttpRequest
{
    protected $request_url;
    protected $request_method;
    protected $request_data;
    protected $request_info;
    protected $response;

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

    public function getResponse()
    {
        return $this->response;
    }

    public function execute()
    {
        $ch = curl_init();
        
        
        
        switch($this->request_method)
        {
            case self::REQUEST_METHOD_DELETE :
            case self::REQUEST_METHOD_PUT :
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 1);    
                break;
            //case self::REQUEST_METHOD_POST :
            //case self::REQUEST_METHOD_GET :
            
        }

        switch($this->request_method)
        {
            case self::REQUEST_METHOD_POST :
            case self::REQUEST_METHOD_PUT :
                //echo 'ss';print_r($this->request_data);
                //echo http_build_query($this->request_data);
                curl_setopt($ch, CURLOPT_URL, $this->request_url);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($this->request_data));
                break;
            default: 
                //echo $this->request_url.http_build_query($_REQUEST);
                curl_setopt($ch, CURLOPT_URL, $this->request_url.'?'.http_build_query($_REQUEST));
                
                break;
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $this->response = curl_exec($ch);
        $this->request_info = curl_getinfo($ch);
        curl_close($ch);
    }    
}


?>

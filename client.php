<?php

class Client{

    private $socket = null;
    private $data = array();
    private $response;
    private $protacol;
    private $ip;
    private $port;
    /**
     * Args: port (integer) - the port number to bind to the 127.0.0.1, default is 8099
    **/
    public function __construct($protacol='tcp', $ip='127.0.0.1', $port=8099)
    {
        /*
         *  A class which takes care of the socket communication with oxD Server.
         *  The object is initialized with the port number
        */
        if(!$this->socket = stream_socket_client($protacol.'://'.$ip.':'.$port,$errno,$errstr,STREAM_CLIENT_PERSISTENT)){
            die($errno);
        }

        $this->protacol =  $protacol;
        $this->ip =  $ip;
        $this->port =  $port;

    }
    /**
     * @return null
    **/
    public function getSocket()
    {
        return $this->socket;
    }
    /**
     * send function sends the command to the oxD server.
        Args:
            command (dict) - Dict representation of the JSON command string
    **/
    public function send($command,$params)
    {
        $this->data = array('command'=>$command,'params'=>$params);
        $this->data= json_encode($this->data,JSON_PRETTY_PRINT);
        fwrite($this->socket,$this->data);
    }
    /**
     * send_test function sends the command to the oxD server.
    Args:
    command (dict) - Dict representation of the JSON command string
     **/
    public function send_test(
        $command='register_client',
        $params = array( "discovery_url"=>"https://seed.gluu.org/.well-known/openid-configuration",
            "redirect_url"=>"https://rs.gluu.org/resources",
            "client_name"=>"oxD Client",
            "response_types"=>"code id_token token",
            "app_type"=>"web",
            "grant_types"=>"authorization_code implicit",
            "contacts"=>"mike@gluu.org yuriy@gluu.org",
            "jwks_uri"=>"https://seed.gluu.org/jwks")
    )
    {
        $this->data = array('command'=>$command,'params'=>$params);
        $this->data= json_encode($this->data,JSON_PRETTY_PRINT);
        fwrite($this->socket,$this->data);
    }
    /**
     * getResult function geting result from oxD server.
        Print:
        response - The JSON response from the oxD Server and print
     **/
    public function getResult(){
        $this->response = fread($this->socket, 8048);
        $object = json_decode($this->response);
        echo '<pre>';
        var_dump($object);

    }
    /**
     * disconnect function closing connection .
     **/
    public function disconnect(){
        fclose($this->socket);
    }
}

$client = new Client();
$client->send_test();
$client->getResult();
$client->disconnect();
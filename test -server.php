<?php
class Server {

    private $socket = null;
    private $protacol;
    private $ip;
    private $port;
    /**
     * Server constructor.
     */
    public function __construct($protacol='tcp', $ip='127.0.0.1', $port=8099)
    {
        if(!$this->socket = stream_socket_server($protacol.'://'.$ip.':'.$port, $errno, $errstr)){
            die($errno);
        }
        while(true){
            $socket = stream_socket_accept($this->socket,-1);
            //echo fread($socket, 8048);

            $k = fread($socket, 8048);
            $object = json_decode($k);
            $message = '';
            $response = array();
            if (!empty($object) && $object->command == 'register_client') {

                $response = array('status' => 'ok',
                    'data' => array('
                                                "client_id":"@!1111!0008!0001",
                                                "client_secret":"ZJYCqe3GGRvdrudKyZS0XhGv_Z45DuKhCUk0gBR1vZk",
                                                "registration_access_token":"this.is.an.access.token.value.ffx83",
                                                "client_secret_expires_at": 1577858400,
                                                "registration_client_uri":"https://seed.gluu.org/oxauth/rest1/register?client_id=23523534",
                                                "client_id_issued_at": 1577858300
                                              ')
                );
                $message = json_encode($response);
            } else {
                $response = array('status' => 'error',
                    'data' => array('
                                                "error"=>"Error code 404",
                                                "error_description"=>"Error page!!!"
                                              ')
                );
                $message = json_encode($response);
            }
            fwrite($socket, $message);
            fclose($socket);
        }

        fclose($this->socket);

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

}

new Server();
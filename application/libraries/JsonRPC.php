<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class JsonRPC
{
    public function __construct()
    {
        require_once APPPATH . 'third_party/JsonRPCClient.php';
    }
}
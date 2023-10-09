<?php

namespace App\Helpers;

class Ftp
{
    /**
     * @var false|resource
     */
    public $ftp;
    private $server;
    private $user;
    private $password;

    public function __construct($server, $user, $password)
    {
        $this->server = $server;
        $this->user = $user;
        $this->password = $password;

        self::logado();

    }

    public function logado()
    {
        $ftp_server =  $this->server;
        $this->ftp = ftp_connect($ftp_server) or die("Could not connect to $ftp_server");

        ftp_pasv($this->ftp, true);

        $login = ftp_login($this->ftp, $this->user,  $this->password);
    }

    public function listar($directory)
    {
        try {
            return ftp_mlsd($this->ftp, $directory);
        } catch (\ErrorException $exception) {
            self::logado();
            $this->listar($directory);
        }


    }

    public function delete($directory): bool
    {
        try {
            return ftp_delete($this->ftp, $directory);
        } catch (\ErrorException $exception) {
            self::logado();
            $this->delete($directory);
        }


    }


    public function __destruct()
    {
        ftp_close($this->ftp);
    }
}




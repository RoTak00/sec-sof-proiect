<?php

class Mail
{
    private $registry = [];
    public function __construct($registry)
    {
        $this->registry = $registry;

    }
    function send($to, $subject, $body, $from = 'no-reply@authx.local')
    {
        $host = 'mailpit';
        $port = 1025;

        $socket = fsockopen($host, $port, $errno, $errstr, 10);
        if (!$socket) {
            throw new Exception("SMTP connection failed: $errstr ($errno)");
        }

        $read = function () use ($socket) {
            $response = '';
            while ($line = fgets($socket, 515)) {
                $response .= $line;
                if (preg_match('/^\d{3} /', $line)) {
                    break;
                }
            }
            return $response;
        };

        $write = function ($command) use ($socket, $read) {
            fwrite($socket, $command . "\r\n");
            return $read();
        };

        $read(); // greeting

        $write('EHLO authx.local');
        $write('MAIL FROM:<' . $from . '>');
        $write('RCPT TO:<' . $to . '>');
        $write('DATA');

        $headers = [];
        $headers[] = 'From: AuthX <' . $from . '>';
        $headers[] = 'To: <' . $to . '>';
        $headers[] = 'Subject: ' . $subject;
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-Type: text/plain; charset=UTF-8';

        $message =
            implode("\r\n", $headers) .
            "\r\n\r\n" .
            $body .
            "\r\n.";

        fwrite($socket, $message . "\r\n");
        $read();

        $write('QUIT');
        fclose($socket);

        return true;
    }
}
<?php

namespace What00\SocketMail;

use What00\SocketMail\SmtpStatus;

class Smtp
{
	var $connection;
    var $recipients;
    var $headers;
    var $timeout;
    var $errors;
    var $status;
    var $body;
    var $from;
    var $host;
    var $port;
    var $encryption;
    var $helo;
    var $auth;
    var $user;
    var $pass;
    var $debug;

    /**
     *  参数为一个数组
     *  host        SMTP 服务器的主机       默认：localhost
     *  port        SMTP 服务器的端口       默认：25
     *  helo        发送HELO命令的名称      默认：localhost
     *  user        SMTP 服务器的用户名     默认：空值
     *  pass        SMTP 服务器的登陆密码   默认：空值
     *  timeout     连接超时的时间          默认：5
     *  @return  bool
     */

    public function __construct($params = array())
    {
        if(!defined('CRLF')) define('CRLF', "\r\n", TRUE);

        $this->timeout    = 5;
        $this->status     = SmtpStatus::SMTP_STATUS_NOT_CONNECTED;
        $this->host       = 'localhost';
        $this->port       = 25;
        $this->auth       = FALSE;
        $this->encryption = 'ssl';
        $this->debug      = FALSE;
        $this->user       = '';
        $this->pass       = '';
        $this->errors     = array();
        foreach($params as $key => $value)
        {
            $this->$key = $value;
        }
        if($this->encryption){
            $this->host = $this->encryption . '://' . $this->host;
        }

        $this->helo     = $this->host;
        //  如果没有设置用户名则不验证
        $this->auth = ('' == $this->user) ? FALSE : TRUE;
    }
    public function connect($params = array())
    {
        if(!isset($this->status))
        {
            $obj = new Smtp($params);

            if($obj->connect())
            {
                $obj->status = SmtpStatus::SMTP_STATUS_CONNECTED;
            }
            return $obj;
        }
        else
        {
            $this->connection = fsockopen($this->host, $this->port, $errno, $errstr, $this->timeout);
            socket_set_timeout($this->connection, 0, 250000);
            $greeting = $this->get_data();

            if(is_resource($this->connection))
            {
                $this->status = 2;
                return $this->auth ? $this->ehlo() : $this->helo();
            }
            else
            {
                $this->errors[] = 'Failed to connect to server: '.$errstr;
                return FALSE;
            }
        }
    }

    /**
     * 参数为数组
     * recipients      接收人的数组
     * from            发件人的地址，也将作为回复地址
     * headers         头部信息的数组
     * body            邮件的主体
     */

    public function send($params = array())
    {
        foreach($params as $key => $value)
        {
            $this->set($key, $value);
        }
        if($this->is_connected())
        {
            //  服务器是否需要验证
            if($this->auth)
            {
                if(!$this->auth())  return FALSE;
            }
            $this->mail($this->from);
            if(is_array($this->recipients))
            {
                foreach($this->recipients as $value)
                {
                    $this->rcpt($value);
                }
            }
            else
            {
                $this->rcpt($this->recipients);
            }
            if(!$this->data()) return FALSE;
            $headers = str_replace(CRLF.'.', CRLF.'..', trim(implode(CRLF, $this->headers)));
            $body    = str_replace(CRLF.'.', CRLF.'..', $this->body);
            $body    = $body[0] == '.' ? '.'.$body : $body;
            $this->send_data($headers);
            $this->send_data('');
            $this->send_data($body);
            $this->send_data('.');
            return (substr(trim($this->get_data()), 0, 3) === '250');
        }
        else
        {
            $this->errors[] = 'Not connected!';
            return FALSE;
        }
    }

    public function helo()
    {
        if(is_resource($this->connection)
                AND $this->send_data('HELO '.$this->helo)
                AND substr(trim($error = $this->get_data()), 0, 3) === '250' )
        {
            return TRUE;
        }
        else
        {
            $this->errors[] = 'HELO command failed, output: ' . trim(substr(trim($error),3));
            return FALSE;
        }
    }


    public function ehlo()
    {
        if(is_resource($this->connection)
                AND $this->send_data('EHLO '.$this->helo)
                AND substr(trim($error = $this->get_data()), 0, 3) === '250' )
        {
            return TRUE;
        }
        else
        {
            $this->errors[] = 'EHLO command failed, output: ' . trim(substr(trim($error),3));
            return FALSE;
        }
    }

    public function auth()
    {
        if(is_resource($this->connection)
                AND $this->send_data('AUTH LOGIN')
                AND substr(trim($error = $this->get_data()),0,3) === '334'
                AND $this->send_data(base64_encode($this->user))            // Send username
                AND substr(trim($error = $this->get_data()),0,3) === '334'
                AND $this->send_data(base64_encode($this->pass))            // Send password
                AND substr(trim($error = $this->get_data()),0,3) === '235' )
        {
            return TRUE;
        }
        else
        {
            $this->errors[] = 'AUTH command failed: ' . trim(substr(trim($error),3));
            return FALSE;
        }
    }

    public function mail($from)
    {
        if($this->is_connected()
            AND $this->send_data('MAIL FROM:<'.$from.'>')
            AND substr(trim($this->get_data()), 0, 2) === '250' )
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
    public function rcpt($to)
    {
        if($this->is_connected()
            AND $this->send_data('RCPT TO:<'.$to.'>')
            AND substr(trim($error = $this->get_data()), 0, 2) === '25' )
        {
            return TRUE;
        }
        else
        {
            $this->errors[] = trim(substr(trim($error), 3));
            return FALSE;
        }
    }
    public function data()
    {
        if($this->is_connected()
            AND $this->send_data('DATA')
            AND substr(trim($error = $this->get_data()), 0, 3) === '354' )
        {
            return TRUE;
        }
        else
        {
            $this->errors[] = trim(substr(trim($error), 3));
            return FALSE;
        }
    }
    public function is_connected()
    {
        return (is_resource($this->connection) AND ($this->status === SmtpStatus::SMTP_STATUS_CONNECTED));
    }
    public function send_data($data)
    {
        if(is_resource($this->connection))
        {
            return fwrite($this->connection, $data.CRLF, strlen($data)+2);
        }
        else
        {
            return FALSE;
        }
    }
    public function &get_data()
    {
        $return = '';
        $line   = '';
        if(is_resource($this->connection))
        {
            while(strpos($return, CRLF) === FALSE OR substr($line,3,1) !== ' ')
            {
                $line        = fgets($this->connection, 512);
                $return      .= $line;
                if($this->debug){
                    echo $line."<br/>";
                }
            }
            return $return;
        }
        else
        {
            return FALSE;
        }
    }
    public function set($var, $value)
    {
        $this->$var = $value;
        return TRUE;
    }
}

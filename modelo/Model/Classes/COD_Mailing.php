<?php
/**
 * CodeDmx
 *
 * An open source application development framework for PHP
 *
 * The MIT License (MIT)
 *
 * Copyright (c) 2015 - 2016
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * @package CodeDmx
 * @author  https://github.com/mxra8
 * @copyright   Copyright (c) 2014 - 2016, Code Dmx (http://codedmx.com/)
 * @license http://opensource.org/licenses/MIT  MIT License
 * @link    https://codedmx.com
 * @since   Version 1.0
 * @filesource
 *
 * CodeDmx Mailing Class
 *
 * @category  Mailing
 * @package   COD_Mailing
 * @copyright Copyright (c) 2016-2017
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @version   1.0-master
 */
class COD_Mailing
{
    /**
     * Default wrap on the text
     *
     * @var int $wrap
     */
    protected $wrap = 78;

    /**
     * Array to send mail
     *
     * @var array $to
     */
    protected $to = array();

    /**
     * @var string $subject
     * @var string $message
     * @var string $params
     * @var string $uid
     */
    protected $subject, $message, $params, $uid;

    /**
     * @var array $headers
     */
    protected $headers = array();

    /**
     * @var array $attachments
     */
    protected $attachments = array();


    /**
     * Resets the class properties.
     *
     * @return COD_Mailing
     */
    public function __construct()
    {
        $this->reset();
    }

    /**
     * Resets all properties to initial state.
     *
     * @return COD_Mailing
     */
    public function reset()
    {
        $this->to = array();
        $this->headers = array();
        $this->subject = null;
        $this->message = null;
        $this->wrap = 78;
        $this->params = null;
        $this->attachments = array();
        $this->uid = $this->get_unique_id();

        return $this;
    }

    /**
     * Set email and name to send to
     *
     * @param string $email
     * @param string $name
     *
     * @return COD_Mailing
     */
    public function set_to($email, $name)
    {
        $this->to[] = $this->format_header((string) $email, (string) $name);
        return $this;
    }

    /**
     * Return an array of formatted To addresses.
     *
     * @return array
     */
    public function get_to()
    {
        return $this->to;
    }

    /**
     * Set email subject
     *
     * @param string $subject
     *
     * @return COD_Mailing
     */
    public function set_subject($subject)
    {
        $this->subject = $this->encode_utf8(
            $this->filter_other((string) $subject)
        );

        return $this;
    }

    /**
     * Get subject function
     *
     * @return string
     */
    public function get_subject()
    {
        return $this->subject;
    }

    /**
     * Set meesage to send
     *
     * @param string $message
     *
     * @return COD_Mailing
     */
    public function set_message($message)
    {
        $this->message = str_replace("\n.", "\n..", (string) $message);

        return $this;
    }

    /**
     * Get message to send
     *
     * @return string
     */
    public function get_message()
    {
        return $this->message;
    }

    /**
     * Add attachment to send
     *
     * @param string $path     
     * @param string $filename 
     *
     * @return COD_Mailing
     */
    public function add_attachment($path, $filename = null)
    {
        $filename = empty($filename) ? basename($path) : $filename;
        $this->attachments[] = array(
            'path' => $path,
            'file' => $filename,
            'data' => $this->get_attachment_data($path)
        );

        return $this;
    }

    /**
     * Get the attachment data
     *
     * @param string $path 
     *
     * @return string
     */
    public function get_attachment_data($path)
    {
        $filesize = filesize($path);
        $handle = fopen($path, "r");
        $attachment = fread($handle, $filesize);
        fclose($handle);

        return chunk_split(base64_encode($attachment));
    }

    /**
     * Information to send as from
     *
     * @param string $email 
     * @param string $name  
     *
     * @return COD_Mailing
     */
    public function set_from($email, $name)
    {
        $this->add_mail_header('From', (string) $email, (string) $name);

        return $this;
    }

    /**
     * The informacion to add
     *
     * @param string $header 
     * @param string $email  
     * @param string $name   
     *
     * @return COD_Mailing
     */
    public function add_mail_header($header, $email = null, $name = null)
    {
        $address = $this->format_header((string) $email, (string) $name);
        $this->headers[] = sprintf('%s: %s', (string) $header, $address);

        return $this;
    }

    /**
     * Agg generic information 
     *
     * @param string $header 
     * @param mixed  $value  
     *
     * @return COD_Mailing
     */
    public function add_generic_header($header, $value)
    {
        $this->headers[] = sprintf(
            '%s: %s',
            (string) $header,
            (string) $value
        );

        return $this;
    }

    /**
     * Return the headers registered so far as an array.
     *
     * @return array
     */
    public function get_headers()
    {
        return $this->headers;
    }

    /**
     * Set additional parameters
     *
     * @param string $additional_parameters 
     *
     * @return COD_Mailing
     */
    public function set_parameters($additional_parameters)
    {
        $this->params = (string) $additional_parameters;

        return $this;
    }

    /**
     * Get additional parameters
     *
     * @return string
     */
    public function get_parameters()
    {
        return $this->params;
    }

    /**
     * Set number of characters at which the message will wrap
     *
     * @param int $wrap 
     *
     * @return COD_Mailing
     */
    public function set_wrap($wrap = 78)
    {
        $wrap = (int) $wrap;
        if ($wrap < 1) 
        {
            $wrap = 78;
        }
        $this->wrap = $wrap;

        return $this;
    }

    /**
     * Get wrap
     *
     * @return int
     */
    public function get_wrap()
    {
        return $this->wrap;
    }

    /**
     * Checks if the email has any registered attachments.
     *
     * @return bool
     */
    public function has_attachments()
    {
        return ! empty($this->attachments);
    }

    /**
     * Assemble attachment
     *
     * @return string
     */
    public function assemble_attachment_headers()
    {
        $head = array();
        $head[] = "MIME-Version: 1.0";
        $head[] = "Content-Type: multipart/mixed; boundary=\"{$this->uid}\"";

        return join(PHP_EOL, $head);
    }

    /**
     * Assemble attachment body
     *
     * @return string
     */
    public function assemble_attachment_body()
    {
        $body = array();
        $body[] = "This is a multi-part message in MIME format.";
        $body[] = "--{$this->uid}";
        $body[] = "Content-type:text/html; charset=\"utf-8\"";
        $body[] = "Content-Transfer-Encoding: 7bit";
        $body[] = "";
        $body[] = $this->message;
        $body[] = "";
        $body[] = "--{$this->uid}";

        foreach ($this->attachments as $attachment) 
        {
            $body[] = $this->get_attachment_mime_template($attachment);
        }

        return implode(PHP_EOL, $body);
    }

    /**
     * Get attachment mime template
     *
     * @param array  $attachment 
     * @param string $uid        
     *
     * @return string
     */
    public function get_attachment_mime_template($attachment)
    {
        $file = $attachment['file'];
        $data = $attachment['data'];

        $head = array();
        $head[] = "Content-Type: application/octet-stream; name=\"{$file}\"";
        $head[] = "Content-Transfer-Encoding: base64";
        $head[] = "Content-Disposition: attachment; filename=\"{$file}\"";
        $head[] = "";
        $head[] = $data;
        $head[] = "";
        $head[] = "--{$this->uid}";

        return implode(PHP_EOL, $head);
    }

    /**
     * Send mail
     *
     * @throws \RuntimeException on no 'To: ' address to send to.
     * @return boolean
     */
    public function send()
    {
        $to = $this->get_to_for_send();
        $headers = $this->get_headers_for_send();

        if (empty($to)) 
        {
            throw new \RuntimeException(
                'Unable to send, no To address has been set.'
            );
        }

        if ($this->has_attachments()) 
        {
            $message  = $this->assemble_attachment_body();
            $headers .= PHP_EOL . $this->assemble_attachment_headers();
        } 
        else 
        {
            $message = $this->get_wrap_message();
        }

        return mail($to, $this->subject, $message, $headers, $this->params);
    }

    /**
     * Debug class
     *
     * @return string
     */
    public function debug()
    {
        return '<pre>' . print_r($this, true) . '</pre>';
    }

    /**
     * Magic __toString function
     *
     * @return string
     */
    public function __toString()
    {
        return print_r($this, true);
    }

    /**
     * Formats a display address for emails according to RFC2822
     *
     * @param string $email 
     * @param string $name  
     *
     * @return string
     */
    public function format_header($email, $name = null)
    {
        $email = $this->filter_email($email);
        if (empty($name)) 
        {
            return $email;
        }
        $name = $this->encode_utf8($this->filter_name($name));

        return sprintf('"%s" <%s>', $name, $email);
    }

    /**
     * Encode UTF8
     *
     * @param string $value
     *
     * @return string
     */
    public function encode_utf8($value)
    {
        $value = trim($value);
        if (preg_match('/(\s)/', $value)) 
        {
            return $this->encode_utf8_words($value);
        }

        return $this->encode_utf8_word($value);
    }

    /**
     * Encode UTF8 word
     *
     * @param string $value
     *
     * @return string
     */
    public function encode_utf8_word($value)
    {
        return sprintf('=?UTF-8?B?%s?=', base64_encode($value));
    }

    /**
     * Encode UTF8 words
     *
     * @param string $value
     *
     * @return string
     */
    public function encode_utf8_words($value)
    {
        $words = explode(' ', $value);
        $encoded = array();
        foreach ($words as $word) 
        {
            $encoded[] = $this->encode_utf8_word($word);
        }

        return join($this->encode_utf8_word(' '), $encoded);
    }

    /**
     * Removes any carriage return, line feed, tab, double quote, comma
     * and angle bracket characters before sanitizing the email address.
     *
     * @param string $email
     *
     * @return string
     */
    public function filter_email($email)
    {
        $rule = array(
            "\r" => '',
            "\n" => '',
            "\t" => '',
            '"'  => '',
            ','  => '',
            '<'  => '',
            '>'  => ''
        );
        $email = strtr($email, $rule);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        return $email;
    }

    /**
     * Removes any carriage return, line feed or tab characters. Replaces
     * double quotes with single quotes and angle brackets with square
     * brackets, before sanitizing the string and stripping out html tags.
     *
     * @param string $name
     *
     * @return string
     */
    public function filter_name($name)
    {
        $rule = array(
            "\r" => '',
            "\n" => '',
            "\t" => '',
            '"'  => "'",
            '<'  => '[',
            '>'  => ']',
        );

        $filtered = filter_var(
            $name,
            FILTER_SANITIZE_STRING,
            FILTER_FLAG_NO_ENCODE_QUOTES
        );

        return trim(strtr($filtered, $rule));
    }

    /**
     * Removes ASCII control characters including any carriage return, line
     * feed or tab characters.
     *
     * @param string $data
     *
     * @return string
     */
    public function filter_other($data)
    {
        return filter_var($data, FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW);
    }

    /**
     * Get headers for send
     *
     * @return string
     */
    public function get_headers_for_send()
    {
        if (empty($this->headers)) 
        {
            return '';
        }

        return join(PHP_EOL, $this->headers);
    }

    /**
     * Get to for send
     *
     * @return string
     */
    public function get_to_for_send()
    {
        if (empty($this->to)) 
        {
            return '';
        }

        return join(', ', $this->to);
    }

    /**
     * Get Unique Id
     *
     * @return string
     */
    public function get_unique_id()
    {
        return md5(uniqid(time()));
    }

    /**
     * Get wrap message
     *
     * @return string
     */
    public function get_wrap_message()
    {
        return wordwrap($this->message, $this->wrap);
    }
}
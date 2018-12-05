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
 * @package	CodeDmx
 * @author	https://github.com/mxra8
 * @copyright	Copyright (c) 2014 - 2016, Code Dmx (http://codedmx.com/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	https://codedmx.com
 * @since	Version 1.0
 * @filesource
 *
 * CodeDmx Loader class
 *
 * Loader all class that MVC required
 *
 * @category    Database Access
 * @package		CodeDmx
 * @subpackage	Libraries
 * @category	Loader
 * @author		https://github.com/mxra8
 */
class COD_Loader
{
    /**
     * Array that contain al subclasses to call it
     * 
     * ('supouse_to_call', 'class_name') 
     *
     * @var array
     */
    protected $classes = array(
        array("db", "QueryBuilder"),
        array("validate", "Validator"),
        array("crypt", "Bcrypt"),
        array('mail', 'Mailing')
    );
    
    // --------------------------------------------------------------------
    
    /**
     * Class constructor
     *
     * Load and include all classes.
     *
     * @return  void
     */
    function __construct()
    {
        foreach ($this->classes as $class)
        {
            require_once $GLOBALS['COD']->doc . 'Model' . DS . 'Classes' . DS . 'COD_'.$class[1].'.php';
            $name = "COD_".$class[1];
            if ($class[0] == "db")
            {
                $this->{$class[0]} = new $name($GLOBALS['COD']->host, $GLOBALS['COD']->usr, $GLOBALS['COD']->pwd, $GLOBALS['COD']->db, $GLOBALS['COD']->port, $GLOBALS['COD']->charset);
            }
            else
            {
                $this->{$class[0]} = new $name;
            }
        }
    }

    // --------------------------------------------------------------------
    
    /**
     * Call the class ImageClass when is needed and rename with
     * 
     * $this->imageclass
     * 
     * Then all of its methods
     *
     * @return  $this
     */
    public function image($image, $width = null, $height = null, $background = null)
    {
        require_once ($GLOBALS['COD']->doc . 'Model' . DS . 'Classes' . DS . 'COD_ImageClass.php');
        $this->imageclass = new COD_ImageClass($image, $width, $height, $background);
    }
    
    // --------------------------------------------------------------------
    
    /**
	 * Run the page that it required
	 * If doesn't exists show a custom Error 404
	 *
	 * @return	page
	 */
    public function run($elapsed_time = 0)
    {
        $rules = explode('/', $_SERVER['REQUEST_URI']);
        
        $page = (isset($_GET['path'])) ? $_GET['path'] : "home.php";
        $page = str_replace("/", "", $page);
        
        if ( ! file_exists($GLOBALS['COD']->doc . 'View' . DS . $page))
        {
        	require $GLOBALS['COD']->doc . '/View/static/File.php';
        	exit(1);
        }
        else
        {
        	include_once $GLOBALS['COD']->doc . 'View' . DS . $page;
        }
    }
    
    // --------------------------------------------------------------------
    
    /**
	 * Check if your site run on https
	 *
	 * @return	bool
	 */
    public function is_https()
    {
        if ( ! empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off')
        {
			return TRUE;
		}
		elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https')
		{
			return TRUE;
		}
		elseif ( ! empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off')
		{
			return TRUE;
		}
        
		return FALSE;
    }
    
    // --------------------------------------------------------------------
    
    /**
	 * Limit characters of an specific string
	 *
	 * @param   string   $string
	 * @param   int      $limit
	 * @param   string   $break
	 * @param   string   $pad
	 * 
	 * @return	string
	 */
    public function limit($string, $limit, $break=".", $pad="...")
    {
    	$string = html_entity_decode($string);
    	if(strlen($string) <= $limit)
    		return str_replace(array("&lt;", "&gt;", "&amp;"), array("<", ">", "&"), htmlentities($string));

    	if(FALSE !== ($breakpoint = strpos($string, $break, $limit)))
        {
    		if($breakpoint < strlen($string)-1)
    			$string = substr($string, 0, $breakpoint) . $pad;
    	}

    	return str_replace(array("&lt;", "&gt;", "&amp;"), array("<", ">", "&"), htmlentities($string));
    }
    
    // --------------------------------------------------------------------
    
    /**
	 * Limit characters of an specific string
	 *
	 * @param   bool   $md5
	 * 
	 * @return	string
	 */
    function create_id($md5 = FALSE) 
    {
        $uid = uniqid((double)microtime() * 10000, true);
        
        $result = str_replace(".", "", $uid);
        $result = $md5 ? md5($result) : $result;
        
        return $result;
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Show the language mode of an extension
    *
    * @param   string     $lang
    * 
    * @return	language
    */
    function language_mode($extension)
    {
        $arr = array('js', 'txt', 'htaccess');
        $rep = array('javascript', 'text', 'text');
        
        if (in_array($extension, $arr))
            $extension = str_replace($arr, $rep, $extension);
        
        return $extension;
    }

    // --------------------------------------------------------------------
    
    /**
     * Convert Array to string
     * expected output: <key1>="value1" <key2>="value2"
     * 
     * @param  array $array
     * 
     * @return string
     */
    public function array_to_string($array = array())
    {
        $string = "";
        if (isset($array) && is_array($array) && !empty($array)) 
        {
            foreach ($array as $key => $value)
                $string .= $key . '="' . $value . '" ';
        }
        return rtrim($string, " ");
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Validate Email address
     * 
     * @param  string $address 
     * @return boolean 
     */
    public function validate_email($address)
    {
        if (filter_var($address, FILTER_VALIDATE_EMAIL)) 
        {
            list(, $mailDomain) = explode("@", $address);
            if (checkdnsrr($mailDomain, "MX"))
                return true;
        }

        return false;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Make Curl call
     * 
     * @param  string  $url 
     * @param  string  $method 
     * @param  mixed $data 
     * @param  mixed $headers 
     * @param  boolean $returnInfo 
     * @return string|array  
     */
    public function curl($url, $method = 'GET', $data = FALSE, $headers = FALSE, $return = FALSE)
    {
        $ch   = curl_init();
        $info = null;
        
        if (strtoupper($method) == 'POST') 
        {
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            if ($data !== FALSE) 
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        } else 
        {
            if ($data !== FALSE) 
            {
                if (is_array($data)) 
                {
                    $data_tokens = array();
                    foreach ($data as $key => $value) 
                        array_push($data_tokens, urlencode($key) . '=' . urlencode($value));
                        
                    $data = implode('&', $data_tokens);
                }
                curl_setopt($ch, CURLOPT_URL, $url . '?' . $data);
            } else {
                curl_setopt($ch, CURLOPT_URL, $url);
            }
        }
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        if ($headers !== FALSE) 
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            
        $contents = curl_exec($ch);
        if ($return) 
            $info = curl_getinfo($ch);
            
        curl_close($ch);
        if ($return)
            return array('contents' => $contents, 'info' => $info);
        else
            return $contents;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Get a QR code
     * 
     * @param  string  $string 
     * @param  integer $width 
     * @param  integer $height 
     * @param  array $attributes 
     * 
     * @return string 
     */
    public function get_qr_code($string, $width = 150, $height = 150, $attributes = array())
    {
        $protocol = (self::is_https()) ? 'https://' : 'http://';
        $attr   = self::array_to_string($attributes);
        
        $api_url = $protocol . "chart.apis.google.com/chart?chs=" . $width . "x" . $height . "&cht=qr&chl=" . urlencode($string);
        return '<img src="' . $api_url . '" ' . trim($attr) . ' />';
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Create HTML A Tag
     * 
     * @param  string $link       
     * @param  string $text       
     * @param  array  $attributes 
     * 
     * @return string             
     */
    public function create_link_tag($link, $text = "", $attributes = array())
    {
        $link_tag = (self::validate_email($link)) ? '<a href="mailto:' . $link . '"' : '<a href="http://' . $link . '"';
        $attr = "";
        
        if ( ! isset($attributes['title']) && ! empty($text))
            $link_tag .= ' title="' . str_replace('"', '', strip_tags($text)) . '" ';
            
        if (empty($text))
            $text = $link;
            
        $attr .= self::array_to_string($attributes);
        $link_tag .= trim($attr) . '>' . htmlspecialchars($text, ENT_QUOTES, 'UTF-8') . "</a>";
        return $link_tag;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Generate Random Password
     * 
     * @param  integer $length 
     * 
     * @return string 
     */
    public function random_string($length = 8)
    {
        $alphabet    = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $pass        = array();
        $alpha_length = strlen($alphabet) - 1;
        for ($i = 0; $i < $length; $i++) 
        {
            $n      = rand(0, $alpha_length);
            $pass[] = $alphabet[$n];
        }
        return implode($pass);
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Determine if current page request type is ajax
     * 
     * @return boolean
     */
    public static function is_ajax()
    {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
            return true;
            
        return false;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Check if number is odd
     * 
     * @param  int  $num 
     * 
     * @return boolean
     */
    public function is_odd($num)
    {
        return $num % 2 !== 0;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Check if number is even
     * 
     * @param  int  $num 
     * 
     * @return boolean
     */
    public function is_even($num)
    {
        return $num % 2 == 0;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Returns the IP address of the client.
     * 
     * @param   boolean $headerContainingIPAddress
     * 
     * @return  string
     */
    public function get_ip($header = null)
    {
        if ( ! empty($header))
            return isset($_SERVER[$header]) ? trim($_SERVER[$header]) : false;
            
        $know_ip = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR');
        foreach ($know_ip as $key) 
        {
            if (array_key_exists($key, $_SERVER) === TRUE) 
            {
                foreach (explode(',', $_SERVER[$key]) as $ip) 
                {
                    $ip = trim($ip);
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== FALSE)
                        return $ip;
                }
            }
        }
        return FALSE;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Detect if user is on mobile device
     * 
     * @return boolean
     */
    public function is_mobile()
    {
        $useragent = $_SERVER['HTTP_USER_AGENT'];
        if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) 
        {
            return true;
        }
        return false;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Get user browser
     * 
     * @return string
     */
    public function know_browser()
    {
        $user_agent     = $_SERVER['HTTP_USER_AGENT'];
        $browser_name = $ub = $platform = 'Unknown';
        
        if (preg_match('/linux/i', $user_agent)) 
        {
            $platform = 'Linux';
        } 
        elseif (preg_match('/macintosh|mac os x/i', $user_agent)) 
        {
            $platform = 'Mac OS';
        } 
        elseif (preg_match('/windows|win32/i', $user_agent)) 
        {
            $platform = 'Windows';
        }
        
        if (preg_match('/MSIE/i', $user_agent) && !preg_match('/Opera/i', $user_agent)) 
        {
            $browser_name = 'Internet Explorer';
            $ub          = "MSIE";
        } 
        elseif (preg_match('/Firefox/i', $user_agent)) 
        {
            $browser_name = 'Mozilla Firefox';
            $ub          = "Firefox";
        } 
        elseif (preg_match('/Chrome/i', $user_agent)) 
        {
            $browser_name = 'Google Chrome';
            $ub          = "Chrome";
        } 
        elseif (preg_match('/Safari/i', $user_agent)) 
        {
            $browser_name = 'Apple Safari';
            $ub          = "Safari";
        } 
        elseif (preg_match('/Opera/i', $user_agent)) 
        {
            $browser_name = 'Opera';
            $ub          = "Opera";
        } 
        elseif (preg_match('/Netscape/i', $user_agent)) 
        {
            $browser_name = 'Netscape';
            $ub          = "Netscape";
        }
        
        $known   = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        
        preg_match_all($pattern, $user_agent, $matches);
        $i = count($matches['browser']);
        if ($i != 1) 
        {
            $version = (strripos($u_agent, "Version") < strripos($user_agent, $ub)) ? $matches['version'][0] : $matches['version'][1];
        } 
        else 
        {
            $version = $matches['version'][0];
        }
        
        if ($version == null || $version == "")
            $version = "?";
            
        return implode(", ", array($browser_name, "Version: " . $version, $platform));
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Get client location
     * 
     * @return string|false
     */
    public function get_location()
    {
        $result  = false;
        $ip_data = @json_decode(self::curl("http://www.geoplugin.net/json.gp?ip=" . self::get_ip()));
        if (isset($ip_data) && $ip_data->geoplugin_countryName != null) {
            $result = $ip_data->geoplugin_city . ", " . $ip_data->geoplugin_countryCode;
        }
        return $result;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Convert number to word representation
     *
     * @param  int $number 
     *
     * @return string 
     */
    public function number_to_word($number)
    {
        $hyphen      = '-';
        $conjunction = ' and ';
        $separator   = ', ';
        $negative    = 'negative ';
        $decimal     = ' point ';
        $fraction    = null;
        $dictionary  = array(0 => 'zero', 1 => 'one', 2 => 'two', 3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six', 7 => 'seven', 8 => 'eight', 9 => 'nine', 10 => 'ten', 11 => 'eleven', 12 => 'twelve', 13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen', 16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen', 19 => 'nineteen', 20 => 'twenty', 30 => 'thirty', 40 => 'fourty', 50 => 'fifty', 60 => 'sixty', 70 => 'seventy', 80 => 'eighty', 90 => 'ninety', 100 => 'hundred', 1000 => 'thousand', 1000000 => 'million', 1000000000 => 'billion', 1000000000000 => 'trillion', 1000000000000000 => 'quadrillion', 1000000000000000000 => 'quintillion');
        
        if ( ! is_numeric($number))
            return FALSE;
            
        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) 
        {
            trigger_error('number_to_word only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX, E_USER_WARNING);
            return FALSE;
        }
        
        if ($number < 0) 
            return $negative . self::number_to_word(abs($number));
        
        if (strpos($number, '.') !== FALSE) 
            list($number, $fraction) = explode('.', $number);
        
        switch (TRUE) 
        {
            case $number < 21:
                $string = $dictionary[$number];
            break;
            
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units) 
                    $string .= $hyphen . $dictionary[$units];
            break;
            
            case $number < 1000:
                $hundreds  = $number / 100;
                $remainder = $number % 100;
                $string    = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) 
                    $string .= $conjunction . self::number_to_word($remainder);
            break;
                
            default:
                $base_unit     = pow(1000, floor(log($number, 1000)));
                $num_base_units = (int) ($number / $base_unit);
                $remainder    = $number % $base_unit;
                $string       = self::number_to_word($num_base_units) . ' ' . $dictionary[$base_unit];
                if ($remainder) 
                {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= self::number_to_word($remainder);
                }
            break;
        }
        if (null !== $fraction && is_numeric($fraction))
        {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number)
                $words[] = $dictionary[$number];
                
            $string .= implode(' ', $words);
        }
        return $string;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Truncate String with or without ellipsis
     *
     * @param  string  $string 
     * @param  int  $max_length 
     * @param  boolean $add_ellipsis 
     * @param  boolean $word_safe 
     *
     * @return string 
     */
    public function cut_string($string, $max_length, $add_ellipsis = TRUE, $word_safe = TRUE)
    {
        $ellipsis  = '';
        $max_length = max($max_length, 0);
        if (mb_strlen($string) <= $max_length)
            return $string;

        if ($add_ellipsis) 
        {
            $ellipsis = mb_substr('...', 0, $max_length);
            $max_length -= mb_strlen($ellipsis);
            $max_length = max($max_length, 0);
        }
        if ($word_safe)
            $string = preg_replace('/\s+?(\S+)?$/', '', mb_substr($string, 0, $max_length));
        else 
            $string = mb_substr($string, 0, $max_length);

        if ($add_ellipsis)
            $string .= $ellipsis;
        
        return $string;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Get Alexa ranking for domain name
     *
     * @param  string $domain
     *
     * @return mixed false
     */
    public function get_alexa_rank($domain)
    {
        $domain      = preg_replace('~^https?://~', '', $domain);
        $alexa       = "http://data.alexa.com/data?cli=10&dat=s&url=%s";
        $request_url = sprintf($alexa, urlencode($domain));
        $xml         = simplexml_load_file($request_url);
        if ( ! isset($xml->SD[1])) 
            return false;

        $node_attributes = $xml->SD[1]->POPULARITY->attributes();
        $text           = (int) $node_attributes['TEXT'];
        return $text;
    }

    // --------------------------------------------------------------------
    
    /**
     * Get Google page rank for url
     *
     * @param  string $url
     *
     * @return mixed 
     */
    public function get_google_rank($url)
    {
        function str_to_num($str, $check, $magic)
        {
            $int_32_unit = 4294967296;

            $length = strlen($str);
            for ($i = 0; $i < $length; $i++) 
            {
                $check *= $magic;
                if ($check >= $int_32_unit) 
                {
                    $check = ($check - $int_32_unit * (int) ($check / $int_32_unit));
                    $check = ($check < -2147483648) ? ($check + $int_32_unit) : $check;
                }
                $check += ord($str{$i});
            }
            return $check;
        }

        function hash_url($string)
        {
            $check1 = str_to_num($string, 0x1505, 0x21);
            $check2 = str_to_num($string, 0, 0x1003F);
            $check1 >>= 2;
            $check1 = (($check1 >> 4) & 0x3FFFFC0) | ($check1 & 0x3F);
            $check1 = (($check1 >> 4) & 0x3FFC00) | ($check1 & 0x3FF);
            $check1 = (($check1 >> 4) & 0x3C000) | ($check1 & 0x3FFF);
            $t1     = (((($check1 & 0x3C0) << 4) | ($check1 & 0x3C)) << 2) | ($check2 & 0xF0F);
            $t2     = (((($check1 & 0xFFFFC000) << 4) | ($check1 & 0x3C00)) << 0xA) | ($check2 & 0xF0F0000);
            return ($t1 | $t2);
        }

        function check_hash($Hashnum)
        {
            $check_byte = 0;
            $flag      = 0;
            $hash_str   = sprintf('%u', $Hashnum);
            $length    = strlen($hash_str);

            for ($i = $length - 1; $i >= 0; $i--) 
            {
                $Re = $hash_str{$i};

                if (1 === ($flag % 2)) 
                {
                    $Re += $Re;
                    $Re = (int) ($Re / 10) + ($Re % 10);
                }

                $check_byte += $Re;
                $flag++;
            }

            $check_byte %= 10;
            if (0 !== $check_byte) 
            {
                $check_byte = 10 - $check_byte;
                if (1 === ($flag % 2)) 
                {
                    if (1 === ($check_byte % 2))
                        $check_byte += 9;
                    
                    $check_byte >>= 1;
                }
            }
            return '7' . $check_byte . $hash_str;
        }

        $query = "http://toolbarqueries.google.com/tbr?client=navclient-auto&ch=" . check_hash(hash_url($url)) . "&features=Rank&q=info:" . $url . "&num=100&filter=0";

        $data = file_get_contents($query);
        $pos  = strpos($data, "Rank_");
        if ($pos === false) 
        {
            return false;
        } 
        else 
        {
            $pagerank = substr($data, $pos + 9);
            return (int) $pagerank;
        }
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Get information on a short URL. Find out where it goes
     *
     * @param  string $short_url
     *
     * @return mixed
     */
    public function get_short_url($short_url)
    {
        if ( ! empty($short_url)) 
        {
            $headers = get_headers($short_url, 1);
            if (isset($headers["Location"])) 
            {
                return $headers["Location"];
            } 
            else 
            {
                $data = self::curl($short_url);
                preg_match_all('/<[\s]*meta[\s]*http-equiv="?' . '([^>"]*)"?[\s]*' . 'content="?([^>"]*)"?[\s]*[\/]?[\s]*>/si', $data, $match);

                if (isset($match) && is_array($match) && count($match) == 3) 
                {
                    $originals = $match[0];
                    $names     = $match[1];
                    $values    = $match[2];
                    if ((isset($originals) && isset($names) && isset($values)) && count($originals) == count($names) && count($names) == count($values)) 
                    {
                        $metaTags = array();
                        for ($i = 0, $limit = count($names); $i < $limit; $i++) 
                            $metaTags[$names[$i]] = array('html' => htmlentities($originals[$i]), 'value' => $values[$i]);
                    }
                }

                if (isset($metaTags['refresh']['value']) && !empty($metaTags['refresh']['value'])) 
                {
                    $returnData = explode("=", $metaTags['refresh']['value']);
                    if (isset($returnData[1]) && !empty($returnData[1])) 
                        return $returnData[1];
                }
            }
        }

        return false;
    }

    // --------------------------------------------------------------------
    
    /**
     * Shorten URL via tinyurl.com service
     *
     * @param  string $url
     *
     * @return mixed
     */
    public function short_url($url)
    {
        if (strpos($url, "http") === false)
            $url = 'http://' . $url;

        $gettiny = self::curl("http://tinyurl.com/api-create.php?url=" . $url);
        if (isset($gettiny) && ! empty($gettiny))
            return $gettiny;

        return false;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Parse text to find url's for embed enabled services like: youtube.com, blip.tv, vimeo.com, dailymotion.com, flickr.com, smugmug.com, hulu.com, revision3.com, wordpress.tv, funnyordie.com, soundcloud.com, slideshare.net and instagram.com and embed elements automatically
     *
     * @param  string $string 
     * @param  string $width  
     * @param  string $height 
     *
     * @return string
     */
    public function embed($string, $width = "560", $height = "315")
    {
        $providers = array('~https?://(?:[0-9A-Z-]+\.)?(?:youtu\.be/|youtube(?:-nocookie)?\.com\S*[^\w\s-])([\w-]{11})(?=[^\w-]|$)[?=&+%\w.-]*~ix' => 'http://www.youtube.com/oembed', '#https?://blip\.tv/(.+)#i' => 'http://blip.tv/oembed/', '~https?://(?:[0-9A-Z-]+\.)?(?:vimeo.com\S*[^\w\s-])([\w-]{1,20})(?=[^\w-]|$)[?=&+%\w.-]*~ix' => 'http://vimeo.com/api/oembed.{format}', '#https?://(www\.)?dailymotion\.com/.*#i' => 'http://www.dailymotion.com/services/oembed', '#https?://(www\.)?flickr\.com/.*#i' => 'http://www.flickr.com/services/oembed/', '#https?://(.+\.)?smugmug\.com/.*#i' => 'http://api.smugmug.com/services/oembed/', '#https?://(www\.)?hulu\.com/watch/.*#i' => 'http://www.hulu.com/api/oembed.{format}', '#https?://revision3\.com/(.+)#i' => 'http://revision3.com/api/oembed/', '#https?://wordpress\.tv/(.+)#i' => 'http://wordpress.tv/oembed/', '#https?://(www\.)?funnyordie\.com/videos/.*#i' => 'http://www.funnyordie.com/oembed', '#https?://(www\.)?soundcloud\.com/.*#i' => 'http://soundcloud.com/oembed', '#https?://(www\.)?slideshare.net/*#' => 'http://www.slideshare.net/api/oembed/2', '#http://instagr(\.am|am\.com)/p/.*#i' => 'http://api.instagram.com/oembed');

        $string    = preg_replace_callback('@(^|[^"|^\'])(https?://?([-\w]+\.[-\w\.]+)+\w(:\d+)?(/([-\w/_\.]*(\?\S+)?)?)*)@', function ($matches) use ($providers, $width, $height) 
        {
            $url = trim($matches[0]);
            $url = explode("#", $url);
            $url = reset($url);
            $provider = $request_url = FALSE;

            foreach ($providers as $pattern => $provider) 
            {
                if (preg_match($pattern, $url)) 
                {
                    if ($provider == "http://www.youtube.com/oembed") 
                        $url = str_replace("www.youtu.be/", "www.youtube.com/watch?v=", $url);

                    $request_url = str_replace('{format}', 'json', $provider);
                    break;
                }
            }

            if ($request_url !== FALSE) 
            {
                $params = array("maxwidth" => $width, "maxheight" => $height, "format" => "json");
                $request_url = $request_url . "?url=" . $url . "&" . http_build_query($params);
                $data       = json_decode(self::curl($request_url), true);
                switch ($data['type']) 
                {
                    case 'photo':
                        if (empty($data['url']) || empty($data['width']) || empty($data['height']) || ! is_string($data['url']) || ! is_numeric($data['width']) || ! is_numeric($data['height'])) 
                            return $matches[0];

                        $title = !empty($data['title']) && is_string($data['title']) ? $data['title'] : '';
                        return '<a href="' . $url . '"><img src="' . htmlspecialchars($data['url'], ENT_QUOTES, 'UTF-8') . '" alt="' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '" width="' . htmlspecialchars($data['width'], ENT_QUOTES, 'UTF-8') . '" height="' . htmlspecialchars($data['height'], ENT_QUOTES, 'UTF-8') . '" /></a>';

                    case 'video':
                    case 'rich':
                        if ( ! empty($data['html']) && is_string($data['html']))
                            return $data['html'];
                    break;
                    
                    case 'link':
                        if ( ! empty($data['title']) && is_string($data['title'])) 
                            return self::create_link_tag($url, $data['title']);
                    break;
                    default:
                        return $matches[0];
                }
            } 
            else 
            {
                return $matches[0];
            }
        }, $string);
        return $string;
    }
}
?>
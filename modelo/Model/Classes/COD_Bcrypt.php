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
 * CodeDmx Bcrypt
 *
 * @category  Cryptography
 * @package   COD_Bcrypt
 * @copyright Copyright (c) 2016-2017
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @version   1.0-master
 */
class COD_Bcrypt {

    /**
     * Work cost factor range between [04 - 31]
     * 
     * @var string
     */
    private static $work_cost = 12;

    /**
     * Default identifier
     * 
     * @var string
     */
    private static $identifier = '';

    /**
     * All valid hash identifiers
     * 
     * @var array
     */
    private static $valid_identifiers = array ('2a', '2x', '2y');
    
    /**
     * Change identifier for GLOBAL variable
     * 
     * @return string
     */
    public static function get_identifier()
    {
        return self::$identifier = $GLOBALS['COD']->identifier;
    }

    /**
     * Hash password
     * 
     * @param string $password
     * @param integer [optional] $work_factor
     *
     * @return string
     */
    public static function hash_password($password, $work_factor = 0) 
    {
        self::get_identifier();
        $salt = self::gen_salt($work_factor);
        return crypt($password, $salt);
    }

    /**
     * Check bcrypt password
     * 
     * @param string $password
     * @param string $stored_hash
     *
     * @return boolean
     */
    public static function check_password($password, $stored_hash) 
    {
        self::get_identifier();
        self::validate_identifier($stored_hash);
        $check_hash = crypt($password, $stored_hash);

        return ($check_hash === $stored_hash);
    }

    /**
     * Generates the salt string
     * 
     * @param integer $work_factor
     *
     * @return string
     */
    private static function gen_salt($work_factor) {
        if ($work_factor < 4 || $work_factor > 31) 
            $work_factor = self::$work_cost;

        $input = self::get_random_bytes();
        $salt = '$' . self::$identifier . '$';

        $salt .= str_pad($work_factor, 2, '0', STR_PAD_LEFT);
        $salt .= '$';

        $salt .= substr(strtr(base64_encode($input), '+', '.'), 0, 22);

        return $salt;
    }

    /**
     * OpenSSL's random generator
     * 
     * @return string
     */
    private static function get_random_bytes() 
    {
        if ( ! function_exists('openssl_random_pseudo_bytes'))
            throw new Exception('Unsupported hash format.');

        return openssl_random_pseudo_bytes(16);
    }

    /**
     * Validate identifier
     * 
     * @param string $hash
     *
     * @return void
     */
    private static function validate_identifier($hash) 
    {
        if ( ! in_array(substr($hash, 1, 2), self::$valid_identifiers))
            throw new Exception('Unsupported hash format.');
    }

}
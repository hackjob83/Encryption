<?php

/**
 * Description of Encrypt:
 *      Purpose of this class is to facilitate and ease use of OpenSSL encryption
 *      The primary purpose of the class is to encrypt strings of potentially sensitive data 
 *          (address, phone number, soc, etc) for storage and provide a simple way to decrypt
 * 
 *      This class utilizes a 3 part key for security
 *          - one part is stored in a database and is passed in the constructor
 *          - one part is in a plain text (log) file outside of web root on the server
 *          - the third part is stored within the class here
 *      All 3 parts of the key are combined and hashed to create the true key
 * 
 *      Currently set up to do only AES-256 encryption via CBC, but can be modified for other methods
 * 
 *      IV is created by hashing a text string stored within the class
 *      It is then shrunk down to only 16 charactrers, as that is what the AES-256-CBC method requires
 * 
 * Usage :
 *      // create object
 *      $Enc = new Encrypt(PART1_OF_KEY);
 * 
 *      // encrypt string
 *      $encrypted_string = $Enc->encrypt($plain_string);
 * 
 *      // decrypt string
 *      $decrypted = $Enc->decrypt($encrypted_string);
 *      
 * 
 * LICENSE: This source file is subject to version 3.01 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_01.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 * 
 * 
 *
 * @author      jhackett (@hackjob83) <hackjob83@gmail.com>
 * @license     http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version     0.1
 * @copyright   (c) 2015, John Hackett
 * @update      2015-09-01
 * 
 * 
 * 
 * 
 */


class Encrypt {
    
    /**
     *
     * @var type 
     */
    private $part3;
    private $super_secret_file;
    private $method;
    private $key;
    private $iv;
    private $full_iv;
    
    
    /** 
     * Constructor method
     * 
     * Takes part of the key as a param passed
     * Sets up the values for some of the vars
     * 
     * @param string $part_key
     */
    public function __construct($part_key) {
        
        $this->part3                    = " ENTER PART 3 OF YOUR KEY HERE ";
        $this->super_secret_file        = '/path/to/file/containing/key/part.txt';
        $this->method                   = "AES-256-CBC";
        $this->full_iv                  = " ENTER STRING TO USE AS IV HERE ";
        
        // encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $this->iv                       = substr(hash('sha256', $this->full_iv), 0, 16);
        
         // puts the key together and set it as well
        $this->key                      = $this->get_key($part_key);   
    }
    
    
    /**
     * Simple encrypt method wrapper     
     * 
     * @param string $plain_text
     * @return string
     */
    public function encrypt($plain_text) {
        $output = openssl_encrypt($plain_text, $this->method, $this->key, 0, $this->iv);
        return base64_encode($output);
    }
    
    /**
     * Simple decrypt method wrapper
     * 
     * @param string $enc_string
     * @return string
     */
    public function decrypt($enc_string) {
        return openssl_decrypt(base64_decode($enc_string), $this->method, $this->key, 0, $this->iv);
    }
    
    
    
    /**
     * Private function that combines and hashes the key
     * 
     * @param string $part1
     * @return string
     */
    private function get_key($part1) {
        
        $part2 = file_get_contents($this->super_secret_file);
        $combine = $part1 . " " . $part2 . " " . $this->part3;
        $full_key = hash('sha256',$combine);
        
        return $full_key;
        //$this->set_key($full_key);
    }
    
    
    /**
     * private function set up to set the key
     *  - not currently in use
     * 
     * @param type $key
     */
    private function set_key($key) {
        $this->key = $key;
    }
}

# Encryption
PHP Wrapper class for OpenSSL string encryption


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
 */

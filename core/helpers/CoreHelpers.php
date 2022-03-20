<?php

declare(strict_types=1);

/**User: Celio Natti... */

namespace NatoxCore\helpers;

use NatoxCore\Config;

/**
 * Class CoreHelpers
 * 
 * @author Celio Natti <amisuusman@gmail.com>
 * @package NatoxCore\helpers
 */

class CoreHelpers
{
    public function generateKey($size = 32)
    {
        return base64_encode(openssl_random_pseudo_bytes($size));
    }

    public function generateToken($size = 12)
    {
        return bin2hex(random_bytes($size));
    }

    /**
     * Encryption and Decryption methods used for key identification of entities
     * @param string $action to specify whether it is an ecryption or decryption
     * @param string $string to specify the field that needs to be encrypted or decrypted
     * @return string of the encrypted or decrypted field
     * @author Celio Natti <amisuusman@gmail.com>
     * @version 1.0
     */
    public static function encryptDecrypt($action, $data, $key = '')
    {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        // Remove the base64 encoding from our key
        $encryption_key = base64_decode($key);


        if ($action == 'encrypt') {
            // Generate an initialization vector
            $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($encrypt_method));
            // Encrypt the data using AES 256 encryption in CBC mode using our encryption key and initialization vector.
            $encrypted = openssl_encrypt($data, $encrypt_method, $encryption_key, 0, $iv);
            // The $iv is just as important as the key for decrypting, so save it with our encrypted data using a unique separator (::)
            $output = base64_encode($encrypted . '::' . $iv);
        } else if ($action == 'decrypt') {
            // To decrypt, split the encrypted data from our IV - our unique separator used was "::"
            $decrypted = explode('::', base64_decode($data), 2);
            if (sizeof($decrypted) == 2) {
                list($encrypted_data, $iv) = $decrypted;
                $output = openssl_decrypt($encrypted_data, $encrypt_method, $encryption_key, 0, $iv);
            }
        }

        return $output;
    }

    /**
     * Gets/Sets a session variable by key
     * @author Dory A.Azar 
     * @version 1.0
     */
    function session($key, $value = null)
    {
        if ($value) {
            $_SESSION[$key] = $value;
        }
        return $_SESSION[$key] ?? null;
    }

    /**
     * Uploads a file from a form into the server
     * @param array $picture defines the picture attributes array
     * @return string the resulting public URL of the uploaded file
     * @author Dory A.Azar 
     * @version 1.0
     */

    public function uploadFile($picture)
    {
        $resultUrl = '';

        if (empty($picture) || (isset($picture['error']) && $picture['error'] != 0)) {
            return $resultUrl;
            exit;
        }

        // get the extension of the picture
        $extension = isset($picture['name']) ? pathinfo($picture['name'])['extension'] : '';

        // if an extension is valid
        if (isset($extension)) {

            //generate a new filename 
            $newfilename = generateToken(12) . "." . $extension;

            // specify the new document location
            $permanentLocation = UPLOAD_PATH . $newfilename;

            // proceed with the move and if successful return the public url to it
            if (move_uploaded_file($picture['tmp_name'], $permanentLocation)) {
                $resultUrl = $_SESSION['uploadsUrl'] . $newfilename;
            };
        }
        return $resultUrl;
    }

    /**
     * Deletes a file given its public path
     * @param string $url of the image public url
     * @return boolean of the result
     * @author Dory A.Azar 
     * @version 1.0
     */

    function deleteFile($url)
    {
        $path = parse_url($url, PHP_URL_PATH);
        $filename = basename($path);
        if (!$filename) {
            return false;
        }
        unlink(UPLOAD_PATH . $filename);
        return true;
    }


    /**
     * Checks if a string is a json
     * @param string $string defines the string to be checked
     * @return boolean true if it is a json
     * @author Dory A.Azar 
     * @version 1.0
     */

    function isJson($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    function csrf()
    {
        echo "<input type='hidden' name='_token' value='" . session('token') . "'>";
    }

    /**
     * cUrl request function
     */

    function httpRequest($url, $method, $data, $headers, $username = null, $password = null)
    {

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers
        ));

        if ($username && $password) {
            curl_setopt_array($curl, array(
                CURLOPT_USERPWD => $username . ":" . $password
            ));
        }

        if (strtoupper($method) == "POST" && !empty($data)) {
            curl_setopt_array($curl, array(
                CURLOPT_POSTFIELDS => json_encode($data)
            ));
        }
        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return json_decode($err, true);
        } else {
            return json_decode($response, true);
        }
    }


    /**
     * now() returns the current timestamp in a mySQL format
     */

    function now()
    {
        return date("Y-m-d H:i:s");
    }
}

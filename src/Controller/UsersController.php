<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         3.3.4
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use App\Controller\AppController;
use ReallySimpleJWT\Token;

/**
 * Users  Controller
 *
 */
class UsersController extends AppController
{
    
  public function login() {
    $secret = 'sec!ReT423*&'; // TODO: use salt?
    debug($_GET);
    $username = $_GET['username'];
    // TODO: lookup userid for username and verify password
    $userId = 123456;
    // Create token header as a JSON string
    $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
    debug($header);
    // Create token payload as a JSON string
    $payload = json_encode(['user_id' => $userId]);
    debug($payload);
    // Encode Header to Base64Url String
    $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
    // Encode Payload to Base64Url String
    $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
    debug($base64UrlHeader);
    debug($base64UrlPayload);
    // Create Signature Hash
    $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);
    //debug($signature);
    // Encode Signature to Base64Url String
    $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));    
    // Create JWT
    $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    debug($jwt);
    $result1 = Token::validate($jwt, $secret);
    debug($result1);

    try {
      $expiration = time() + 3600;
      $issuer = 'localhost';
      $token = Token::create($userId, $secret, $expiration, $issuer);
      debug($token);
      $result = Token::validate($token, $secret);
      debug($result);
      // Return the header claims
      $h = Token::getHeader($token, $secret);
      debug($h);
      // Return the payload claims
      $p = Token::getPayload($token, $secret);
      debug($p);
    } catch (Exception $e) {
      debug($e->getMessage());
    }
  }
}

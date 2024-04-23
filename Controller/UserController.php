<?php

namespace App\Controller;

use App\Core\Database;
use App\Core\JWT;
use App\Core\Request;

class UserController {
    protected Request $request;
    protected Database $db;

    public function __construct(Request $request, Database $db) {
        $this->request = $request;
        $this->db = $db;
    }

    public function login() {
        if ($this->request->post('email') && $this->request->post('password')) {
            $email = $this->request->post('email');
            $password = $this->request->post('password');
            
            $user = $this->db->select("SELECT * FROM users WHERE email = ?", [$email])->fetch();
            if ($user && password_verify($password, $user['password'])) {
                $secretKey = 'Abbas';
                $payload = [
                    'iss' => 'http://abolfazl-deriss.ir',
                    'iat' => time(),
                    'nbf' => time() + 20,
                    'exp' => time() + 86400 * 30,
                    'data' => ['email' => $email],
                ];
                $jsonWebToken = JWT::encode($payload, $secretKey, 'HS256');
                if ($jsonWebToken) {
                    $this->db->update('users', $user['id'], ['token'], [$jsonWebToken]);
                    return $jsonWebToken;
                }
            } else {
                return ['error' => 'Invalid credentials'];
            }
        } else {
            return ['error' => 'Missing credentials'];
        }
    }

    public function logout() {
        $userData = $this->checkAuth();
        if ($userData) {
            $this->db->update('users', $userData['id'], ['token'], [null]);
        }
        header('Location: /login.php');
        exit;
    }

    public function checkAuth() {
        $token = $this->request->header('Authorization');
        if (!$token) {
            return ['error' => 'Unauthorized'];
        }
    
        $secretKey = 'Abbas';
        $decodedToken = JWT::decode($token, $secretKey, ['HS256']);
        if (!$decodedToken) {
            return ['error' => 'Invalid token'];
        }
    
        return $decodedToken['data'];
    }

    public function userProfile() {
        $userData = $this->checkAuth();
        
        if (isset($userData['email'])) {
            $userProfile = $this->db->select("SELECT * FROM users WHERE email = ?", [$userData['email']])->fetch();
            
            if ($userProfile) {
                return $userProfile;
            } else {
                return ['error' => 'User profile not found'];
            }
        } else {
            return ['error' => 'Unauthorized'];
        }
    }
}
?>
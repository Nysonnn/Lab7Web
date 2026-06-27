<?php

namespace App\Controllers\Api;

use App\Libraries\ApiToken;
use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;

class Auth extends ResourceController
{
    protected $format = 'json';

    public function login()
    {
        $identity = trim((string) $this->request->getVar('username'));
        $password = (string) $this->request->getVar('password');

        if ($identity === '' || $password === '') {
            return $this->failValidationErrors([
                'login' => 'Username/email dan password wajib diisi.',
            ]);
        }

        $user = (new UserModel())
            ->groupStart()
            ->where('username', $identity)
            ->orWhere('useremail', $identity)
            ->groupEnd()
            ->first();

        if ($user === null || ! password_verify($password, $user['userpassword'])) {
            return $this->failUnauthorized('Username atau password salah.');
        }

        return $this->respond([
            'status'   => 200,
            'error'    => null,
            'messages' => 'Login berhasil.',
            'data'     => [
                'id'       => (int) $user['id'],
                'username' => $user['username'],
                'token'    => ApiToken::issue((int) $user['id'], $user['username']),
            ],
        ]);
    }

    public function check()
    {
        $authorization = $this->request->getHeaderLine('Authorization');
        $token = preg_replace('/^Bearer\s+/i', '', $authorization) ?? '';

        return $this->respond([
            'status'        => 200,
            'authenticated' => true,
            'authorization' => 'Bearer ' . substr($token, 0, 18) . '••••••••',
            'messages'      => 'Token diterima dan lolos validasi ApiAuthFilter.',
        ]);
    }
}

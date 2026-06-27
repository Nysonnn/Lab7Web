<?php

namespace App\Filters;

use App\Libraries\ApiToken;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class ApiAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $authorization = trim($request->getHeaderLine('Authorization'));

        if (! preg_match('/^Bearer\s+(\S+)$/i', $authorization, $matches)) {
            return $this->unauthorized('Akses ditolak. Token Bearer tidak ditemukan.');
        }

        if (ApiToken::validate($matches[1]) === null) {
            return $this->unauthorized('Sesi token tidak valid atau kedaluwarsa.');
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }

    private function unauthorized(string $message): ResponseInterface
    {
        return Services::response()
            ->setStatusCode(401)
            ->setJSON([
                'status'   => 401,
                'error'    => 401,
                'messages' => $message,
            ]);
    }
}

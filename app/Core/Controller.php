<?php

namespace App\Core;

/* ~~~ Controller Base 🚀 ~~~ */

abstract class Controller
{
    /**
     * Get JSON request body.
     */
    protected function getRequestBody(): ?array
    {
        $json = file_get_contents("php://input");
        $arr = json_decode($json, true);
        return $arr ?: null;
    }

    /**
     * Send JSON response.
     */
    protected function response($data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
}

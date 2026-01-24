<?php

namespace RUC\helpers;
use RUC\config\RUCConfig;

class TokenVerifier
{
    /* =============================
     *  API PRINCIPAL
     * ============================= */

    public function verificarToken(string $token)
    {
        try {
            if (!$this->validarFormatoToken($token)) {
                error_log('[RUC] Token con formato inválido');
                return false;
            }

            [$base64Payload, $firmaRecibida] = explode('.', $token, 2);

            $firmaCalculada = $this->calcularFirma(
                $base64Payload,
                $this->getClaveSecreta()
            );

            if (!hash_equals($firmaCalculada, $firmaRecibida)) {
                error_log('[RUC] Firma inválida');
                return false;
            }

            $payload = $this->decodificarPayload($base64Payload);

            if (!$payload || !$this->validarPayload($payload)) {
                return false;
            }

            if (!$this->validarExpiracion($payload)) {
                error_log('[RUC] Token expirado');
                return false;
            }

            return $payload;

        } catch (\Throwable $e) {
            error_log('[RUC] Error token: ' . $e->getMessage());
            return false;
        }
    }

    public function extraerUsuario(string $token): ?array
    {
        $payload = $this->verificarToken($token);

        if (!$payload) {
            return null;
        }

        return [
            'usuario'       => $payload['usuario_vm'] ?? null,
            'token'         => $payload['token'] ?? null,
            'tipo_usuario'  => $payload['tipo_usuario'] ?? null,
        ];
    }

    /* =============================
     *  VALIDACIONES
     * ============================= */

    protected function validarFormatoToken(string $token): bool
    {
        return substr_count($token, '.') === 1;
    }

    protected function validarPayload(array $payload): bool
    {
        if (!isset($payload['usuario_vm'])) {
            return false;
        }

        foreach (['Identidad', 'Correo', 'Nombre'] as $campo) {
            if (empty($payload['usuario_vm'][$campo])) {
                return false;
            }
        }

        return true;
    }

    protected function validarExpiracion(array $payload): bool
    {
        return !isset($payload['exp']) || $payload['exp'] >= time();
    }

    /* =============================
     *  JWT CORE
     * ============================= */

    protected function calcularFirma(string $base64Payload, string $clave): string
    {
        return base64_encode(
            hash_hmac('sha256', $base64Payload, $clave, true)
        );
    }

    protected function decodificarPayload(string $base64Payload)
    {
        $json = base64_decode($base64Payload, true);
        return json_decode($json, true);
    }

    protected function getClaveSecreta(): string
    {
        $clave = RUCConfig::getClaveSecreta();

        if (!$clave) {
            throw new \Exception('Clave secreta RUC no configurada');
        }

        return $clave;
    }

    /* =============================
     *  GENERACIÓN / CIFRADO
     * ============================= */

    public function generarToken(array $usuario, int $duracion = 3600): string
    {
        $payload = [
            'usuario_vm' => $usuario,
            'iat' => time(),
            'exp' => time() + $duracion,
        ];

        $base64 = base64_encode(json_encode($payload));
        return $base64 . '.' . $this->calcularFirma($base64, $this->getClaveSecreta());
    }

    public function CrearCifrado(array $objeto): string
    {
        $json = json_encode($objeto, JSON_UNESCAPED_UNICODE);
        $payload = base64_encode($json);
        $firma = $this->calcularFirma($payload, $this->getClaveSecreta());

        return $payload . '.' . $firma;
    }

    public function retornoCifrado($portal, $accion, $noparam): string
    {
        $token = $_SESSION['central_ruc.token'] ?? null;

        $data = compact('portal', 'accion', 'noparam', 'token');
        $encoded = urlencode($this->CrearCifrado($data));

        return RUCConfig::getRucBaseUrlCentral() . "?cifrado={$encoded}";
    }
}

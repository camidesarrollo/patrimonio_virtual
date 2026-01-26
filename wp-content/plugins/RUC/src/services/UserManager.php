<?php

namespace RUC\services;

use WP_User;
use RUC\config\RUCConfig;

/**
 * Servicio para validación de URLs de redirección.
 */
class UserManager
{

    /**
     * Procesa un usuario: crea o actualiza.
     */
    public function procesarUsuario($usuarioApi)
    {
        try {
            $datosUsuario = $usuarioApi['usuario'] ?? null;

            if (!$datosUsuario) {
                return null;
            }

            if (is_object($datosUsuario)) {
                $datosUsuario = json_decode(json_encode($datosUsuario), true);
            }

            $identificacion = $datosUsuario['Identidad'] ?? '';
            $email = strtolower(trim($datosUsuario['Correo'] ?? ''));

            if (empty($identificacion) || empty($email)) {
                error_log('[RUC] Datos insuficientes');
                return null;
            }

            $esPasaporte = $this->esPasaporte($identificacion);
            $username    = $this->generarUsername($identificacion, $email, $esPasaporte);
            $datos       = $this->prepararDatosUsuario($datosUsuario, $esPasaporte);

            $user = $this->buscarUsuario($username)
                ?: $this->buscarUsuarioPorEmail($email);

            return $user
                ? $this->actualizarUsuario($user, $datos)
                : $this->crearUsuario($username, $datos);
        } catch (\Exception $e) {
            error_log('[RUC] Error procesarUsuario: ' . $e->getMessage());
            return null;
        }
    }

    protected function prepararDatosUsuario($usuario, bool $esPasaporte): array
    {
        // Convertir a array si es stdClass (para trabajar de forma uniforme)
        if (is_object($usuario)) {
            $usuario = json_decode(json_encode($usuario), true);
        }

        // Validar y preparar fecha de nacimiento
        $fechaNacimiento = null;
        if (!empty($usuario['FechaNacimiento'])) {
            try {
                // Reemplazar la "T" por espacio si viene en formato ISO
                $fecha = str_replace('T', ' ', $usuario['FechaNacimiento']);
                $fechaNacimiento = new  \DateTime($fecha);
            } catch (\Exception $e) {
                $fechaNacimiento = null;
            }
        }

        // Obtener comuna y región (desde el nivel superior o subarray)
        $comuna = $usuario['CodigoComuna'] ?? ($usuario['Comunas']['CodigoComuna'] ?? 0);
        $region = $usuario['Comunas']['CodigoRegion'] ?? 0;

        // Obtener sexo (M / F / Otro)
        $sexo = $usuario['Sexo']['CodigoSexo'] ?? '';

        // Obtener país de residencia o nombre desde subarray (si existiera)
        $paisResidencia = $usuario['CodigoPaisResidencia'] ?? ($usuario['PaisRecidencia']['CodigoPais'] ?? 0);

        // Retornar array listo para creación de usuario
        return [
            'codigo_usuario'        => $usuario['CodigoUsuario'] ?? 0,
            'tipo_identificacion'   => $esPasaporte ? "P" : "R",
            'rut_persona'           => $usuario['Identidad'] ?? '',
            'nombre_persona'        => $usuario['Nombre'] ?? '',
            'apellido_paterno'      => $usuario['ApellidoPaterno'] ?? '',
            'apellido_materno'      => $usuario['ApellidoMaterno'] ?? '',
            'fecha_nacimiento'      => $fechaNacimiento ? $fechaNacimiento->format('d-m-Y') : null,
            'sexo_persona'          => $sexo,
            'region_persona'        => $region,
            'comuna_persona'        => $comuna,
            'codigo_nacionalidad'   => $usuario['CodigoNacionalidad'] ?? ($usuario['Nacionalidad']['CodigoNacionalidad'] ?? 0),
            'pais_persona'          => $paisResidencia,
            'paisorigen_persona'    => $usuario['CodigoPaisOrigen'] ?? 0,
            'mail_persona'          => $usuario['Correo'] ?? '',
            'contrasena_persona'    => '', // se puede completar luego
        ];
    }


    /* =========================
     * BÚSQUEDAS
     * ========================= */

    public function buscarUsuario(string $username)
    {
        return get_user_by('login', $username) ?: null;
    }

    public function buscarUsuarioPorEmail(string $email)
    {
        return get_user_by('email', $email) ?: null;
    }

    /* =========================
     * CREAR / ACTUALIZAR
     * ========================= */

    protected function crearUsuario(string $username, array $datos): ?WP_User
    {
        $correo = $this->resolverCorreo($datos, null);

        // Crear usuario con password aleatorio
        $password = wp_generate_password(12, true);

        $user_id = wp_create_user($username, $password, $correo);

        if (is_wp_error($user_id)) {
            error_log('[RUC] Error al crear usuario WP: ' . $user_id->get_error_message());
            return null;
        }

        $user = get_user_by('id', (int) $user_id);

        if (!$user) {
            error_log('[RUC] Usuario creado pero no recuperable');
            return null;
        }

        // Setear datos básicos de WP
        wp_update_user([
            'ID'           => $user->ID,
            'display_name' => trim(
                ($datos['nombre_persona'] ?? '') . ' ' . ($datos['apellido_paterno'] ?? '')
            ),
        ]);

        // 🔥 REUTILIZACIÓN CLAVE
        $this->setearCamposUsuario($user, $datos);

        // Campo exclusivo de creación
        update_user_meta($user->ID, 'validado_persona', rand());

        error_log('[RUC] Usuario creado: ' . $username);

        return $user;
    }

    protected function actualizarUsuario(WP_User $user, array $datos): WP_User
    {
        $correo = $this->resolverCorreo($datos, $user);

        wp_update_user([
            'ID'           => $user->ID,
            'user_email'   => $correo,
            'display_name' => trim(
                ($datos['nombre_persona'] ?? '') . ' ' . ($datos['apellido_paterno'] ?? '')
            ),
        ]);

        $this->setearCamposUsuario($user, $datos);

        error_log('[RUC] Usuario actualizado: ' . $user->user_login);

        return $user;
    }


    /* =========================
     * META USUARIO
     * ========================= */

    protected function setearCamposUsuario(WP_User $user, array $datos): void
    {
        $meta = [
            // Identificación
            'rut_persona'         => $datos['rut_persona'] ?? '',
            'tipo_identificacion' => $datos['tipo_identificacion'] ?? '',

            // Nombre
            'nombre_persona'      => $datos['nombre_persona'] ?? '',
            'apellido_paterno'    => $datos['apellido_paterno'] ?? '',
            'apellido_materno'    => $datos['apellido_materno'] ?? '',

            // Campos estándar WP
            'first_name'          => $datos['nombre_persona'] ?? '',
            'last_name'           => trim(
                ($datos['apellido_paterno'] ?? '') . ' ' . ($datos['apellido_materno'] ?? '')
            ),

            // Datos personales
            'fecha_nacimiento'    => $datos['fecha_nacimiento'] ?? '',
            'sexo_persona'        => $datos['sexo_persona'] ?? '',

            // Ubicación
            'region_persona'      => $datos['region_persona'] ?? '',
            'comuna_persona'      => $datos['comuna_persona'] ?? '',
            'pais_persona'        => $datos['pais_persona'] ?? '',
            'paisorigen_persona'  => $datos['paisorigen_persona'] ?? '',

            // Nacionalidad
            'codigo_nacionalidad' => $datos['codigo_nacionalidad'] ?? '',

            // Correo
            'mail_persona'        => $datos['mail_persona'] ?? '',

            // Sistema RUC
            'codigo_usuario'      => $datos['codigo_usuario'] ?? '',
        ];

        foreach ($meta as $key => $value) {
            update_user_meta($user->ID, $key, $value);
        }
    }


    /* =========================
     * CORREOS
     * ========================= */

    protected function resolverCorreo(array $datos, ?WP_User $userActual): string
    {
        $correo = trim($datos['mail_persona']);
        $rut    = preg_replace('/[.\-]/', '', $datos['rut_persona']);

        if (
            $datos['mail_persona'] === true ||
            $this->correoExisteEnOtroUsuario($correo, $userActual)
        ) {
            return "{$rut}_correo_duplicado@example.com";
        }

        return $correo;
    }

    protected function correoExisteEnOtroUsuario(string $correo, ?WP_User $userActual): bool
    {
        $user = get_user_by('email', $correo);

        if (!$user) {
            return false;
        }

        return !$userActual || $user->ID !== $userActual->ID;
    }

    /* =========================
     * HELPERS
     * ========================= */

    protected function esPasaporte(string $identificacion): bool
    {
        return !str_contains($identificacion, '-');
    }

    protected function generarUsername(string $identificacion, string $email, bool $esPasaporte): string
    {
        return $esPasaporte
            ? sanitize_user($email, true)
            : sanitize_user(str_replace(['.', ' '], '', $identificacion), true);
    }

    protected function parsearFechaNacimiento($fecha)
    {
        if (!$fecha) {
            return null;
        }

        try {
            return (new \DateTime(str_replace('T', ' ', $fecha)))
                ->format('Y-m-d');
        } catch (\Exception $e) {
            error_log('[RUC] Fecha inválida: ' . $fecha);
            return null;
        }
    }

    /* =========================
     * ESTADÍSTICAS
     * ========================= */

    public function obtenerEstadisticas(): array
    {
        global $wpdb;

        $total = (int) $wpdb->get_var(
            "SELECT COUNT(ID) FROM {$wpdb->users}"
        );

        $rut = (int) $wpdb->get_var(
            "SELECT COUNT(user_id) FROM {$wpdb->usermeta}
             WHERE meta_key = 'tipo_identificacion'
             AND meta_value = 'R'"
        );

        $pasaporte = (int) $wpdb->get_var(
            "SELECT COUNT(user_id) FROM {$wpdb->usermeta}
             WHERE meta_key = 'tipo_identificacion'
             AND meta_value = 'P'"
        );

        return [
            'total' => $total,
            'rut' => $rut,
            'pasaporte' => $pasaporte,
        ];
    }
}

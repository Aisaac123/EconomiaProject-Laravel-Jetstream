<?php

return [
    'enable' => [
        'header' => 'No has activado la autenticación de dos factores.',
        'description' => 'Cuando la autenticación de dos factores esté activada, se te solicitará un token seguro y aleatorio durante el inicio de sesión. Puedes obtener este token desde la aplicación Google Authenticator de tu teléfono.',
    ],
    'logout' => [
        'button' => 'Cerrar sesión',
    ],
    'enabled' => [
        'header' => 'Has activado la autenticación de dos factores.',
        'description' => 'Guarda estos códigos de recuperación en un gestor de contraseñas seguro. Pueden usarse para recuperar el acceso a tu cuenta si pierdes tu dispositivo de autenticación de dos factores.',
    ],
    'setup_confirmation' => [
        'header' => 'Finaliza la activación de la autenticación de dos factores.',
        'description' => 'Cuando la autenticación de dos factores esté activada, se te solicitará un token seguro y aleatorio durante el inicio de sesión. Puedes obtener este token desde la aplicación Google Authenticator de tu teléfono.',
        'scan_qr_code' => 'Para finalizar la activación de la autenticación de dos factores, escanea el siguiente código QR con la aplicación autenticadora de tu teléfono o introduce la clave de configuración y proporciona el código OTP generado.',
    ],
    'base' => [
        'wrong_user' => 'El objeto de usuario autenticado debe ser un modelo de Filament Auth para permitir que la página de perfil lo actualice.',
        'rate_limit_exceeded' => 'Demasiadas solicitudes',
        'try_again' => 'Por favor, inténtalo de nuevo en :seconds segundos',
    ],
    '2fa' => [
        'confirm' => 'Confirmar',
        'cancel' => 'Cancelar',
        'enable' => 'Activar',
        'disable' => 'Desactivar',
        'confirm_password' => 'Confirmar Contraseña',
        'wrong_password' => 'La contraseña proporcionada es incorrecta.',
        'code' => 'Código',
        'setup_key' => 'Clave de Configuración: :setup_key.',
        'current_password' => 'Contraseña Actual',
        'regenerate_recovery_codes' => 'Generar Nuevos Códigos de Recuperación',
    ],
    'passkey' => [
        'add' => 'Crear Clave de Acceso (Passkey)',
        'name' => 'Nombre',
        'added' => 'Clave de acceso añadida correctamente.',
        'login' => 'Iniciar sesión con Clave de Acceso',
        'tootip' => 'Usa Face ID, huella digital o PIN',
        'notice' => [
            'header' => 'Las claves de acceso (Passkeys) son un método de inicio de sesión sin contraseña que utiliza la autenticación biométrica de tu dispositivo. En lugar de escribir una contraseña, apruebas el inicio de sesión en tu dispositivo de confianza.',
        ],
    ],
];

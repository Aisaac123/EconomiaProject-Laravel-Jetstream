<?php

return [
    'form' => [

        'name' => [
            'label' => 'Nombre',
        ],

        'team_owner' => [
            'label' => 'Propietario del Equipo',
        ],

        'email' => [
            'label' => 'Correo Electrónico',
        ],

        'password' => [

            'label' => 'Contraseña',

            'error_message' => 'La contraseña proporcionada es incorrecta.',

        ],

        'code' => [

            'label' => 'Código',

            'hint' => 'Por favor, confirma el acceso a tu cuenta introduciendo el código de autenticación proporcionado por tu aplicación de autenticación.',

            'error_message' => 'El código de autenticación de dos factores proporcionado no es válido.',

        ],

        'profile_photo' => [
            'label' => 'Foto',
        ],

        'current_password' => [
            'label' => 'Contraseña Actual',
        ],

        'new_password' => [
            'label' => 'Nueva Contraseña',
        ],

        'confirm_password' => [
            'label' => 'Confirmar Contraseña',
        ],

        'recovery_code' => [

            'label' => 'Código de Recuperación',

            'hint' => 'Por favor, confirma el acceso a tu cuenta introduciendo uno de tus códigos de recuperación de emergencia.',

        ],

        'token_name' => [
            'label' => 'Nombre del Token',
        ],

        'permissions' => [
            'label' => 'Permisos',
        ],

        'team_name' => [
            'label' => 'Nombre del Equipo',
        ],

        'or' => [
            'label' => 'O ',
        ],

    ],

    'table' => [

        'columns' => [

            'token_name' => [
                'label' => 'Tokens',
            ],

            'pending_invitations' => [
                'label' => 'Invitaciones Pendientes',
            ],

            'team_members' => [
                'label' => 'Miembros',
            ],

            'role' => [
                'label' => 'Rol',
            ],

        ],

    ],

    'notification' => [

        'save' => [

            'success' => [
                'message' => 'Guardado.',
            ],

        ],

        'create_token' => [

            'success' => [
                'message' => 'Por favor, copia tu nuevo token de API. Por tu seguridad, no se volverá a mostrar.',
            ],

            'error' => [
                'message' => 'Selecciona al menos un permiso.',
            ],

        ],

        'copy_token' => [

            'success' => [
                'message' => 'copiado al portapapeles',
            ],

        ],

        'token_deleted' => [

            'success' => [
                'message' => '¡Token eliminado!',
            ],

        ],

        'team_deleted' => [

            'success' => [
                'message' => '¡Equipo eliminado!',
            ],

        ],

        'team_member_removed' => [
            'success' => [
                'message' => 'Has eliminado a este miembro del equipo.',
            ],
        ],

        'team_invitation_sent' => [
            'success' => [
                'message' => 'Invitación al equipo enviada.',
            ],
        ],

        'team_invitation_cancelled' => [
            'success' => [
                'message' => 'Invitación al equipo cancelada.',
            ],
        ],

        'leave_team' => [

            'success' => [
                'message' => 'Has abandonado el equipo.',
            ],

        ],

        'accepted_invitation' => [

            'success' => [

                'title' => 'Invitación al Equipo Aceptada',

                'message' => '¡Genial! Has aceptado la invitación para unirte al equipo :team.',

            ],
        ],

        'rate_limited' => [

            'title' => 'Demasiadas solicitudes',

            'message' => 'Por favor, inténtalo de nuevo en :seconds segundos',

        ],

        'logged_out_other_sessions' => [

            'success' => [
                'message' => 'Todas las demás sesiones de navegador se han cerrado correctamente.',
            ],

        ],

        'permission_denied' => [

            'cannot_update_team_member' => 'No tienes permiso para actualizar a este miembro del equipo.',

            'cannot_leave_team' => 'No puedes abandonar un equipo que creaste.',

            'cannot_remove_team_member' => 'No tienes permiso para eliminar a este miembro del equipo.',

            'cannot_delete_team' => 'No tienes permiso para eliminar este equipo.',

        ],
    ],

    'action' => [

        'save' => [
            'label' => 'Guardar',
        ],

        'confirm' => [
            'label' => 'Confirmar',
        ],

        'cancel' => [
            'label' => 'Cancelar',
        ],

        'disable' => [
            'label' => 'Desactivar',
        ],

        'enable' => [
            'label' => 'Activar',
        ],

        'two_factor_authentication' => [

            'label' => [

                'regenerate_recovery_codes' => 'Regenerar Códigos de Recuperación',

                'use_recovery_code' => 'usar un código de recuperación',

                'use_authentication_code' => 'usar un código de autenticación',

                'logout' => 'Cerrar Sesión',

            ],

        ],

        'update_token' => [

            'title' => 'Permisos del Token de API',

            'label' => 'Permisos',

            'modal' => [
                'label' => 'Guardar',
            ],

        ],

        'delete_token' => [

            'title' => 'Eliminar Token de API',

            'description' => '¿Estás seguro de que deseas eliminar este token de API?',

            'label' => 'Eliminar',

        ],

        'delete_account' => [

            'label' => 'Eliminar Cuenta',

            'notice' => '¿Estás seguro de que quieres eliminar tu cuenta? Una vez que se elimine tu cuenta, todos sus recursos y datos se borrarán permanentemente. Por favor, introduce tu contraseña para confirmar que deseas eliminar permanentemente tu cuenta.',

        ],

        'delete_team' => [

            'label' => 'Eliminar Equipo',

            'notice' => '¿Estás seguro de que quieres eliminar este equipo? Una vez que se elimine un equipo, todos sus recursos y datos se borrarán permanentemente.',

        ],

        'create_token' => [
            'label' => 'Crear Token',
        ],

        'copy_token' => [
            'label' => 'Copiar',
        ],

        'add_team_member' => [

            'label' => 'Agregar',

            'error_message' => [

                'email_already_joined' => 'Este usuario ya pertenece al equipo.',

                'email_not_found' => 'No pudimos encontrar un usuario registrado con esta dirección de correo electrónico.',

                'email_already_invited' => 'Este usuario ya ha sido invitado al equipo.',

            ],
        ],

        'update_team_role' => [
            'title' => 'Gestionar Rol',
        ],

        'remove_team_member' => [

            'label' => 'Eliminar',

            'notice' => '¿Estás seguro de que quieres eliminar a este miembro del equipo?',
        ],

        'leave_team' => [

            'label' => 'Abandonar',

            'notice' => '¿Estás seguro de que quieres abandonar este equipo?',
        ],

        'resend_team_invitation' => [
            'label' => 'Reenviar',
        ],

        'cancel_team_invitation' => [
            'label' => 'Cancelar',
        ],

        'log_out_other_browsers' => [

            'label' => 'Cerrar Sesión en Otros Navegadores',

            'title' => 'Cerrar Sesión en Otros Navegadores',

            'description' => 'Introduce tu contraseña para confirmar que deseas cerrar la sesión en tus otros navegadores en todos tus dispositivos.',

        ],

    ],

    'mail' => [

        'team_invitation' => [

            'subject' => 'Invitación al Equipo',

            'message' => [
                'invitation' => '¡Has sido invitado a unirte al equipo :team!',

                'instruction' => 'Haz clic en el botón de abajo para aceptar la invitación y comenzar:',

                'notice' => 'Si no esperabas recibir una invitación a este equipo, puedes descartar este correo electrónico.',
            ],

            'label' => [

                'create_account' => 'Crear Cuenta',

                'accept_invitation' => 'Aceptar Invitación',

            ],

        ],

    ],

    'page' => [

        'create_team' => [

            'title' => 'Crear Equipo',

        ],

        'edit_team' => [

            'title' => 'Configuración del Equipo',

        ],

    ],

    'menu_item' => [

        'api_tokens' => [
            'label' => 'Tokens de API',
        ],

    ],

    'profile_photo' => [
    ],

    'update_profile_information' => [

        'section' => [

            'title' => 'Información del Perfil',

            'description' => 'Actualiza la información de tu perfil y tu dirección de correo electrónico.',

        ],

    ],

    'update_password' => [

        'section' => [

            'title' => 'Actualizar Contraseña',

            'description' => 'Asegúrate de que tu cuenta utiliza una contraseña larga y aleatoria para mantenerla segura.',

        ],

    ],

    'two_factor_authentication' => [

        'section' => [

            'title' => 'Autenticación de Dos Factores',

            'description' => 'Añade seguridad adicional a tu cuenta utilizando la autenticación de dos factores.',

        ],

    ],

    'delete_account' => [

        'section' => [

            'title' => 'Eliminar Cuenta',

            'description' => 'Elimina permanentemente tu cuenta.',

            'notice' => 'Una vez que se elimine tu cuenta, todos sus recursos y datos se borrarán permanentemente. Antes de eliminar tu cuenta, descarga cualquier dato o información que desees conservar.',

        ],

    ],

    'create_api_token' => [

        'section' => [

            'title' => 'Crear Token de API',

            'description' => 'Los tokens de API permiten que servicios de terceros se autentiquen con nuestra aplicación en tu nombre.',

        ],

    ],

    'manage_api_tokens' => [

        'section' => [

            'title' => 'Gestionar Tokens de API',

            'description' => 'Puedes eliminar cualquiera de tus tokens existentes si ya no los necesitas.',

        ],

    ],

    'browser_sessions' => [

        'section' => [

            'title' => 'Sesiones del Navegador',

            'description' => 'Gestiona y cierra sesión en tus sesiones activas en otros navegadores y dispositivos.',

            'notice' => 'Si es necesario, puedes cerrar la sesión de todos tus demás navegadores en todos tus dispositivos. A continuación se enumeran algunas de tus sesiones recientes; sin embargo, esta lista puede no ser exhaustiva. Si crees que tu cuenta ha sido comprometida, también deberías actualizar tu contraseña.',

            'labels' => [

                'current_device' => 'Este dispositivo',

                'last_active' => 'Última actividad',

                'unknown_device' => 'Desconocido',

            ],

        ],

    ],

    'create_team' => [

        'section' => [

            'title' => 'Crear Equipo',

        ],

    ],

    'update_team_name' => [

        'section' => [

            'title' => 'Nombre del Equipo',

            'description' => 'El nombre del equipo y la información del propietario.',

        ],

    ],

    'add_team_member' => [

        'section' => [

            'title' => 'Agregar Miembro al Equipo',

            'description' => 'Añade un nuevo miembro al equipo, permitiéndole colaborar contigo.',

            'notice' => 'Por favor, proporciona la dirección de correo electrónico de la persona que te gustaría agregar a este equipo.',

        ],

    ],

    'team_members' => [

        'section' => [

            'title' => 'Miembros del Equipo',

            'description' => 'Todas las personas que forman parte de este equipo.',

        ],

    ],

    'pending_team_invitations' => [

        'section' => [

            'title' => 'Invitaciones de Equipo Pendientes',

            'description' => 'Estas personas han sido invitadas a tu equipo y se les ha enviado un correo electrónico de invitación. Pueden unirse al equipo aceptando la invitación por correo electrónico.',

        ],

    ],

    'delete_team' => [

        'section' => [

            'title' => 'Eliminar Equipo',

            'description' => 'Elimina permanentemente este equipo.',

            'notice' => 'Una vez que se elimine un equipo, todos sus recursos y datos se borrarán permanentemente. Antes de eliminar este equipo, descarga cualquier dato o información que desees conservar.',

        ],

    ],

];

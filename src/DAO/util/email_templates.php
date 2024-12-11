<?php
function getTemplate($type, $placeholders = []) {
    $templates = [
        'confirmation' => "
            <html>
            <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
                <h2 style='color: #0056b3;'>Estimado(a) {{full_name}},</h2>
                <p>Gracias por enviar tu solicitud de admisión. Nos complace informarte que tu solicitud ha sido registrada exitosamente.</p>
                <p><strong>Número de solicitud:</strong> {{id_application}}</p>
                <p><strong>Contraseña:</strong> {{password}}</p>
                 <p><strong>Aviso</strong> No comparta ni pierda esta contraseña, la necesitará para continuar con el proceso exitosamente</p>
                <p>El siguiente paso en el proceso es la verificación de los datos proporcionados. En breve recibirás un correo nuestro informándote si la información ingresada es correcta o si es necesario realizar algún ajuste.</p>
                <p>Agradecemos tu interés en formar parte de nuestra comunidad universitaria. Estamos aquí para ayudarte en todo lo necesario durante este proceso.</p>
                <p>Atentamente,</p>
                <p style='color: #0056b3;'><strong>Equipo de Admisiones</strong></p>
                <p style='font-size: 12px; color: #777;'>Este es un mensaje automático, por favor no respondas a este correo. Si tienes preguntas, contáctanos a través de nuestros canales oficiales.</p>
            </body>
            </html>",
        'exam_results' => "
            <html>
            <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
                <h2 style='color: #0056b3;'>Hola, {{full_name}}</h2>
                <p>Te informamos que obtuviste los siguientes resultados en tu examen de admisión para 
                la máxima casa de estudios:</p>
                {{exams_details}}
                <p>Puedes acceder al enlace adjunto en este correo para seleccionar la carrera en la que
                te gustaría inscribirte. Usa tu número de identidad como usuario y la siguiente
                contraseña para ingresar al sitio.</p>
                <p>Contraseña: {{password_user_applicant}}</p>
                <a href='http://localhost:8000/views/admissions/login.html'>Elige tu carrera aquí</a>
                <p style='color: #ff6600;'><strong>Equipo de Admisiones</strong></p>
                <p style='font-size: 12px; color: #777;'>Este es un mensaje automático, por favor no respondas a este correo. Si tienes preguntas, contáctanos a través de nuestros canales oficiales.</p>
            </body>
            </html>",
        'career_acceptance' => "
            <html>
            <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
                <h2 style='color: #0056b3;'>Hola, {{full_name}}</h2>
                <p>¡Felicidades! Nos complace informarte que has sido aceptado/a en la carrera de {{career_name}}.</p>
                <p>¡Bienvenido Pumita!</p>
                <p style='color: #ff6600;'><strong>Equipo de Admisiones</strong></p>
                <p style='font-size: 12px; color: #777;'>Este es un mensaje automático, por favor no respondas a este correo. Si tienes preguntas, contáctanos a través de nuestros canales oficiales.</p>
            </body>
            </html>",
            'verification_email' => "
            <html>
            <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f9f9f9; padding: 20px;'>
                <div style='max-width: 600px; margin: auto; background: #fff; border-radius: 10px; padding: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);'>
                    <h2 style='color: #0056b3; text-align: center;'>Verificación de Correo Electrónico</h2>
                    <p>Estimado(a) {{full_name}},</p>
                    <p>Gracias por registrarte en nuestro sistema. Para completar tu registro, por favor ingresa el siguiente código de verificación en la página de registro:</p>
                    <div style='text-align: center; margin: 20px 0;'>
                        <h3 style='font-size: 24px; color: #333;'>{{verification_code}}</h3>
                    </div>
                    <p>Si no realizaste esta solicitud, por favor ignora este mensaje.</p>
                    <p>Atentamente,</p>
                    <p style='color: #0056b3;'><strong>Equipo de Soporte</strong></p>
                    <p style='font-size: 12px; color: #777;'>Este es un mensaje automático, por favor no respondas a este correo. Si tienes preguntas, contáctanos a través de nuestros canales oficiales.</p>
                </div>
            </body>
            </html>
            ",
            'confirmation_correct' => "
                <html>
                <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
                    <h2 style='color: #0056b3;'>Estimado(a) {{full_name}},</h2>
                    <p>Nos complace informarte que la información proporcionada en tu solicitud de admisión ha sido verificada y se encuentra correcta. No es necesario que realices ningún ajuste.</p>
                    <p>Tu solicitud ha sido procesada exitosamente, y ahora pasaremos al siguiente paso del proceso de admisión. Te mantendremos informado sobre cualquier novedad.</p>
                    <p>Agradecemos tu interés en formar parte de nuestra comunidad universitaria.</p>
                    <p>Atentamente,</p>
                    <p style='color: #0056b3;'><strong>Equipo de Admisiones</strong></p>
                    <p style='font-size: 12px; color: #777;'>Este es un mensaje automático, por favor no respondas a este correo. Si tienes preguntas, contáctanos a través de nuestros canales oficiales.</p>
                </body>
                </html>",
                'exam_results_warning' => "
                <html>
                <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
                    <h2 style='color: #ff6600;'>Estimado(a) {{full_name}},</h2>
                    <p>Te informamos que, tras revisar tu solicitud de admisión, hemos encontrado algunos errores en la información proporcionada. A continuación, te indicamos los campos que necesitan corrección:</p>
                    <ul>
                        {{campos_incorrectos}}
                    </ul>
                    <p><strong>Descripción:</strong> {{descripcion}}</p>
                    <p>Por favor, realiza las correcciones necesarias y envía nuevamente la solicitud. Estamos aquí para ayudarte durante este proceso.</p>
                    <p>Atentamente,</p>
                    <p style='color: #ff6600;'><strong>Equipo de Admisiones</strong></p>
                    <p style='font-size: 12px; color: #777;'>Este es un mensaje automático, por favor no respondas a este correo. Si tienes preguntas, contáctanos a través de nuestros canales oficiales.</p>
                </body>
                </html>",
                    "user_professor" =>'<html>
                    <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
                        <h2 style="color: #0056b3;">Estimado(a) {{full_name}},</h2>
                        <p>Nos complace darte la bienvenida al sistema de gestión universitaria. A continuación, te proporcionamos tu información de inicio de sesión:</p>
                        <p><strong>Usuario:</strong> {{username}}</p>
                        <p><strong>Contraseña:</strong> {{password}}</p>
                        <p><strong>Aviso:</strong> Por favor, mantén esta información en un lugar seguro y no la compartas con nadie. Se recomienda cambiar tu contraseña la primera vez que inicies sesión.</p>
                        <p>Si tienes alguna pregunta o necesitas asistencia, no dudes en ponerte en contacto con nuestro equipo de soporte a través de los canales oficiales.</p>
                        <p>Agradecemos tu compromiso con nuestra institución y estamos aquí para apoyarte en todo momento.</p>
                        <p>Atentamente,</p>
                        <p style="color: #0056b3;"><strong>Equipo de Gestión Universitaria</strong></p>
                        <p style="font-size: 12px; color: #777;">Este es un mensaje automático, por favor no respondas a este correo. Si necesitas ayuda, contáctanos a través de nuestros canales oficiales.</p>
                    </body>
                </html>',
            'users_login' => "
            <html>
            <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
               <h2 style='color: #0056b3;'>Estimado(a) {{full_name}},</h2>
                <p>A continuación te compartimos tu usuario y contraseña para que puedas acceder al portal virtual.</p>
                <p>Usuario: {{username_user_student}}</p>
                <p>Contraseña: {{password_user_student}}</p>
                <a href='https://www.facebook.com'>Inicia sesión aquí</a>
                <p style='color: #ff6600;'><strong>Equipo de Admisiones</strong></p>
                <p style='font-size: 12px; color: #777;'>Este es un mensaje automático, por favor no respondas a este correo. Si tienes preguntas, contáctanos a través de nuestros canales oficiales.</p>
            </body>
            </html>",
            'reset_request' => "
            <html>
            <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
                <h2 style='color: #0056b3;'>Estimado(a) {{full_name}},</h2>
                <p>Puedes restablecer tu contraseña accediendo al siguiente enlace:</p>
                <a href='http://localhost:8000/views/reset-view.php?token={{token}}' style='color: #0056b3;'>Actualiza tu contraseña</a>
                <p>Este enlace expirará dentro de 1 hora.</p>
                <p>Atentamente,</p>
                <p style='color: #0056b3;'><strong>Equipo de Admisiones</strong></p>
                <p style='font-size: 12px; color: #777;'>Este es un mensaje automático, por favor no respondas a este correo. Si tienes preguntas, contáctanos a través de nuestros canales oficiales.</p>            </body>
            </html>"
    ];

    $message = $templates[$type] ?? '';
    foreach ($placeholders as $key => $value) {
        $message = str_replace("{{{$key}}}", $value, $message);
    }

    return $message;
}
?>

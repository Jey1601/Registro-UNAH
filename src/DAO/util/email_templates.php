<?php
function getTemplate($type, $placeholders = []) {
    $templates = [
        'confirmation' => "
            <html>
            <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
                <h2 style='color: #0056b3;'>Estimado(a) {{full_name}},</h2>
                <p>Gracias por enviar tu solicitud de admisión. Nos complace informarte que tu solicitud ha sido registrada exitosamente.</p>
                <p><strong>Número de solicitud:</strong> {{id_application}}</p>
                <p>El siguiente paso en el proceso es la verificación de los datos proporcionados. En breve recibirás un correo nuestro informándote si la información ingresada es correcta o si es necesario realizar algún ajuste.</p>
                <p>Agradecemos tu interés en formar parte de nuestra comunidad universitaria. Estamos aquí para ayudarte en todo lo necesario durante este proceso.</p>
                <p>Atentamente,</p>
                <p style='color: #0056b3;'><strong>Equipo de Admisiones</strong></p>
                <p style='font-size: 12px; color: #777;'>Este es un mensaje automático, por favor no respondas a este correo. Si tienes preguntas, contáctanos a través de nuestros canales oficiales.</p>
            </body>
            </html>",
        'exam_results' => "
            <html>
            <body>
                <h2>Hola, {{full_name}}</h2>
                <p>Te informamos que obtuviste los siguientes resultados en tu examen de admisión para 
                la máxima casa de estudios:</p>
                {{exams_details}}
                <p>Puedes acceder al enlace adjunto en este correo para seleccionar la carrera en la que
                te gustaría inscribirte. Usa tu número de identidad como usuario y la siguiente
                contraseña para ingresar al sitio.</p>
                <p>Contraseña: {{password_user_applicant}}</p>
                <a href='https://www.facebook.com'>Elige tu carrera aquí</a>
                <p>Saludos,<br>El equipo de Admisiones</p>
            </body>
            </html>",
        'acceptance' => "
            <html>
            <body>
                <h2>Hola, {{full_name}}</h2>
                <p>Fuiste aceptado en {{career_name}}. ¡Bienvenido Pumita!</p>
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
            "
    ];

    $message = $templates[$type] ?? '';
    foreach ($placeholders as $key => $value) {
        $message = str_replace("{{{$key}}}", $value, $message);
    }

    return $message;
}
?>

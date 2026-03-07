<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
        .container { max-width: 500px; margin: auto; background: white; border-radius: 12px; padding: 40px; }
        .btn { display: inline-block; padding: 14px 28px; background: #2BB1D3; color: white;
               text-decoration: none; border-radius: 8px; font-weight: bold; margin-top: 24px; }
        .footer { margin-top: 32px; font-size: 12px; color: #999; }
    </style>
</head>
<body>
    <div class="container">
        <h2>🔐 Recuperar contraseña</h2>
        <p>Hola <strong>{{ $nombreUsuario }}</strong>,</p>
        <p>Recibimos una solicitud para restablecer la contraseña de tu cuenta. Haz clic en el botón para continuar.</p>
        <a href="{{ $linkRecuperacion }}" class="btn">Restablecer contraseña</a>
        <p class="footer">Este enlace expira en 60 minutos. Si no solicitaste esto, ignora este correo.</p>
    </div>
</body>
</html>
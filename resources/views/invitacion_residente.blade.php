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
        <h2>🏠 Bienvenido a Happy Community</h2>
        <p>Hola <strong>{{ $nombreResidente }}</strong>,</p>
        <p>El administrador de tu condominio te ha registrado en el sistema. 
           Haz clic en el botón para activar tu cuenta y establecer tu contraseña.</p>
        <a href="{{ $linkActivacion }}" class="btn">Activar mi cuenta</a>
        <p class="footer">Este enlace expira en 48 horas. Si no esperabas este correo, ignóralo.</p>
    </div>
</body>
</html>
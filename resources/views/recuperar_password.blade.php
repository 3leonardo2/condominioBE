<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
        .container { max-width: 500px; margin: auto; background: white; border-radius: 12px; padding: 40px; }
        .codigo { font-size: 48px; font-weight: 900; letter-spacing: 12px; color: #2BB1D3;
                  text-align: center; margin: 32px 0; padding: 20px;
                  background: #f0fbff; border-radius: 12px; border: 2px dashed #2BB1D3; }
        .footer { margin-top: 32px; font-size: 12px; color: #999; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <h2>🔐 Código de verificación</h2>
        <p>Hola <strong>{{ $nombreUsuario }}</strong>,</p>
        <p>Usa el siguiente código para restablecer tu contraseña. Expira en <strong>15 minutos</strong>.</p>
        <div class="codigo">{{ $codigo }}</div>
        <p>Si no solicitaste este código, ignora este correo. Tu contraseña no cambiará.</p>
        <p class="footer">Happy Community — Seguridad y Hogar</p>
    </div>
</body>
</html>
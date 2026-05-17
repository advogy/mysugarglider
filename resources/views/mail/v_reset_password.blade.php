<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reset Kata Sandi</title>
<style>
  body { margin: 0; padding: 0; background: #F5F0E8; font-family: 'Helvetica Neue', Arial, sans-serif; }
  .wrap { max-width: 520px; margin: 40px auto; background: #ffffff; border-radius: 20px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.07); }
  .header { background: linear-gradient(135deg, #4CAF82 0%, #3A9E6E 100%); padding: 32px 40px; text-align: center; }
  .logo { font-size: 22px; font-weight: 900; color: #ffffff; letter-spacing: -0.5px; }
  .logo-gold { color: #F5A623; }
  .body { padding: 40px; }
  .greeting { font-size: 18px; font-weight: 700; color: #3D2B1F; margin-bottom: 12px; }
  .text { font-size: 14px; color: #7A6355; line-height: 1.7; margin-bottom: 24px; }
  .btn-wrap { text-align: center; margin: 28px 0; }
  .btn { display: inline-block; background: linear-gradient(135deg, #4CAF82 0%, #3A9E6E 100%); color: #ffffff !important; text-decoration: none; font-weight: 700; font-size: 15px; padding: 16px 36px; border-radius: 14px; letter-spacing: 0.2px; }
  .url-fallback { background: #F5F0E8; border-radius: 12px; padding: 14px 16px; margin: 16px 0; word-break: break-all; }
  .url-fallback p { margin: 0 0 6px; font-size: 12px; color: #9E8878; }
  .url-fallback a { font-size: 12px; color: #4CAF82; text-decoration: none; }
  .divider { border: none; border-top: 1px solid #EDE8E0; margin: 28px 0; }
  .warning { background: #FFF8E7; border-left: 3px solid #F5A623; border-radius: 0 8px 8px 0; padding: 14px 16px; }
  .warning p { margin: 0; font-size: 13px; color: #7A6355; line-height: 1.5; }
  .footer { padding: 24px 40px; background: #F5F0E8; text-align: center; }
  .footer p { margin: 0; font-size: 12px; color: #9E8878; line-height: 1.6; }
  .footer strong { color: #3D2B1F; }
</style>
</head>
<body>
<div class="wrap">

  <div class="header">
    <div class="logo">My<span style="color:#A8DFC5">Sugar</span><span style="color:#A8DFC5">Glider</span><span class="logo-gold">.id</span></div>
  </div>

  <div class="body">
    <p class="greeting">Hai, {{ $user->name }}!</p>
    <p class="text">
      Kami menerima permintaan untuk mereset kata sandi akun MySugarGlider Anda.
      Klik tombol di bawah untuk membuat kata sandi baru. Tautan ini hanya berlaku selama
      <strong>{{ $expires }} menit</strong>.
    </p>

    <div class="btn-wrap">
      <a href="{{ $url }}" class="btn">Reset Kata Sandi</a>
    </div>

    <div class="url-fallback">
      <p>Jika tombol tidak berfungsi, salin tautan berikut ke browser Anda:</p>
      <a href="{{ $url }}">{{ $url }}</a>
    </div>

    <hr class="divider">

    <div class="warning">
      <p>
        Jika Anda tidak meminta reset kata sandi, abaikan email ini — kata sandi Anda
        tidak akan berubah dan tautan ini akan kedaluwarsa dengan sendirinya.
        <strong>Jangan bagikan tautan ini</strong> kepada siapapun.
      </p>
    </div>
  </div>

  <div class="footer">
    <p>
      Pesan ini dikirim otomatis oleh <strong>MySugarGlider.id</strong>.<br>
      Harap tidak membalas email ini.
    </p>
  </div>

</div>
</body>
</html>

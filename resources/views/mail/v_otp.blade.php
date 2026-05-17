<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kode Verifikasi Email</title>
<style>
  body { margin: 0; padding: 0; background: #F5F0E8; font-family: 'Helvetica Neue', Arial, sans-serif; }
  .wrap { max-width: 520px; margin: 40px auto; background: #ffffff; border-radius: 20px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.07); }
  .header { background: linear-gradient(135deg, #4CAF82 0%, #3A9E6E 100%); padding: 32px 40px; text-align: center; }
  .logo { font-size: 22px; font-weight: 900; color: #ffffff; letter-spacing: -0.5px; }
  .logo-gold { color: #F5A623; }
  .body { padding: 40px; }
  .greeting { font-size: 18px; font-weight: 700; color: #3D2B1F; margin-bottom: 12px; }
  .text { font-size: 14px; color: #7A6355; line-height: 1.7; margin-bottom: 24px; }
  .otp-block { background: #F5F0E8; border-radius: 16px; padding: 28px; text-align: center; margin: 24px 0; }
  .otp-label { font-size: 12px; font-weight: 700; color: #7A6355; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 12px; }
  .otp-code { font-size: 46px; font-weight: 900; letter-spacing: 12px; color: #3D2B1F; font-family: 'Courier New', monospace; }
  .otp-expiry { font-size: 12px; color: #9E8878; margin-top: 10px; }
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
      Gunakan kode verifikasi berikut untuk mengkonfirmasi alamat email Anda di MySugarGlider.
      Kode ini hanya berlaku selama <strong>{{ $expiryMinutes }} menit</strong>.
    </p>

    <div class="otp-block">
      <div class="otp-label">Kode Verifikasi Anda</div>
      <div class="otp-code">{{ $otp }}</div>
      <div class="otp-expiry">Berlaku hingga {{ now()->addMinutes($expiryMinutes)->format('H:i') }} WIB</div>
    </div>

    <hr class="divider">

    <div class="warning">
      <p>
        <strong>Jangan bagikan kode ini</strong> kepada siapapun, termasuk tim MySugarGlider.
        Jika Anda tidak merasa mendaftar atau mengubah email, abaikan pesan ini.
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

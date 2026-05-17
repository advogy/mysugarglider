@extends('layouts.v_auth')

@section('title', 'Verifikasi Email')

@section('form')

<div class="mb-8">
    <div class="w-14 h-14 rounded-2xl bg-sage/10 flex items-center justify-center mb-5">
        <i class="bi bi-envelope-check text-3xl text-sage"></i>
    </div>
    <h1 class="text-3xl font-display font-bold text-bark mb-2">Cek Email Anda</h1>
    <p class="text-bark-muted text-sm leading-relaxed">
        Kode verifikasi 6 digit telah dikirim ke<br>
        <span class="font-bold text-bark">{{ $email }}</span>
    </p>
</div>

@if (session('resent'))
    <div class="alert-success mb-5">
        <i class="bi bi-check-circle-fill text-lg flex-shrink-0"></i>
        <p class="font-semibold">Kode baru telah dikirimkan ke email Anda.</p>
    </div>
@endif

@if ($errors->has('otp'))
    <div class="alert-danger mb-5">
        <i class="bi bi-exclamation-circle-fill text-lg flex-shrink-0"></i>
        <p class="font-semibold">{{ $errors->first('otp') }}</p>
    </div>
@endif

<form action="{{ route('verification.otp.verify') }}" method="POST" id="otp-form">
    @csrf
    <input type="hidden" name="otp" id="otp-combined">

    <div class="flex gap-2.5 justify-center mb-8" id="otp-boxes">
        @for ($i = 0; $i < 6; $i++)
        <input type="text"
               inputmode="numeric"
               maxlength="1"
               class="otp-digit-box w-12 h-14 text-center text-2xl font-bold text-bark bg-cream rounded-2xl border-2 border-cream-dark focus:border-sage focus:ring-2 focus:ring-sage/20 focus:outline-none transition-all"
               data-index="{{ $i }}"
               autocomplete="{{ $i === 0 ? 'one-time-code' : 'off' }}"
               autofocus="{{ $i === 0 ? 'autofocus' : '' }}">
        @endfor
    </div>

    <button type="submit" id="submit-btn"
            class="auth-btn opacity-50 cursor-not-allowed" disabled>
        Verifikasi <i class="bi bi-arrow-right"></i>
    </button>
</form>

<div class="text-center mt-6">
    <p class="text-sm text-bark-muted mb-2">Tidak menerima kode?</p>
    <form action="{{ route('verification.resend') }}" method="POST" class="inline">
        @csrf
        <button type="submit" id="resend-btn"
                class="text-sm font-bold text-sage hover:underline disabled:opacity-40 disabled:cursor-not-allowed disabled:no-underline"
                disabled>
            Kirim Ulang <span id="resend-timer" class="text-bark-muted font-normal">(60s)</span>
        </button>
    </form>
</div>

<div class="text-center mt-8 pt-6 border-t border-cream-dark">
    <form action="{{ route('logout') }}" method="POST" class="inline">
        @csrf
        <button type="submit" class="text-sm text-bark-muted hover:text-bark font-semibold transition-colors">
            <i class="bi bi-box-arrow-left mr-1"></i> Keluar
        </button>
    </form>
</div>

@push('scripts')
<script>
(function () {
    const boxes    = Array.from(document.querySelectorAll('.otp-digit-box'));
    const combined = document.getElementById('otp-combined');
    const submitBtn = document.getElementById('submit-btn');
    const resendBtn = document.getElementById('resend-btn');
    const timerEl  = document.getElementById('resend-timer');

    // ── OTP boxes logic ──────────────────────────────────────
    function getValue() {
        return boxes.map(b => b.value).join('');
    }

    function updateSubmit() {
        const val = getValue();
        combined.value = val;
        const complete = val.length === 6;
        submitBtn.disabled = !complete;
        submitBtn.classList.toggle('opacity-50', !complete);
        submitBtn.classList.toggle('cursor-not-allowed', !complete);
    }

    boxes.forEach((box, i) => {
        box.addEventListener('input', () => {
            // Only digits
            box.value = box.value.replace(/\D/g, '').slice(-1);
            if (box.value && i < 5) boxes[i + 1].focus();
            updateSubmit();
        });

        box.addEventListener('keydown', e => {
            if (e.key === 'Backspace') {
                if (!box.value && i > 0) {
                    boxes[i - 1].value = '';
                    boxes[i - 1].focus();
                }
                updateSubmit();
            }
            if (e.key === 'ArrowLeft' && i > 0)  boxes[i - 1].focus();
            if (e.key === 'ArrowRight' && i < 5) boxes[i + 1].focus();
        });

        box.addEventListener('paste', e => {
            e.preventDefault();
            const digits = e.clipboardData.getData('text').replace(/\D/g, '').slice(0, 6);
            digits.split('').forEach((ch, j) => { if (boxes[j]) boxes[j].value = ch; });
            const next = Math.min(digits.length, 5);
            boxes[next].focus();
            updateSubmit();
        });

        // Select all on click for easy replacement
        box.addEventListener('click', () => box.select());
    });

    // Auto-submit when all 6 filled
    document.getElementById('otp-form').addEventListener('submit', () => {
        combined.value = getValue();
    });

    // ── Resend countdown ─────────────────────────────────────
    let seconds = 60;
    const interval = setInterval(() => {
        seconds--;
        timerEl.textContent = seconds > 0 ? `(${seconds}s)` : '';
        if (seconds <= 0) {
            clearInterval(interval);
            resendBtn.disabled = false;
        }
    }, 1000);
})();
</script>
@endpush

@endsection

@extends('layouts.v_main')

@section('title', 'Tentang Kami — MySugarGlider')

@section('content')

<header class="premium-page-header header-about">
    <div class="header-blob-about-1"></div>
    <div class="header-blob-about-2"></div>
    <h1 class="page-title size-lg">Tentang MySugarGlider</h1>
    <p class="page-subtitle subtitle-lg">Platform komunitas Sugar Glider terpercaya di Indonesia. Kami hadir untuk memastikan sahabat berbulu Anda mendapatkan perawatan terbaik.</p>
</header>

<section class="about-section">
    <div class="about-visual">
        <div class="about-blob"></div>
        <img src="{{ asset('assets/images/pets/sg_card1_1778842695259.png') }}" class="about-img" alt="About Us">
    </div>
    <div class="about-text">
        <h2 class="about-title">Karena Sugar Glider Anda begitu penting...</h2>
        <p class="about-desc">
            <span class="highlight-text">MySugarGlider.id</span> didirikan bermula dari kecintaan mendalam terhadap sugar glider. Kami menyadari bahwa belum ada platform terpusat di Indonesia yang dapat mencatat silsilah secara akurat dan modern.
        </p>
        <p class="about-desc">
            Platform ini hadir sebagai wadah bagi para pencinta, pemilik, dan peternak sugar glider. Dengan silsilah yang akurat, Anda bisa mendapatkan keturunan sugar glider yang berkualitas tinggi, mencegah inbreeding, dan memonitor kesehatan genetik mereka dengan lebih mudah.
        </p>
    </div>
</section>

<section class="stats-section">
    <div class="stat-box">
        <div class="stat-number color-yellow">{{ $stat_sg }}+</div>
        <div class="stat-label">Sugar Glider</div>
    </div>
    <div class="stat-box">
        <div class="stat-number color-blue">{{ $stat_shelter }}+</div>
        <div class="stat-label">Kandang Aktif</div>
    </div>
    <div class="stat-box">
        <div class="stat-number color-green">{{ $stat_user }}+</div>
        <div class="stat-label">Member Bergabung</div>
    </div>
</section>

@endsection

@extends('layouts.v_backend')
@section('title', 'Dashboard Admin')
@section('content')

<div class="mb-8">
    <h2 class="text-2xl font-display font-bold text-bark">Halo, {{ $user->name }}!</h2>
    <p class="text-bark-muted text-sm mt-1">Ringkasan keseluruhan data platform MySugarGlider.id.</p>
</div>

{{-- Statistik Global --}}
<div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4 mb-8">
    <x-stat-widget label="Total Pengguna"   :value="$count_users"                icon="bi-people-fill"         color="sage"  :url="route('admin.users.index')" />
    <x-stat-widget label="Kandang"          :value="$count_shelters"             icon="bi-house-heart-fill"    color="honey" :url="route('admin.data.shelters')" />
    <x-stat-widget label="Sugar Glider"     :value="$count_sugargliders"         icon="bi-heart-fill"          color="sky"   :url="route('admin.data.sugargliders')" />
    <x-stat-widget label="Penempatan Aktif" :value="$count_collections"          icon="bi-collection-fill"     color="sage"  :url="route('admin.data.collections')" />
    <x-stat-widget label="Adopsi Aktif"     :value="$count_adoptions"            icon="bi-journal-check"       color="honey" :url="route('adoption.list')" />
    <x-stat-widget label="Testimoni Pending" :value="$count_testimonials_pending" icon="bi-chat-quote-fill"    color="amber" :url="route('admin.testimonial.admin')" />
</div>

{{-- Panel navigasi cepat admin --}}
<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">

    <div class="be-card p-5">
        <h4 class="font-bold text-bark text-sm mb-4 flex items-center gap-2 uppercase tracking-wider text-bark-muted">
            <i class="bi bi-file-earmark-text-fill"></i> Konten
        </h4>
        <div class="space-y-2">
            <a href="{{ route('admin.testimonial.admin') }}" class="flex items-center justify-between gap-3 text-sm font-semibold text-bark hover:text-sage transition-colors py-1.5">
                <span><i class="bi bi-chat-quote-fill text-blue-sg mr-2"></i> Testimoni</span>
                @if ($count_testimonials_pending > 0)
                    <span class="text-xs font-bold bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full">{{ $count_testimonials_pending }} pending</span>
                @endif
            </a>
            <a href="{{ route('admin.configs.halaman') }}" class="flex items-center gap-3 text-sm font-semibold text-bark hover:text-sage transition-colors py-1.5">
                <i class="bi bi-file-earmark-text-fill text-sage mr-2"></i> Halaman Publik
            </a>
        </div>
    </div>

    <div class="be-card p-5">
        <h4 class="font-bold text-bark text-sm mb-4 flex items-center gap-2 uppercase tracking-wider text-bark-muted">
            <i class="bi bi-database-fill"></i> Data
        </h4>
        <div class="space-y-2">
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 text-sm font-semibold text-bark hover:text-sage transition-colors py-1.5">
                <i class="bi bi-people-fill text-sage mr-2"></i> Manajemen User
            </a>
            <a href="{{ route('admin.data.shelters') }}" class="flex items-center gap-3 text-sm font-semibold text-bark hover:text-sage transition-colors py-1.5">
                <i class="bi bi-house-heart-fill text-honey-dark mr-2"></i> Data Kandang
            </a>
            <a href="{{ route('admin.data.sugargliders') }}" class="flex items-center gap-3 text-sm font-semibold text-bark hover:text-sage transition-colors py-1.5">
                <i class="bi bi-heart-fill text-sky-500 mr-2"></i> Data Sugar Glider
            </a>
            <a href="{{ route('admin.data.collections') }}" class="flex items-center gap-3 text-sm font-semibold text-bark hover:text-sage transition-colors py-1.5">
                <i class="bi bi-collection-fill text-sage mr-2"></i> Data Penempatan
            </a>
        </div>
    </div>

    <div class="be-card p-5">
        <h4 class="font-bold text-bark text-sm mb-4 flex items-center gap-2 uppercase tracking-wider text-bark-muted">
            <i class="bi bi-star-fill"></i> Poin & Sistem
        </h4>
        <div class="space-y-2">
            <a href="{{ route('admin.points.users') }}" class="flex items-center gap-3 text-sm font-semibold text-bark hover:text-sage transition-colors py-1.5">
                <i class="bi bi-star-fill text-amber-500 mr-2"></i> Pengguna & Poin
            </a>
            <a href="{{ route('admin.points.redemptions') }}" class="flex items-center gap-3 text-sm font-semibold text-bark hover:text-sage transition-colors py-1.5">
                <i class="bi bi-gift-fill text-sage mr-2"></i> Penukaran Poin
            </a>
            <a href="{{ route('admin.points.rewards') }}" class="flex items-center gap-3 text-sm font-semibold text-bark hover:text-sage transition-colors py-1.5">
                <i class="bi bi-box-seam-fill text-honey-dark mr-2"></i> Reward Items
            </a>
            <a href="{{ route('admin.configs.site') }}" class="flex items-center gap-3 text-sm font-semibold text-bark hover:text-sage transition-colors py-1.5">
                <i class="bi bi-gear-fill text-bark-muted mr-2"></i> Sistem Konfigurasi
            </a>
        </div>
    </div>

</div>

@endsection

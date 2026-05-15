@extends('layouts.v_backend')

@section('title', 'Pedigree Sugar Glider')

@section('content')

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-xl font-bold text-bark">{{ __('text.pedigree') }} Sugar Glider</h2>
        <p class="text-bark-muted text-sm mt-0.5">{{ __('text.search_data') }}</p>
    </div>
</div>

<div class="be-card overflow-hidden">
    <div class="overflow-x-auto scrollbar-thin">
        <table class="be-table">
            <thead>
                <tr>
                    <th class="w-16">Foto</th>
                    <th>{{ __('text.name') }}</th>
                    <th class="hidden sm:table-cell">{{ __('text.code') }}</th>
                    <th class="hidden md:table-cell">{{ __('text.type') }}</th>
                    <th class="hidden lg:table-cell">{{ __('text.shelter') }}</th>
                    <th class="text-right">{{ __('text.pedigree') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($collections as $collection)
                    <tr>
                        <td>
                            <div class="w-11 h-11 rounded-xl overflow-hidden bg-sage-100">
                                @if ($collection->sgGambar)
                                    <img src="{{ asset('/upload/sugargliders/' . $collection->sgGambar) }}"
                                         class="w-full h-full object-cover" alt="">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <i class="bi bi-image text-sage/30"></i>
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="font-bold text-bark">{{ $collection->sgNama }}</td>
                        <td class="hidden sm:table-cell font-mono text-xs text-bark-muted">{{ $collection->sgKode }}</td>
                        <td class="hidden md:table-cell">
                            @if ($collection->sgJenis)
                                <span class="badge-sage">{{ $collection->sgJenis }}</span>
                            @else
                                <span class="text-bark-muted">—</span>
                            @endif
                        </td>
                        <td class="hidden lg:table-cell text-bark-light text-sm">{{ $collection->stNama }}</td>
                        <td class="text-right">
                            <a href="{{ route('pedigree.backend.show', $collection->sgId) }}"
                               class="inline-flex items-center gap-1.5 text-sage text-xs font-bold
                                      px-3 py-1.5 rounded-xl border border-sage
                                      hover:bg-sage hover:text-white transition-all duration-200">
                                <i class="bi bi-diagram-3"></i>
                                <span class="hidden sm:inline">Lihat Pedigree</span>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-16">
                            <img src="{{ asset('assets/images/mascot/glider-glide.svg') }}"
                                 class="w-16 mx-auto mb-3 opacity-30" alt="">
                            <p class="text-bark-muted font-semibold">Belum ada data koleksi.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($collections->hasPages())
        <div class="px-5 py-4 border-t border-cream-dark">
            {{ $collections->links('pagination::v_pagination') }}
        </div>
    @endif
</div>

@endsection

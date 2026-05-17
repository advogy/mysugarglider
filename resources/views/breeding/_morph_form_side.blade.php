<div class="flex flex-col h-full">
    <div class="px-5 py-4 border-b border-cream-dark flex items-center gap-2">
        <i class="bi {{ $icon }}"></i>
        <h3 class="font-ui font-bold text-bark text-sm">{{ $label }}</h3>
    </div>
    <div class="p-5 flex flex-col flex-1 gap-5">

        {{-- Morph Utama — radio (pilih satu) --}}
        <div class="flex-1">
            <p class="text-xs font-bold text-bark-muted uppercase tracking-wide mb-3">
                Morph Utama <span class="normal-case font-normal">(pilih satu yang diekspresikan)</span>
            </p>
            <div class="flex flex-wrap gap-2">
                {{-- Classic Grey = tidak ada morph utama --}}
                <label class="cursor-pointer select-none">
                    <input type="radio"
                           name="{{ $side }}_expressed"
                           value=""
                           class="sr-only peer"
                           @checked($expressed === '' || $expressed === null)>
                    <span class="inline-flex items-center px-3 py-1.5 rounded-xl border-2 border-transparent
                                 bg-gray-100 text-gray-600 opacity-50 text-xs font-bold transition-all
                                 peer-checked:opacity-100 peer-checked:border-gray-400 peer-checked:shadow-sm">
                        Classic Grey
                    </span>
                </label>

                @foreach($morphs as $key => $morph)
                    <label class="cursor-pointer select-none">
                        <input type="radio"
                               name="{{ $side }}_expressed"
                               value="{{ $key }}"
                               class="sr-only peer"
                               @checked($expressed === $key)>
                        <span class="inline-flex items-center px-3 py-1.5 rounded-xl border-2 border-transparent
                                     text-xs font-bold transition-all
                                     {{ $morph['color'] }} opacity-50
                                     peer-checked:opacity-100 peer-checked:border-current peer-checked:shadow-sm">
                            {{ $morph['label'] }}
                        </span>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- Het — checkbox (bisa banyak, hanya resesif) --}}
        <div class="border-t border-cream-dark pt-4">
            <p class="text-xs font-bold text-bark-muted uppercase tracking-wide mb-3">
                Het <span class="normal-case font-normal">(carrier — bisa pilih banyak)</span>
            </p>
            <div class="flex flex-wrap gap-2">
                @foreach($recessives as $key => $morph)
                    <label class="cursor-pointer select-none">
                        <input type="checkbox"
                               name="{{ $side }}_het[]"
                               value="{{ $key }}"
                               class="sr-only peer"
                               @checked(in_array($key, $het))>
                        <span class="inline-flex items-center px-3 py-1.5 rounded-xl border-2 border-transparent
                                     bg-amber-50 text-amber-700 opacity-40 text-xs font-bold transition-all
                                     peer-checked:opacity-100 peer-checked:border-amber-400 peer-checked:shadow-sm">
                            het {{ $morph['label'] }}
                        </span>
                    </label>
                @endforeach
            </div>
        </div>

    </div>
</div>

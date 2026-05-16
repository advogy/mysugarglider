<footer class="px-4 sm:px-6 py-4 border-t border-cream-dark flex flex-col sm:flex-row items-center justify-between gap-2">
    <p class="font-ui text-bark-muted text-xs">
        &copy; 2022–{{ date('Y') }} - v{{ config('app.version', '1.0.0') }}. Seluruh hak cipta dilindungi. Dikembangkan oleh <a href="https://github.com/advogy" target="_blank" rel="noopener" class="text-sage font-semibold hover:underline">Advogy</a>
    </p>
    <p class="font-ui text-bark-muted text-xs">
        {{ config('app.name') }}
    </p>
</footer>

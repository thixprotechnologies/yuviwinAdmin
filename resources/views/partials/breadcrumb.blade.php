<h3 class="page-title">
    @if ($pagetitle == 'Dashboard')
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-home"></i>
        </span>
    @endif
    {{ $pagetitle }}
</h3>
@stack('breadcrumb-plugins')

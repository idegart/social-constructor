<div class="card">
    <div class="card-body">
        <h5 class="card-title">
            <a href="{{ route('scripts.show', ['script' => $script->id]) }}">{{ $script->title }}</a>
        </h5>
        <p class="card-text">This card has supporting text below as a natural lead-in to additional content.</p>
        <p class="card-text"><small class="text-muted">Last updated {{ $script->updated_at->diffForHumans() }}</small></p>
    </div>

    @isset($socialChannel)
        <div class="card-footer d-flex justify-content-between">
            <remove-script-from-social-channel :channel='@json($socialChannel)' :script='@json($script)' />
        </div>
    @endisset
</div>

<div class="card">
    <div class="card-body">
        <ul>
            @foreach ($pairsList as $pair)
                <li><a href="{{ route('profile.exchange.index', [$pair]) }}">{{ $pair->getName() }}</a></li>
            @endforeach
        </ul>
    </div>
</div>

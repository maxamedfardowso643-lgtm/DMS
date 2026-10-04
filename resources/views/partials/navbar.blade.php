@php
    $u = auth()->user();
    $initials = collect(explode(' ', $u->name))->map(fn($n) => mb_substr($n, 0, 1))->take(2)->join('');
    $primaryRole = $u->roles->first()->name ?? 'User';
@endphp
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <ul class="navbar-nav ml-auto align-items-center">
        <li class="nav-item">
            <a class="nav-link theme-toggle-btn" href="#" title="Toggle dark / light mode">
                <i class="theme-toggle-icon fas fa-moon"></i>
            </a>
        </li>
        @if ($u->dentist)
            @php
                $unreadCount = $u->unreadNotifications()->count();
                $recentNotes = $u->notifications()->latest()->take(8)->get();
            @endphp
            <li class="nav-item dropdown">
                <a class="nav-link position-relative" data-bs-toggle="dropdown" href="#" title="Appointment notifications">
                    <i class="fas fa-bell"></i>
                    <span id="notif-badge" class="badge bg-danger rounded-pill position-absolute" style="top:2px;right:0;font-size:.6rem;{{ $unreadCount ? '' : 'display:none;' }}">{{ $unreadCount }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="min-width:320px;">
                    <div class="dropdown-header d-flex justify-content-between align-items-center">
                        <span>Appointments</span>
                        @if ($unreadCount)
                            <form action="{{ route('notifications.read-all') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="btn btn-link btn-sm p-0" style="font-size:.75rem;">Mark all read</button>
                            </form>
                        @endif
                    </div>
                    <div class="dropdown-divider"></div>
                    @forelse ($recentNotes as $note)
                        <a href="{{ route('notifications.open', $note->id) }}" class="dropdown-item" style="white-space:normal;{{ $note->read_at ? '' : 'background:rgba(79,70,229,.08);' }}">
                            <div style="font-weight:{{ $note->read_at ? 500 : 700 }};font-size:.85rem;">
                                <i class="fas {{ ($note->data['kind'] ?? '') === 'rescheduled' ? 'fa-calendar-alt text-warning' : 'fa-calendar-plus text-primary' }}"></i>
                                {{ ($note->data['kind'] ?? '') === 'rescheduled' ? 'Appointment rescheduled' : 'New appointment' }}
                            </div>
                            <div style="font-size:.78rem;">{{ $note->data['patient'] ?? '-' }} · {{ $note->data['service'] ?? '-' }}</div>
                            <div style="font-size:.72rem;color:var(--text-soft);">{{ $note->data['date'] ?? '' }} {{ $note->data['time'] ?? '' }} · {{ $note->created_at->diffForHumans() }}</div>
                        </a>
                    @empty
                        <div class="dropdown-item text-center" style="cursor:default;font-size:.8rem;color:var(--text-soft);">No notifications yet</div>
                    @endforelse
                </div>
            </li>
            @push('js')
            <script>
                // Keep the bell count fresh while the dentist has a page open.
                setInterval(function () {
                    $.getJSON('{{ route('notifications.count') }}', function (r) {
                        $('#notif-badge').text(r.unread).toggle(r.unread > 0);
                    });
                }, 60000);
            </script>
            @endpush
        @endif
        <li class="nav-item dropdown">
            <a class="nav-link user-menu-trigger" data-bs-toggle="dropdown" href="#">
                @if ($u->photo)
                    <span class="user-avatar user-avatar-photo"><img src="{{ $u->photoUrl() }}" alt="{{ $u->name }}"></span>
                @else
                    <span class="user-avatar">{{ $initials }}</span>
                @endif
                <span class="d-none d-md-flex flex-column align-items-start">
                    <span class="user-name">{{ $u->name }}</span>
                    <span class="user-role">{{ $primaryRole }}</span>
                </span>
                <i class="fas fa-chevron-down d-none d-md-inline" style="font-size:.65rem;color:var(--text-soft);margin-left:.2rem;"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <div class="dropdown-header">Signed in as</div>
                <div class="dropdown-item" style="cursor:default;">
                    <div>
                        <div style="font-weight:600;">{{ $u->name }}</div>
                        <div style="font-size:.75rem;color:var(--text-soft);">{{ $u->email }}</div>
                    </div>
                </div>
                <div class="dropdown-divider"></div>
                <a href="{{ route('profile.edit') }}" class="dropdown-item"><i class="fas fa-user"></i> My Profile</a>
                @if ($u->hasRole('admin'))
                    <a href="{{ route('settings.edit') }}" class="dropdown-item"><i class="fas fa-cog"></i> Settings</a>
                @endif
                <a href="{{ route('public.home') }}" target="_blank" class="dropdown-item"><i class="fas fa-globe"></i> View Site</a>
                <a href="#" class="dropdown-item theme-toggle-btn"><i class="theme-toggle-icon fas fa-moon"></i> Light / Dark Mode</a>
                <a href="https://laravel.com/docs" target="_blank" class="dropdown-item"><i class="fas fa-question-circle"></i> Help &amp; Support</a>
                <div class="dropdown-divider"></div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="dropdown-item text-danger" style="width:100%;background:none;border:none;">
                        <i class="fas fa-sign-out-alt" style="color:var(--danger);"></i> Logout
                    </button>
                </form>
            </div>
        </li>
    </ul>
</nav>

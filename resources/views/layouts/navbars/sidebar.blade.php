<div class="sidebar">
    <div class="sidebar-wrapper">
        <div class="logo">
            <a href="{{ route('home') }}" class="simple-text logo-mini">
                <img src="{{ asset('black') }}/img/white-shm-logo.png" }}>
            </a>
            <a href="{{ route('home') }}" class="simple-text logo-normal">
                {{ __('SHM Integration') }}
            </a>
        </div>
        <ul class="nav">
            <li @if ($pageSlug == 'dashboard') class="active " @endif>
                <a href="{{ route('home') }}">
                    <i class="tim-icons icon-chart-pie-36"></i>
                    <p>{{ __('Dashboard') }}</p>
                </a>
            </li>

            @if (auth()->user()->hasRole('member'))
                <li>
                    <a data-toggle="collapse" href="#member" aria-expanded="true">
                        <i class="fab fa-laravel"></i>
                        <span class="nav-link-text">{{ __('Member Tabs') }}</span>
                        <b class="caret mt-1"></b>
                    </a>

                    <div class="collapse show" id="member">
                        <ul class="nav pl-4">
                            <li @if ($pageSlug == 'tasks') class="active " @endif>
                                <a href="{{ route('task.tasks')  }}">
                                    <i class="tim-icons icon-bullet-list-67"></i>
                                    <p>{{ __('Task List') }}</p>
                                </a>
                            </li>
                            <li @if ($pageSlug == 'create') class="active " @endif>
                                <a href="{{ route('task.create')  }}">
                                    <i class="tim-icons icon-bullet-list-67"></i>
                                    <p>{{ __('Add Task') }}</p>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endif

            @if (auth()->user()->hasRole('admin'))
                <li>
                    <a data-toggle="collapse" href="#admin" aria-expanded="true">
                        <i class="fab fa-laravel"></i>
                        <span class="nav-link-text">{{ __('Admin Tabs') }}</span>
                        <b class="caret mt-1"></b>
                    </a>

                    <div class="collapse show" id="admin">
                        <ul class="nav pl-4">
                            <li @if ($pageSlug == 'user') class="active " @endif>
                                <a href="{{ route('users.create')  }}">
                                    <i class="tim-icons icon-single-02"></i>
                                    <p>{{ __('Add User') }}</p>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endif

            <li>
                <a data-toggle="collapse" href="#users" aria-expanded="true">
                    <i class="fab fa-laravel"></i>
                    <span class="nav-link-text">{{ __('Users') }}</span>
                    <b class="caret mt-1"></b>
                </a>

                <div class="collapse show" id="users">
                    <ul class="nav pl-4">
                        <li @if ($pageSlug == 'profile') class="active " @endif>
                            <a href="{{ route('profile.edit') }}">
                                <i class="tim-icons icon-single-02"></i>
                                <p>{{ __('My Profile') }}</p>
                            </a>
                        </li>
                        <li @if ($pageSlug == 'users') class="active " @endif>
                            <a href="{{ route('users.index')  }}">
                                <i class="tim-icons icon-bullet-list-67"></i>
                                <p>{{ __('All Users') }}</p>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li @if ($pageSlug == 'icons') class="active " @endif>
                <a href="{{ route('pages.icons') }}">
                    <i class="tim-icons icon-atom"></i>
                    <p>{{ __('Icons') }}</p>
                </a>
            </li>
            <li @if ($pageSlug == 'maps') class="active " @endif>
                <a href="{{ route('pages.maps') }}">
                    <i class="tim-icons icon-pin"></i>
                    <p>{{ __('Maps') }}</p>
                </a>
            </li>
            <li @if ($pageSlug == 'notifications') class="active " @endif>
                <a href="{{ route('pages.notifications') }}">
                    <i class="tim-icons icon-bell-55"></i>
                    <p>{{ __('Notifications') }}</p>
                </a>
            </li>
            <li @if ($pageSlug == 'tables') class="active " @endif>
                <a href="{{ route('pages.tables') }}">
                    <i class="tim-icons icon-puzzle-10"></i>
                    <p>{{ __('Table List') }}</p>
                </a>
            </li>
            <li @if ($pageSlug == 'typography') class="active " @endif>
                <a href="{{ route('pages.typography') }}">
                    <i class="tim-icons icon-align-center"></i>
                    <p>{{ __('Typography') }}</p>
                </a>
            </li>

        </ul>
    </div>
</div>

<header class="dtech-navbar">
            <div class="dtech-brand">
                <img src="{{ asset('assets/images/Logo.png') }}" alt="Logo D-tech" class="max-w-[120px] h-auto ml-2">
            </div>

            <div class="flex items-center gap-6">
                <span class="text-sm font-medium text-gray-700">
                    Olá, <strong class="text-[#7C3AED]">{{ Auth::user()->name ?? 'Usuário' }}</strong>
                </span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-xs text-gray-500 hover:text-[#7C3AED] font-semibold transition-colors">
                        {{ __('Sair') }}
                    </button>
                </form>
            </div>
        </header>
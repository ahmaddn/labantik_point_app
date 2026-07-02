<!DOCTYPE html>
<html lang="en" class="light scroll-smooth group" data-layout="vertical" data-sidebar="light" data-sidebar-size="lg"
    data-mode="light" data-topbar="light" data-skin="default" data-navbar="sticky" data-content="fluid" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>Pilih Peran | Point App</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">
    <!-- Layout config Js -->
    <script src="{{ asset('assets/js/layout.js') }}"></script>
    <!-- StarCode CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/starcode2.css') }}">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body
    class="flex items-center justify-center min-h-screen py-16 lg:py-10 bg-slate-50 dark:bg-zink-800 dark:text-zink-100 font-public">

    <div class="relative">
        <div class="mb-0 w-screen lg:mx-auto lg:w-[500px] card shadow-lg border-none shadow-slate-100 relative">
            <div class="!px-10 !py-12 card-body">
                <div class="text-center mb-8">
                    <h4 class="mb-1 text-custom-500 dark:text-custom-500">Pilih Peran Anda</h4>
                    <p class="text-slate-500 dark:text-zink-200">Anda memiliki beberapa peran. Silakan pilih salah satu untuk melanjutkan.</p>
                </div>

                @if (session('success'))
                    <div class="px-4 py-3 mb-3 text-sm text-green-500 border border-green-200 rounded-md bg-green-50">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="px-4 py-3 mb-3 text-sm text-red-500 border border-red-200 rounded-md bg-red-50">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('role.store') }}" method="POST" class="mt-5 space-y-4">
                    @csrf
                    
                    @foreach($roles as $role)
                        <button type="submit" name="role_code" value="{{ $role->code }}" 
                            class="w-full text-left flex items-center justify-between p-4 border border-slate-200 rounded-lg hover:border-custom-500 hover:bg-custom-50 transition-all dark:border-zink-500 dark:hover:bg-zink-700">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-custom-100 text-custom-600 rounded-md">
                                    @if(strtolower($role->code) == 'guru-bk' || strtolower($role->code) == 'kesiswaan')
                                        <i data-lucide="shield"></i>
                                    @elseif(strtolower($role->code) == 'guru')
                                        <i data-lucide="book-open"></i>
                                    @else
                                        <i data-lucide="user"></i>
                                    @endif
                                </div>
                                <div>
                                    <h6 class="text-15 font-bold">{{ $role->name ?? strtoupper($role->code) }}</h6>
                                    <p class="text-13 text-slate-500">Masuk sebagai {{ $role->name ?? strtolower($role->code) }}</p>
                                </div>
                            </div>
                            <i data-lucide="chevron-right" class="text-slate-400"></i>
                        </button>
                    @endforeach
                </form>

                <div class="mt-8 text-center">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-custom-500 hover:underline">Batal dan Kembali (Logout)</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        lucide.createIcons();
    </script>
</body>
</html>

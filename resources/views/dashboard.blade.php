<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ISG Website Monitoring</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 p-8 min-h-screen">
    <div class="max-w-8xl mx-auto">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6 gap-4">
            <div class="flex items-center space-x-4">
                <img src="{{ asset('CWF_PN_LOGO.png') }}" alt="Web Logo" class="h-12 w-12 object-contain rounded">
                <h1 class="text-3xl font-bold text-gray-800">Website Monitoring Dashboard</h1>
            </div>
            
            <form action="{{ route('websites.pingAll') }}" method="POST">
                @csrf
                <button type="submit" class="bg-indigo-600 text-white px-5 py-2.5 rounded-lg shadow-md hover:bg-indigo-700 transition flex items-center space-x-2 font-semibold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    <span>Ping Now</span>
                </button>
            </form>
        </div>

        <!-- Navigation Menu -->
        <nav class="mb-8 bg-white p-2 rounded-xl shadow-sm border border-gray-100 flex flex-wrap gap-2 items-center">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 px-4 py-2.5 rounded-lg font-semibold text-blue-700 bg-blue-50 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('websites.index') }}" class="flex items-center space-x-2 px-4 py-2.5 rounded-lg font-medium text-gray-600 hover:bg-gray-50 hover:text-blue-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                <span>Manage Websites</span>
            </a>
            <a href="{{ route('vapt.index') }}" class="flex items-center space-x-2 px-4 py-2.5 rounded-lg font-medium text-gray-600 hover:bg-gray-50 hover:text-blue-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                <span>VAPT Status</span>
            </a>
            <a href="{{ route('archives.index') }}" class="flex items-center space-x-2 px-4 py-2.5 rounded-lg font-medium text-gray-600 hover:bg-gray-50 hover:text-blue-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                <span>Archives</span>
            </a>
        </nav>

        <!-- Summary Stats -->
        <div class="flex flex-col sm:flex-row justify-center gap-8 mb-8 mt-4">
            <div class="w-full sm:w-72 bg-white p-5 rounded-lg shadow-md border-l-4 border-green-500 flex items-center justify-between transition hover:shadow-lg">
                <div>
                    <p class="text-sm text-gray-500 font-semibold uppercase tracking-wide">Total UP</p>
                    <h2 class="text-4xl font-extrabold text-gray-800 mt-1">{{ $upCount }}</h2>
                </div>
                <div class="p-4 bg-green-100 rounded-full text-green-600"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg></div>
            </div>
            <div class="w-full sm:w-72 bg-white p-5 rounded-lg shadow-md border-l-4 border-red-500 flex items-center justify-between transition hover:shadow-lg">
                <div>
                    <p class="text-sm text-gray-500 font-semibold uppercase tracking-wide">Total DOWN</p>
                    <h2 class="text-4xl font-extrabold text-gray-800 mt-1">{{ $downCount }}</h2>
                </div>
                <div class="p-4 bg-red-100 rounded-full text-red-600"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg></div>
            </div>
        </div>

        <!-- Monitoring Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($websites as $site)
                <div class="p-6 rounded-lg shadow-md bg-white border-l-4 flex flex-col justify-between transition hover:shadow-lg
                    {{ $site->status === 'UP' ? 'border-green-500' : ($site->status === 'DOWN' ? 'border-red-500' : 'border-gray-400') }}">
                    
                    <div class="flex items-center space-x-4 mb-4">
                        <img src="{{ route('media.websiteLogo', $site) }}" alt="Logo" class="h-12 w-12 rounded-full object-cover border border-gray-200 flex-shrink-0">
                        <div class="overflow-hidden">
                            <h2 class="text-xl font-semibold text-gray-800 truncate">{{ $site->name }}</h2>
                            <a href="{{ $site->url }}" target="_blank" class="text-blue-500 hover:underline text-sm block truncate">{{ $site->url }}</a>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between mt-2 pt-4 border-t border-gray-100">
                        <div class="flex flex-col space-y-1">
                            <span class="text-xs text-gray-500">Last checked: {{ $site->updated_at->diffForHumans() }}</span>
                            <div class="flex items-center space-x-2">
                                <span class="text-xs font-medium flex items-center {{ $site->response_time && $site->response_time < 500 ? 'text-green-600' : 'text-red-500' }}">
                                    {{ $site->response_time ? $site->response_time . ' ms' : 'Offline' }}
                                </span>
                                <span class="text-xs font-bold px-1.5 py-0.5 rounded bg-gray-200 text-gray-700">
                                    {{ $site->http_status ?? 'N/A' }}
                                </span>
                            </div>
                        </div>
                        <span class="px-3 py-1 text-sm font-bold rounded-full tracking-wide {{ $site->status === 'UP' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $site->status }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center text-gray-500">No websites monitored.</div>
            @endforelse
        </div>
    </div>
    
    <!-- Auto-ping and Refresh Script -->
    <script>
        window.addEventListener('load', function() {
            fetch("{{ route('websites.autoPing') }}", { method: "POST", headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" } })
            .then(() => window.location.reload());
        });
        setTimeout(() => window.location.reload(), 10000);
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archived Websites - Website Monitor</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 p-8 min-h-screen">
    <div class="max-w-8xl mx-auto">
        
        <div class="flex items-center space-x-4 mb-6">
            <!-- SECURE SYSTEM LOGO -->
            <img src="{{ asset('CWF_PN_LOGO.png') }}" alt="Web Logo" class="h-12 w-12 object-contain rounded">
            <h1 class="text-3xl font-bold text-gray-800">Archived Websites</h1>
        </div>
        
        <!-- Enhanced Navigation Menu -->
        <nav class="mb-8 bg-white p-2 rounded-xl shadow-sm border border-gray-100 flex flex-wrap gap-2 items-center">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 px-4 py-2.5 rounded-lg font-medium text-gray-600 hover:bg-gray-50 hover:text-blue-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('websites.index') }}" class="flex items-center space-x-2 px-4 py-2.5 rounded-lg font-medium text-gray-600 hover:bg-gray-50 hover:text-blue-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                <span>Manage Websites</span>
            </a>
            <!-- Active State -->
            <a href="{{ route('archives.index') }}" class="flex items-center space-x-2 px-4 py-2.5 rounded-lg font-semibold text-blue-700 bg-blue-50 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                <span>Archives</span>
            </a>
        </nav>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="min-w-full text-left border-collapse">
                <thead class="bg-gray-800 text-gray-300">
                    <tr>
                        <th class="py-4 px-6 font-semibold text-sm uppercase tracking-wider">Website Name</th>
                        <th class="py-4 px-6 font-semibold text-sm uppercase tracking-wider">URL</th>
                        <th class="py-4 px-6 font-semibold text-sm uppercase tracking-wider">Last Status</th>
                        <th class="py-4 px-6 font-semibold text-sm uppercase tracking-wider">Archived On</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-gray-50">
                    @forelse($websites as $site)
                        <tr class="hover:bg-gray-100 transition duration-150 text-gray-500">
                            <td class="py-4 px-6 font-medium line-through decoration-gray-300">{{ $site->name }}</td>
                            <td class="py-4 px-6">{{ $site->url }}</td>
                            <td class="py-4 px-6">
                                <span class="px-2 py-1 bg-gray-200 text-gray-600 text-xs rounded border border-gray-300">
                                    {{ $site->status }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-sm">
                                {{ $site->updated_at->format('M d, Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-gray-500 bg-white">
                                You have no archived websites.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
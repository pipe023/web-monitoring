<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Websites - Website Monitor</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 p-8 min-h-screen">
    <div class="max-w-8xl mx-auto">
        
        <div class="flex items-center space-x-4 mb-6">
            <!-- SECURE SYSTEM LOGO -->
            <img src="{{ asset('CWF_PN_LOGO.png') }}" alt="Web Logo" class="h-12 w-12 object-contain rounded">
            <h1 class="text-3xl font-bold text-gray-800">Manage Websites</h1>
        </div>
        
        <!-- Enhanced Navigation Menu -->
        <nav class="mb-8 bg-white p-2 rounded-xl shadow-sm border border-gray-100 flex flex-wrap justify-between items-center gap-2">
            <div class="flex flex-wrap gap-2 items-center">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 px-4 py-2.5 rounded-lg font-medium text-gray-600 hover:bg-gray-50 hover:text-blue-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span>Dashboard</span>
                </a>
                <!-- Active State -->
                <a href="{{ route('websites.index') }}" class="flex items-center space-x-2 px-4 py-2.5 rounded-lg font-semibold text-blue-700 bg-blue-50 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                    <span>Manage Websites</span>
                </a>
                <a href="{{ route('archives.index') }}" class="flex items-center space-x-2 px-4 py-2.5 rounded-lg font-medium text-gray-600 hover:bg-gray-50 hover:text-blue-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                    <span>Archives</span>
                </a>
            </div>
            <a href="{{ route('websites.create') }}" class="bg-blue-600 text-white px-4 py-2.5 rounded-lg shadow-sm hover:bg-blue-700 transition font-semibold">
                + Add Website
            </a>
        </nav>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-lg border border-green-200 shadow-sm flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="min-w-full text-left border-collapse">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="py-4 px-6 font-semibold text-sm uppercase">Logo</th>
                        <th class="py-4 px-6 font-semibold text-sm uppercase">Name</th>
                        <th class="py-4 px-6 font-semibold text-sm uppercase">URL</th>
                        <th class="py-4 px-6 font-semibold text-sm uppercase text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($websites as $site)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="py-4 px-6">
                                @if($site->logo)
                                <!-- Updated Image Tag for perfect centering and resizing -->
                                    <div class="h-10 w-10 rounded-full overflow-hidden border border-gray-200">
                                    <img src="{{ route('media.websiteLogo', $site) }}" 
                 alt="Logo" 
                 class="h-full w-full object-cover">
        </div>
    @else
        <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold border border-gray-300">
            {{ strtoupper(substr($site->name, 0, 1)) }}
        </div>
    @endif
                            </td>
                            <td class="py-4 px-6 text-gray-800 font-medium">{{ $site->name }}</td>
                            <td class="py-4 px-6 text-blue-600 hover:underline">
                                <a href="{{ $site->url }}" target="_blank">{{ $site->url }}</a>
                            </td>
                            <td class="py-4 px-6 flex justify-center space-x-2">
                                <a href="{{ route('websites.edit', $site) }}" class="bg-yellow-500 text-white px-3 py-1.5 rounded text-sm hover:bg-yellow-600 transition shadow-sm">Edit</a>
                                <form action="{{ route('websites.archive', $site) }}" method="POST" onsubmit="return confirm('Archive this website?');">
                                    @csrf
                                    <button type="submit" class="bg-gray-500 text-white px-3 py-1.5 rounded text-sm hover:bg-gray-600 transition shadow-sm">Archive</button>
                                </form>
                                <form action="{{ route('websites.destroy', $site) }}" method="POST" onsubmit="return confirm('Permanently delete this website?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white px-3 py-1.5 rounded text-sm hover:bg-red-600 transition shadow-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-gray-500">
                                No websites found. Click "Add Website" to get started.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
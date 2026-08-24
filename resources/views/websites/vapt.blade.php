<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VAPT Status - Website Monitor</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 p-8 min-h-screen">
    <div class="max-w-8xl mx-auto">
        
        <div class="flex items-center space-x-4 mb-6">
            <!-- SECURE SYSTEM LOGO -->
            <img src="{{ asset('CWF_PN_LOGO.png') }}" alt="Web Logo" class="h-12 w-12 object-contain rounded">
            <h1 class="text-3xl font-bold text-gray-800">VAPT Status Tracking</h1>
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
            <a href="{{ route('archives.index') }}" class="flex items-center space-x-2 px-4 py-2.5 rounded-lg font-medium text-gray-600 hover:bg-gray-50 hover:text-blue-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                <span>Archives</span>
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
                        <th class="py-4 px-6 font-semibold text-sm uppercase tracking-wider">Website Name</th>
                        <th class="py-4 px-6 font-semibold text-sm uppercase tracking-wider">URL</th>
                        <th class="py-4 px-6 font-semibold text-sm uppercase tracking-wider">VAPT Status</th>
                        <th class="py-4 px-6 font-semibold text-sm uppercase tracking-wider">Last Updated</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($websites as $site)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="py-4 px-6 text-gray-800 font-medium">{{ $site->name }}</td>
                            <td class="py-4 px-6 text-blue-600 hover:underline">
                                <a href="{{ $site->url }}" target="_blank">{{ $site->url }}</a>
                            </td>
                            <td class="py-4 px-6">
                                <form action="{{ route('websites.updateVapt', $site) }}" method="POST" class="flex items-center space-x-2">
                                    @csrf
                                    @method('PATCH')
                                    
                                    <select name="vapt_status" class="shadow-sm border rounded-full py-1.5 px-3 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer
                                        {{ $site->vapt_status === 'Passed' ? 'bg-green-100 text-green-700 border-green-200' : '' }}
                                        {{ $site->vapt_status === 'Failed' ? 'bg-red-100 text-red-700 border-red-200' : '' }}
                                        {{ $site->vapt_status === 'In Progress' ? 'bg-yellow-100 text-yellow-700 border-yellow-200' : '' }}
                                        {{ $site->vapt_status === 'For Patching' ? 'bg-purple-100 text-purple-700 border-purple-200' : '' }}
                                        {{ $site->vapt_status === 'Pending' ? 'bg-gray-100 text-gray-700 border-gray-200' : '' }}">
                                        
                                        <option value="Pending" {{ $site->vapt_status === 'Pending' ? 'selected' : '' }} class="bg-white text-gray-800">Pending</option>
                                        <option value="In Progress" {{ $site->vapt_status === 'In Progress' ? 'selected' : '' }} class="bg-white text-gray-800">In Progress</option>
                                        <option value="For Patching" {{ $site->vapt_status === 'For Patching' ? 'selected' : '' }} class="bg-white text-gray-800">For Patching</option>
                                        <option value="Passed" {{ $site->vapt_status === 'Passed' ? 'selected' : '' }} class="bg-white text-gray-800">Passed</option>
                                        <option value="Failed" {{ $site->vapt_status === 'Failed' ? 'selected' : '' }} class="bg-white text-gray-800">Failed</option>
                                    </select>

                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg text-sm transition shadow-sm">
                                        Update
                                    </button>
                                </form>
                            </td>
                            <td class="py-4 px-6 text-sm text-gray-500">
                                {{ $site->updated_at->format('M d, Y H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-gray-500">
                                No active websites found. Add some to track their VAPT status.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
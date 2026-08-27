<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Website - Website Monitor</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 p-8 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">Edit Website</h1>
        
        <form action="{{ route('websites.update', $website) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Website Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $website->name) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                @error('name') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="monitor_method">Monitor By</label>
                <select name="monitor_method" id="monitor_method" class="shadow border rounded w-full py-2 px-3 text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="url" {{ old('monitor_method', $website->monitor_method ?? 'url') === 'url' ? 'selected' : '' }}>URL</option>
                    <option value="ping" {{ old('monitor_method', $website->monitor_method ?? 'url') === 'ping' ? 'selected' : '' }}>IP</option>
                </select>
                @error('monitor_method') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4" id="url-field">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="url">URL</label>
                <input type="url" name="url" id="url" value="{{ old('url', $website->url) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 @error('url') border-red-500 @enderror">
                @error('url') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4 hidden" id="ping-ip-field">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="ping_ip">IP Address</label>
                <input type="text" name="ping_ip" id="ping_ip" value="{{ old('ping_ip', $website->ping_ip) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 @error('ping_ip') border-red-500 @enderror" placeholder="e.g., 192.0.2.10">
                <p class="text-xs text-gray-500 mt-1">Required when IP monitoring is selected.</p>
                @error('ping_ip') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="logo">Website Logo</label>
                
                @if($website->logo)
                    <div class="mb-3 flex items-center space-x-3">
                        <!-- SECURE WEBSITE LOGO -->
                        <img src="{{ route('media.websiteLogo', $website) }}" alt="Current Logo" class="h-16 w-16 object-cover rounded-full border shadow-sm">
                        <span class="text-sm text-gray-500">Current Logo</span>
                    </div>
                @endif

                <input type="file" name="logo" id="logo" accept="image/*" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-gray-500 mt-1">Leave blank to keep the current logo.</p>
                @error('logo') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition">
                    Update Website
                </button>
                <a href="{{ route('websites.index') }}" class="inline-block align-baseline font-bold text-sm text-gray-500 hover:text-gray-800">
                    Cancel
                </a>
            </div>
        </form>
    </div>
    <script>
        const monitorMethod = document.getElementById('monitor_method');
        const urlField = document.getElementById('url-field');
        const urlInput = document.getElementById('url');
        const pingIpField = document.getElementById('ping-ip-field');
        const pingIpInput = document.getElementById('ping_ip');

        function updateMonitoringFields() {
            const isIpMonitoring = monitorMethod.value === 'ping';
            urlField.classList.toggle('hidden', isIpMonitoring);
            urlInput.disabled = isIpMonitoring;
            pingIpField.classList.toggle('hidden', !isIpMonitoring);
            pingIpInput.required = isIpMonitoring;
            pingIpInput.disabled = !isIpMonitoring;

            if (isIpMonitoring) {
                urlInput.value = '';
            }
        }

        monitorMethod.addEventListener('change', updateMonitoringFields);
        updateMonitoringFields();
    </script>
</body>
</html>
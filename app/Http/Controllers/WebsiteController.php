<?php

namespace App\Http\Controllers;

use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;

class WebsiteController extends Controller
{
    public function dashboard()
    {
        // Get all unarchived websites
        $websites = Website::where('is_archived', false)->get();

        // Count the statuses
        $upCount = $websites->where('status', 'UP')->count();
        $downCount = $websites->where('status', 'DOWN')->count();

        // Pass everything to the view
        return view('dashboard', compact('websites', 'upCount', 'downCount'));
    }

    public function index()
    {
        $websites = Website::where('is_archived', false)->get();

        return view('websites.index', compact('websites'));
    }

    public function create()
    {
        return view('websites.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'url' => 'required_if:monitor_method,url|nullable|url',
            'monitor_method' => 'required|in:url,ping',
            'ping_ip' => 'required_if:monitor_method,ping|nullable|ip',
        ]);
        Website::create($validated);

        return redirect()->route('websites.index')->with('success', 'Website added successfully.');
    }

    public function edit(Website $website)
    {
        return view('websites.edit', compact('website'));
    }

    public function update(Request $request, Website $website)
    {
        $validated = $request->validate([
            'name' => 'required',
            'url' => 'required_if:monitor_method,url|nullable|url',
            'monitor_method' => 'required|in:url,ping',
            'ping_ip' => 'required_if:monitor_method,ping|nullable|ip',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5000',
        ]);

        // Handle the file upload properly
        if ($request->hasFile('logo')) {
            // Delete the old logo from storage if one exists
            if ($website->logo) {
                Storage::disk('public')->delete($website->logo);
            }
            // Save the new file and store the generated path
            $validated['logo'] = $request->file('logo')->store('logos', 'public');
        }

        // THIS IS THE CRITICAL LINE FOR EDITING:
        // It applies the new data to the specific website being edited
        $website->update($validated);

        return redirect()->route('websites.index')->with('success', 'Website updated successfully.');
    }

    public function destroy(Website $website)
    {
        $website->delete();

        return redirect()->route('websites.index');
    }

    public function vapt()
    {
        $websites = Website::where('is_archived', false)->get();

        return view('websites.vapt', compact('websites'));
    }

    public function updateVapt(Request $request, Website $website)
    {
        // Add 'For Patching' to the validation rule
        $request->validate([
            'vapt_status' => 'required|in:Pending,In Progress,Passed,Failed,For Patching',
        ]);

        $website->update([
            'vapt_status' => $request->vapt_status,
        ]);

        return redirect()->back()->with('success', "VAPT status for {$website->name} updated successfully.");
    }

    public function archives()
    {
        $websites = Website::where('is_archived', true)->get();

        return view('websites.archives', compact('websites'));
    }

    public function archive(Website $website)
    {
        $website->update(['is_archived' => true]);

        return redirect()->route('websites.index');
    }

    public function pingAll()
    {
        $websites = Website::where('is_archived', false)->get();

        foreach ($websites as $site) {
            try {
                if ($site->monitor_method === 'url') {
                    $httpStartTime = microtime(true);
                    $response = Http::timeout(10)->get($site->url);
                    $httpResponseTime = round((microtime(true) - $httpStartTime) * 1000);
                    $statusCode = $response->status();
                    $isUp = $response->successful() || ($statusCode >= 300 && $statusCode < 400);

                    $site->update([
                        'status' => $isUp ? 'UP' : 'DOWN',
                        'response_time' => $isUp ? $httpResponseTime : null,
                        'http_status' => $statusCode,
                    ]);

                    continue;
                }

                $host = $site->ping_ip ?: parse_url($site->url, PHP_URL_HOST);

                if (! is_string($host) || $host === '') {
                    throw new \RuntimeException('The website URL does not contain a valid host.');
                }

                $startTime = microtime(true);
                $arguments = PHP_OS_FAMILY === 'Windows'
                    ? ['ping', '-n', '1', '-w', '5000', $host]
                    : ['ping', '-c', '1', '-W', '5', $host];
                $result = Process::timeout(6)->run($arguments);
                $responseTime = round((microtime(true) - $startTime) * 1000);

                if ($result->successful()) {
                    $site->update([
                        'status' => 'UP',
                        'response_time' => $responseTime,
                        'http_status' => null,
                    ]);
                }
            } catch (\Exception $e) {
                $site->update([
                    'status' => 'DOWN',
                    'response_time' => null,
                    'http_status' => null,
                ]);
            }
        }

        return redirect()->route('dashboard')->with('success', 'All websites have been pinged.');
    }

    // AUTO-PING
    public function autoPing()
    {
        // Reuse your existing ping logic here
        $this->pingAll();

        return response()->json(['message' => 'Ping complete']);
    }

    // Serve the system logo without exposing the public folder path
    public function serveSystemLogo()
    {
        $path = public_path('system-logo.png');

        if (! file_exists($path)) {
            abort(404);
        }

        return response()->file($path);
    }

    // Serve website logos using the website's ID instead of the file path
    public function serveWebsiteLogo(Website $website)
    {
        if (! $website->logo || ! Storage::disk('public')->exists($website->logo)) {
            abort(404);
        }

        $fullPath = storage_path('app/public/'.$website->logo);

        return response()->file($fullPath);
    }
}

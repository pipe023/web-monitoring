<?php

// namespace App\Console\Commands;

// use Illuminate\Console\Command;
// use App\Models\Website;
// use Illuminate\Support\Facades\Http;

// class MonitorWebsites extends Command
// {
//     protected $signature = 'monitor:websites';
//     protected $description = 'Ping websites to check if they are UP or DOWN';

//     public function handle()
//     {
//         $websites = Website::where('is_archived', false)->get();

//         foreach ($websites as $site) {
//             try {
//                 $response = Http::timeout(5)->get($site->url);
//                 $status = $response->successful() ? 'UP' : 'DOWN';
//             } catch (\Exception $e) {
//                 $status = 'DOWN';
//             }

//             $site->update(['status' => $status]);
//             $this->info("Checked {$site->name}: {$status}");
//         }
//     }
// }

use Illuminate\Support\Facades\Http;

// Assuming you are looping through your websites like: foreach($websites as $website)

try {
    // Ping the URL with a strict 5-second timeout so a dead site doesn't freeze the loop
    $response = Http::timeout(5)->head($website->url);

    // successful() checks if the status code is between 200 and 299
    if ($response->successful()) {
        $website->update(['status' => 'UP']);
    } else {
        // Fails if the site returns 404, 500, 502, etc.
        $website->update(['status' => 'DOWN']);
    }
} catch (\Exception $e) {
    // Catches timeouts, SSL errors, or invalid URLs
    $website->update(['status' => 'DOWN']);
}
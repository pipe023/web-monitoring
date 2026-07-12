<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Website;
use Illuminate\Support\Facades\Http;

class MonitorWebsites extends Command
{
    protected $signature = 'monitor:websites';
    protected $description = 'Ping websites to check if they are UP or DOWN';

    public function handle()
    {
        $websites = Website::where('is_archived', false)->get();

        foreach ($websites as $site) {
            try {
                $response = Http::timeout(5)->get($site->url);
                $status = $response->successful() ? 'UP' : 'DOWN';
            } catch (\Exception $e) {
                $status = 'DOWN';
            }

            $site->update(['status' => $status]);
            $this->info("Checked {$site->name}: {$status}");
        }
    }
}
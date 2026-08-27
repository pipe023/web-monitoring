<?php

use App\Models\Website;
use Illuminate\Support\Facades\Process;

it('checks website hosts with ICMP ping', function () {
    Process::fake();

    $website = Website::create([
        'name' => 'Monitor example',
        'url' => 'https://monitor.example.test/health',
        'status' => 'DOWN',
        'is_archived' => false,
        'http_status' => 503,
    ]);

    $this->post(route('websites.pingAll'))->assertOk();

    expect($website->refresh()->status)->toBe('UP')
        ->and($website->response_time)->toBeInt()
        ->and($website->http_status)->toBeNull();

    Process::assertRan(function ($process) {
        return $process->command[0] === 'ping'
            && in_array('monitor.example.test', $process->command, true);
    });
});

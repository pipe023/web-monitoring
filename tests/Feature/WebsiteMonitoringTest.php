<?php

use App\Models\Website;
use Illuminate\Support\Facades\Http;
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

    $this->post(route('websites.pingAll'))
        ->assertRedirect(route('dashboard'))
        ->assertSessionHas('success', 'All websites have been pinged.');

    expect($website->refresh()->status)->toBe('UP')
        ->and($website->response_time)->toBeInt()
        ->and($website->http_status)->toBeNull();

    Process::assertRan(function ($process) {
        return $process->command[0] === 'ping'
            && in_array('monitor.example.test', $process->command, true);
    });
});

it('uses the website when ICMP is blocked', function () {
    Process::fake(fn () => Process::result('', 'Request timed out', 1));
    Http::fake([
        'https://blocked-icmp.example.test/*' => Http::response('OK', 200),
    ]);

    $website = Website::create([
        'name' => 'Blocked ICMP example',
        'url' => 'https://blocked-icmp.example.test/health',
        'status' => 'DOWN',
        'is_archived' => false,
    ]);

    $this->post(route('websites.pingAll'))->assertRedirect(route('dashboard'));

    expect($website->refresh()->status)->toBe('UP')
        ->and($website->response_time)->toBeInt()
        ->and($website->http_status)->toBe(200);
});

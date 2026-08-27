<?php

use App\Models\Website;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Process;

it('checks website hosts with ICMP ping', function () {
    Process::fake();

    $website = Website::create([
        'name' => 'Monitor example',
        'url' => 'https://monitor.example.test/health',
        'monitor_method' => 'ping',
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

it('keeps a Ping monitored website down when ICMP is blocked', function () {
    Process::fake(fn () => Process::result('', 'Request timed out', 1));
    Http::fake([
        'https://blocked-icmp.example.test/*' => Http::response('OK', 200),
    ]);

    $website = Website::create([
        'name' => 'Blocked ICMP example',
        'url' => 'https://blocked-icmp.example.test/health',
        'monitor_method' => 'ping',
        'status' => 'DOWN',
        'is_archived' => false,
    ]);

    $this->post(route('websites.pingAll'))->assertRedirect(route('dashboard'));

    expect($website->refresh()->status)->toBe('DOWN')
        ->and($website->response_time)->toBeNull()
        ->and($website->http_status)->toBeNull();

    Http::assertNothingSent();
});

it('uses the configured ping IP instead of the URL host', function () {
    Process::fake();

    $website = Website::create([
        'name' => 'IP monitored example',
        'url' => 'https://private.example.test/health',
        'ping_ip' => '192.0.2.10',
        'monitor_method' => 'ping',
        'status' => 'DOWN',
        'is_archived' => false,
    ]);

    $this->post(route('websites.pingAll'))->assertRedirect(route('dashboard'));

    expect($website->refresh()->status)->toBe('UP');

    Process::assertRan(function ($process) {
        return in_array('192.0.2.10', $process->command, true)
            && ! in_array('private.example.test', $process->command, true);
    });
});

it('uses the URL when URL monitoring is selected', function () {
    Process::fake();
    Http::fake([
        'https://url-monitor.example.test/*' => Http::response('OK', 200),
    ]);

    $website = Website::create([
        'name' => 'URL monitored example',
        'url' => 'https://url-monitor.example.test/health',
        'monitor_method' => 'url',
        'ping_ip' => '192.0.2.11',
        'status' => 'DOWN',
        'is_archived' => false,
    ]);

    $this->post(route('websites.pingAll'))->assertRedirect(route('dashboard'));

    expect($website->refresh()->status)->toBe('UP')
        ->and($website->http_status)->toBe(200);

    Process::assertNotRan('ping');
});

it('can create a website monitored only by IP', function () {
    $this->post(route('websites.store'), [
        'name' => 'IP only example',
        'monitor_method' => 'ping',
        'ping_ip' => '172.16.7.97',
    ])->assertRedirect(route('websites.index'));

    $website = Website::where('name', 'IP only example')->first();

    expect($website)->not->toBeNull()
        ->and($website->url)->toBeNull()
        ->and($website->monitor_method)->toBe('ping')
        ->and($website->ping_ip)->toBe('172.16.7.97');
});

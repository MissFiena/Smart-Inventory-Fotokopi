<?php
use Illuminate\Support\Facades\Schedule;

// Run every day at 8am
Schedule::command('inventory:check-expiry')->dailyAt('08:00');

// Test it manually anytime with:
// php artisan inventory:check-expiry
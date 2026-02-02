<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Account;
use App\Models\AccountingPeriod;
use App\Models\Journal;
use App\Policies\AccountPolicy;
use App\Policies\PeriodPolicy;
use App\Policies\JournalPolicy;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        require_once app_path('Support/helpers.php');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::setLocale(config('app.locale'));
        Gate::policy(Account::class, AccountPolicy::class);
        Gate::policy(AccountingPeriod::class, PeriodPolicy::class);
        Gate::policy(Journal::class, JournalPolicy::class);
    }
}

<?php

namespace App\Console\Commands;

use App\Models\Customer;
use Illuminate\Console\Command;

class SubscriptionExpiryNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:subscription-expiry-notification';

    protected $description = 'Command description';

    public function handle()
    {
		$customers = Customer::all();
		foreach ($customers as $key => $customer) {
			$expiryDate = $customer->subscription_end_date;
			
		}
    }
}

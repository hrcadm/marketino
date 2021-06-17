<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Jobs\CreateNextStep;
use App\Models\Customer;


class NextStepTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testJobCreateNextsStep()
    {
    	$this->expectsJobs(CreateNextStep::class);

    	$customer = Customer::first();
    	$data = [
    		'name'	=> 'Mark',
    		'date'	=>	\Carbon\Carbon::today()
    	];
        $jobAfter3Day = CreateNextStep::dispatch([
                'customer_id'	=>  $customer->id,
                'date'			=>  \Carbon\Carbon::now(),
                'comment'		=>  'Grenke ponude',
                'data'			=>	$data
            ]);
    }
}

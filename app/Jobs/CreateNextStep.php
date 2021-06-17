<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\NextStep;
use App\Models\Quote;
class CreateNextStep implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $quote = 0;
        $resolvedNextSteps = 0;
        if(isset($this->data['quote_id'])) {
            $quote = Quote::find($this->data['quote_id']);
        }
        if($quote) {
            $resolvedNextSteps = NextStep::where('quote_id', $quote->id)->where('customer_id', $this->data['customer_id'])->where('resolved', true)->where('created_at', '>', $quote->created_at)->count();
        }
        if(! $resolvedNextSteps) {
            $nextStep = new NextStep;
            $nextStep->customer_id = $this->data['customer_id'];
            $nextStep->user_id = $this->data['user_id'] ?? NULL;
            $nextStep->date = $this->data['date'];
            $nextStep->comment = $this->data['comment'];
            if(isset($this->data['data']))
                $nextStep->data = is_array($this->data['data']) ? http_build_query($this->data['data'],'',', ') : $this->data['data'];
            $nextStep->quote_id = $this->data['quote_id'] ?? NULL;
            $nextStep->save();
        }

    }
}

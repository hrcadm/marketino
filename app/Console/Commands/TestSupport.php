<?php

namespace App\Console\Commands;

use App\Mail\SimpleMessage;
use App\Models\SupportTicket;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class TestSupport extends Command
{
    private const MAX_WAIT_SECONDS = 20;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:support';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test if support emails are piping through Mailgun';

    /**
     * Random identifier
     *
     * @var string
     */
    private $uuid;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->uuid = \Str::uuid()->toString();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $maxWaitSeconds = self::MAX_WAIT_SECONDS;
        $this->sendSupportTicketEmail();
        $this->line("Email sent, waiting {$maxWaitSeconds}s for Mailgun forward");

        $bar = $this->output->createProgressBar($maxWaitSeconds);
        $bar->start();

        $waitingSeconds = 0;
        while (!$this->supportTicketCreated()) {
            sleep(1);
            $waitingSeconds += 1;
            $bar->advance();

            if ($waitingSeconds >= $maxWaitSeconds) {
                $this->error("\nSupport ticket did not arrive.");

                return 0;
            }
        }

        $this->deleteSupportTicket();
        $this->info("\nSupport ticket arrived successfully.");

        return 0;
    }

    /* HELPER */
    /**
     * Send out the email which should be caught and forwarded with Mailgun
     */
    private function sendSupportTicketEmail(): void
    {
        $supportEmail = env('SUPPORT_EMAIL');
        if (!$supportEmail){
            $this->error('Environment variable [SUPPORT_EMAIL] is not set!');
            exit(0);
        }

        \Mail::to($supportEmail)
             ->send(with(new SimpleMessage("Testing support messages"))->subject($this->uuid));
    }

    /**
     * Check if support ticket has been created
     *
     * @return bool
     */
    private function supportTicketCreated(): bool
    {
        return $this->supportTicketQueryByUuid()->exists();
    }

    /**
     * Delete the support ticket
     */
    private function deleteSupportTicket(): void
    {
        try {
            $this->supportTicketQueryByUuid()->delete();
        } catch (\Exception $e) {
            return;
        }
    }

    /**
     * Build the support ticket query with current UUID
     *
     * @return Builder
     */
    private function supportTicketQueryByUuid(): Builder
    {
        return SupportTicket::where('title', $this->uuid);
    }
}

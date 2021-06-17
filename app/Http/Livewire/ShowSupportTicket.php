<?php

namespace App\Http\Livewire;

use App\Enum\SupportTicketDepartment;
use App\Enum\SupportTicketStatus;
use App\Mail\SupportTicketResponse;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class ShowSupportTicket extends Component
{
    use WithFileUploads;

    /* @var SupportTicket */
    public $ticket;

    /* @var SupportMessage */
    public $message;

    /* @var array */
    public $attachments = [];

    public $modalShow = false;

    /* @var array $rules */
    protected $rules = [
        'ticket.department'        => 'required|enum_value:' . SupportTicketDepartment::class,
        'ticket.status'            => 'nullable|enum_value:' . SupportTicketStatus::class,
        'ticket.assigned_agent_id' => 'nullable|exists:users,id',
        'ticket.new_contact_email' => 'nullable|email',
        'ticket.customer_id'       => 'nullable',

        'message.content'          => 'string|min:1',
        'message.send_to_customer' => 'boolean',
    ];

    /* @var array $listeners */
    protected $listeners = [
        'customerSelected' => 'updateSupportTicketCustomer',
    ];

    public function mount()
    {
        $this->message = new SupportMessage();
    }

    public function render()
    {
        return view(
            'livewire.support-ticket-show',
            [
                'customer' => $this->customer,
                'messages' => $this->messages,
            ]
        );
    }

    public function resetMessage()
    {
        $this->reset('attachments');
        $this->message = new SupportMessage();
        $this->ticket->refresh();
    }

    /* ACCESSORS */
    public function getMessagesProperty()
    {
        return $this->ticket->messages;
    }

    public function getCustomerProperty()
    {
        return $this->ticket->customer;
    }

    /* ACTIONS */
    public function createNewSupportMessage(): void
    {
        // Return if there is no content
        if (!$this->message->content) {
            return;
        }

        // Mark ticket as read when answering it
        if (!$this->ticket->first_opened_at) {
            $this->ticket->first_opened_at = now();
            $this->ticket->save();
        }

        $this->ticket->assigned_agent_id = Auth::user()->id;

        // Persist to disk and database
        $this->saveSupportMessage();
        $this->saveSupportMessageAttachments();

        // Send mail to customer if needed
        $this->notifyCustomer();

        // Reset the response form and refresh messages
        $this->resetMessage();
    }

    public function saveSupportTicket(): void
    {
        if ($this->ticket->status->value === 'closed') {
            $this->ticket->first_opened_at = now();
        }
        $this->ticket->save();
        $this->ticket->refresh();
        session()->flash('message', 'Ticket updated!');
    }

    public function updateSupportTicketCustomer($customerId = null)
    {
        $this->ticket->customer_id = $customerId ?: $this->ticket->customer_id;
        $this->saveSupportTicket();
    }

    /* HELPER */
    protected function saveSupportMessage(): void
    {
        $this->message->fill(
            [
                'ticket_id'        => $this->ticket->id,
                'agent_id'         => auth()->user()->id,
                'content'          => (string)$this->message->content,
                'send_to_customer' => (bool)$this->message->send_to_customer,
            ]
        );

        $this->message->save();
        $this->ticket->touch();
    }

    public function saveSupportMessageAttachments()
    {
        foreach ($this->attachments as $file) {
            /** @var UploadedFile $file */
            $file->store($this->message->fileDirectory(), $this->message->fileDisk());
        }
    }

    protected function notifyCustomer(): void
    {
        $email = $this->getRecipientEmail();

        // Return if internal
        if (!$email or !$this->message->send_to_customer) {
            return;
        }

        // Send the email
        \Mail::to($email)->send($this->buildMail());

        // Update support message sent timestamp
        $this->message->sent_to_customer_at = now();
        $this->message->save();
    }

    protected function buildMail(): SupportTicketResponse
    {
        // Set subject and template
        $subject = "Re: {$this->ticket->title} [T#{$this->ticket->short_id}]";
        $mail = new SupportTicketResponse($subject, $this->message->content);

        // Add attachments
        collect($this->message->files())->each(
            function ($filepath) use ($mail) {
                $mail->attachFromStorageDisk($this->message->fileDisk(), $filepath);
            }
        );

        return $mail;
    }

    /**
     * @return string|null
     */
    protected function getRecipientEmail(): ?string
    {
        $lastContactEmail = $this->ticket->lastContactEmail();

        // check if email is provided in the ticket response
        if ($this->ticket->new_contact_email) {
            return $this->ticket->new_contact_email;
        }

        // check if replying to inbound message
        if ($lastContactEmail) {
            return $lastContactEmail;
        }

        // check if existing customer
        $customer = optional($this->ticket->customer);
        if ($customer->email_address) {
            return $customer->email_address;
        }

        return null;
    }

    public function deleteTicket()
    {
        $this->ticket->delete();

        return redirect()->to('/tickets');
    }
}

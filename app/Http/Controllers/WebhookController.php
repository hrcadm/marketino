<?php

namespace App\Http\Controllers;

use App\Enum\SupportTicketDepartment;
use App\Enum\SupportTicketStatus;
use App\Http\Requests\SupportEmailRequest;
use App\Models\Customer;
use App\Models\CustomerEmail;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Safe\Exceptions\PcreException;

// use Carbon\Carbon;

class WebhookController extends Controller
{
    /**
     * @param  SupportEmailRequest  $request
     * @return JsonResponse
     * @throws PcreException
     */
    public function supportEmail(SupportEmailRequest $request)
    {
        $from = (string)$request->get('from');
        $subject = (string)$request->get('subject');

        # Available: body-html, body-plain, stripped-html, stripped-text
        $strippedText = $request->get('stripped-text');
        // $time = Carbon::parse($request->get('Date'))->utc();

        # TODO throw exception and handle it globally
        if (!$strippedText) {
            return response()->json(['error' => 'No content']);
        }

        $ticketShortId = $this->extractTicketIdFrom($subject);
        $fromEmail = $this->extractEmailFrom($from);

        /* @var SupportTicket $ticket */
        $ticket = SupportTicket::firstOrCreate(
            [
                'short_id' => $ticketShortId,
            ],
            [
                'title'      => $subject,
                'department' => SupportTicketDepartment::TECHNICAL_LEVEL_1(),
            ],
        );

        /* @var CustomerEmail $customerEmail */
        $customerEmail = CustomerEmail::getByEmail($fromEmail)->get();

        if ($customerEmail->count() === 0) {
            $ticket->new_contact_email = $fromEmail;
        }

        if ($customerEmail->count() === 1) {
            $ticket->customer_id = $customerEmail->first()->customer->id;
        }

        $ticket->status = SupportTicketStatus::OPEN;
        $ticket->first_opened_at = null; # Reset ticket read status
        $ticket->save();

        /* @var SupportMessage $message */
        $message = SupportMessage::create(
            [
                'ticket_id'  => $ticket->id,
                'content'    => $strippedText,
                'from_email' => $fromEmail,
            ]
        );

        /** @var UploadedFile $file */
        foreach ($request->allFiles() as $file){
            $file->store($message->fileDirectory(), $message->fileDisk());
        }

        return response()->json();
    }

    /**
     * @param  string  $from
     * @return string|null
     * @throws PcreException
     */
    protected function extractEmailFrom(string $from)
    {
        \Safe\preg_match('/\<(.*)>/', $from, $matches);

        // Get first capturing group (full match is with parenthesis)
        return (string)\Arr::get($matches, 1) ?? $from;
    }

    /**
     * @param  string  $subject
     * @return string|null
     * @throws PcreException
     */
    protected function extractTicketIdFrom(string $subject)
    {
        \Safe\preg_match('/\[T#(\S{6,8})\]/', $subject, $matches);

        // Get first capturing group (full match is with parenthesis)
        return (string)\Arr::get($matches, 1) ?: null;
    }

    /**
     * @param  string  $from
     * @return string
     * @throws PcreException
     */
    protected function extractNameFrom(string $from)
    {
        \Safe\preg_match('/^[a-zA-Z]*/', $from, $matches);

        // Will return From name or first part of email address
        return (string)\Arr::get($matches, 0);
    }
}

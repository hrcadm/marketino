<?php

namespace App\Http\Controllers;

use App\Enum\SupportTicketStatus;
use App\Http\Requests\SupportTicketCreateRequest;
use App\Mail\SupportTicketCreated;
use App\Models\Customer;
use App\Models\SupportTicket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Sopamo\LaravelFilepond\Filepond;

class TicketController extends Controller
{
    /* @var SupportTicket */
    public $ticket;

    public function index()
    {
        // Build initial query
        $tickets = SupportTicket::with('customer', 'messages', 'assignedAgent')
            ->orderByDesc('updated_at')
            ->paginate(10);

        return view('pages.tickets.index', ['tickets' => $tickets]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('pages.tickets.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  SupportTicketCreateRequest  $request
     * @return RedirectResponse
     */
    public function store(SupportTicketCreateRequest $request)
    {
        $supportTicket = new SupportTicket($request->validated());
        $supportTicket->status = SupportTicketStatus::OPEN();
        $supportTicket->save();

        $supportMessage = $supportTicket->messages()->create(
            [
                'content'  => $request->get('content'),
                'agent_id' => $request->user()->id,
            ]
        );

        if ($request->get('send_to_customer')) {
           $supportTicket->messages()->latest()->first()->update([
                'send_to_customer' => true,
                'sent_to_customer_at' => now()
            ]);
            $this->notifyCustomer($supportTicket);
        }

        $files = (array)$request->get('filepond');

        /** @var UploadedFile $file */
        foreach ($files as $file) {

            $filepond = app(Filepond::class);
            $path = $filepond->getPathFromServerId($file);

            $parts = explode('/', $path);
            $filename = array_pop($parts);

            Storage::cloud()->put($supportMessage->fileDirectory() . '/' . $filename, file_get_contents($path));

        }

        return redirect()->route(
            'tickets.show',
            [
                'ticket' => $supportTicket,
            ]
        );
    }

    protected function notifyCustomer(SupportTicket $supportTicket): void
    {
        $email = $supportTicket->new_contact_email ?? $supportTicket->customer->email_address;

        if ($email) {
            \Mail::to($email)->send($this->buildMail($supportTicket));
        }
    }

    protected function buildMail(SupportTicket $supportTicket): SupportTicketCreated
    {
        // Set subject and template
        $subject = "{$supportTicket->title} [T#{$supportTicket->short_id}]";

        return new SupportTicketCreated($subject);
    }

    /**
     * Display the specified resource.
     *
     * @param  SupportTicket  $ticket
     * @return Response
     */
    public function show(SupportTicket $ticket)
    {
        $ticket->load('customer', 'messages.agent');

        return view('pages.tickets.show', ['ticket' => $ticket]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  SupportTicket  $ticket
     * @return Response
     */
    public function destroy(SupportTicket $ticket)
    {
        // TODO soft delete the support ticket (thread) or archive

        return redirect()->route('tickets.index');
    }
}

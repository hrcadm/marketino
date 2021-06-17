<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TicketAttachmentsController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  SupportTicket  $ticket
     * @param  Request  $request
     * @return Response
     * @throws \Throwable
     */
    public function show(SupportTicket $ticket, Request $request)
    {
        $path = $request->get('path');
        throw_unless($path, new \InvalidArgumentException('Path not provided'));

        $file = \Storage::cloud()->get($path);
        $mimeType = \Storage::cloud()->mimeType($path);
        $lastModified = \Storage::cloud()->lastModified($path);

        return response($file, Response::HTTP_OK, [
            'Content-Type'  => $mimeType,
            'Expires'       => $this->httpDate(strtotime('+1 year')),
            'Cache-Control' => 'public, max-age=31536000',
            'Last-Modified' => $this->httpDate($lastModified),
        ]);
    }

    protected function httpDate($timestamp)
    {
        return sprintf('%s GMT', gmdate('D, d M Y H:i:s', $timestamp));
    }
}

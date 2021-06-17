<?php

namespace App\Http\Controllers;

use App\Models\NextStep;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Requests\NextStepRequest;
use Illuminate\Support\Facades\Auth;
use App\Jobs\CreateNextStep;

class NextStepController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // if(empty($request->daterange) || empty($request->type)) {
        //     if(empty($request->daterange))
        //         $inputs['daterange'] = date('d.m.Y', strtotime('-1 year')) . ' - ' . date('d.m.Y');
        //     if(empty($request->type))
        //         $inputs['type'] = 'in-progress';
        //     return redirect()->route('next-steps.index', $inputs);
        // }
        if($request->ajax()) {
            $nextSteps = NextStep::with(['customer', 'user'])
                        ->when($request->q, function ($query) use ($request) {
                            return $query->whereHas('customer', function ($query) use ($request) {
                                $keywords = explode(' ', $request->q);
                                foreach ($keywords ?? [] as $key => $word) {
                                    $query->where('comment', 'LIKE', "%{$word}%");
                                    $query->orWhere('first_name', 'LIKE', "%{$word}%");
                                    $query->orWhere('last_name', 'LIKE', "%{$word}%");
                                    $query->orWhere('company_name', 'LIKE', "%{$word}%");
                                    $query->orWhereHas('phones', function ($query) use ($word) {
                                        $query->where('customer_phones.phone', 'LIKE', "%{$word}%");
                                    });
                                    $query->orWhereHas('emails', function ($query) use ($word) {
                                        $query->where('customer_emails.email', 'LIKE', "%{$word}%");
                                    });
                                }
                                return $query;
                            });
                        })
                        ->when($request->daterange, function ($query) use ($request) {
                            $dates = explode("-", $request->daterange);
                            if(isset($dates[0]) ) $query->whereDate('date', '>=', \Carbon\Carbon::parse($dates[0]));
                            if(isset($dates[1]) ) $query->whereDate('date', '<=', \Carbon\Carbon::parse($dates[1]));
                            return $query;
                        })
                        ->when($request->type, function ($query) use ($request) {
                            if($request->type === 'in-progress') 
                                return $query->where('resolved', '!=', TRUE);
                            elseif($request->type === 'only-mine')
                                return $query->where('user_id', Auth::id());
                            elseif($request->type === 'quotes')
                                return $query->whereNotNull('quote_id');
                        })
                        ->orderByDesc('date')->orderByDesc('id')
                        ->paginate(10); 
            return response()->json(['html' => view('pages.next-steps.results', compact('nextSteps'))->render()]);   
        }

        return view('pages.next-steps.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NextStepRequest $request)
    {
        $customer = Customer::findOrFail($request->customer_id);
        $nextStep = new NextStep($request->validated());
        $nextStep->customer_id = $customer->id;
        $nextStep->user_id = Auth::id();
        $nextStep->save();
        if($request->old_nextstep) {
            $oldNextStep = NextStep::find($request->old_nextstep);
            if($oldNextStep) $oldNextStep->update(['resolved' => TRUE]);
        }
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\NextStep  $nextStep
     * @return \Illuminate\Http\Response
     */
    public function show(NextStep $nextStep)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\NextStep  $nextStep
     * @return \Illuminate\Http\Response
     */
    public function edit(NextStep $nextStep)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\NextStep  $nextStep
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, NextStep $nextStep)
    {
        if($request->resolved)
            $nextStep->update(['resolved' => TRUE]);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\NextStep  $nextStep
     * @return \Illuminate\Http\Response
     */
    public function destroy(NextStep $nextStep)
    {
        //
    }
}

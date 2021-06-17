@extends('pdf.layout')

@section('content')

    <div class="header">
        <div class="seller">
            Marketino SRL<br>
            Roma 20,<br>
            33075 Cordovado (PN), Italia
        </div>
        <div class="client">
            {!! $pdfQuote->getCustomerDataBlockAttribute() !!}
        </div>
    </div>

    <h1 class="title">Preventivo n. {{ $quote->document_number }}</h1>

    <div class="dates">
        <div class="valid_until">Data di validità: {{ $quote->valid_until->format('d.m.Y.') }}</div>
        <div class="create_at">Data di emissione: {{ $quote->created_at->format('d.m.Y.') }}</div>
    </div>

    <div class="table-container">
        <table>
            <thead>
            <tr>
                <th>Nome/Descrizione</th>
                <th>Prezzo unitario</th>
                <th>Quantità</th>
                <th>Prezzo</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($quote->items as $item)
                <tr>
                    <td><b>{{ $item->saleItemPrice->name }}</b></td>
                    <td class="text-right">

                        @if ($item->saleItemPrice->original_net_price)
                            <span class="original_price">{{$item->saleItemPrice->original_net_price}}</span>
                        @endif

                        {{ number_format($item->saleItemPrice->net_price, 2, ',', '.') }}

                    </td>
                    <td class="text-right">{{ $item->quantity }}</td>
                    <td class="text-right">{{ number_format($item->total_net_amount_before_discount, 2, ',', '.') }}</td>
                </tr>

                @if($item->total_discount_amount > 0)
                    <tr>
                        <td> &nbsp; &nbsp; {{ $item->saleItemPrice->discount_name }}</td>
                        <td class="text-right">
                            - {{ number_format($item->saleItemPrice->discount_amount, 2, ',', '.') }}
                        </td>
                        <td class="text-right">{{ $item->quantity }}</td>
                        <td class="text-right">- {{ number_format($item->total_discount_amount, 2, ',', '.') }}</td>
                    </tr>
                @endif

                @if($item->note)
                    <tr>
                        <td colspan="4"> &nbsp; &nbsp; {{ $item->note }}</td>
                    </tr>
                @endif

                <tr>
                    <td colspan="4"> &nbsp;</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="text-right">
            <p>
                Totale: {{ number_format($quote->total_net_amount, 2, ',', '.') }} &euro;<br>
                IVA: {{ number_format($quote->total_vat_amount, 2, ',', '.') }} &euro;
            </p>

            <p class="text-xl">
                Totale da pagare: {{ number_format($quote->total_amount, 2, ',', '.') }} &euro;
            </p>
        </div>
    </div>

    <div class="bank-details">
        <p>
            Effettua il pagamento:<br>
            Banca: Intesa SanPaolo<br>
            IBAN: IT43A0306965015100000007100<br>
            BIC: BCITITMMXXX<br>
            Numero di conto bancario: 50552/100/00007100
        </p>
    </div>

    @if($quote->containsMarketinoCashRegisterWithoutRates())
        <br/>

        <table>
            <tr>
                <th>NOTA INFORMATIVA</th>
                <th>Quantità</th>
                <th>Prezzo unitario</th>
                <th>Prezzo</th>
                <th>Prezzo con IVA</th>
            </tr>
            <tr>
                <td>Marketino - Abbonamento Mensile<br> (pagamento bimestrale)</td>
                <td class="text-right">2</td>
                <td class="text-right">11,99 &euro;</td>
                <td class="text-right">23,98 &euro;</td>
                <td class="text-right">29,26 &euro;</td>
            </tr>
            <tr>
                <td>Marketino - Abbonamento Annuale</td>
                <td class="text-right">12</td>
                <td class="text-right">9,99 &euro;</td>
                <td class="text-right">119,88 &euro;</td>
                <td class="text-right">146,25 &euro;</td>
            </tr>
        </table>
        <b>* a pagamento dal 01.03.2021.</b>
    @endif

    @if($quote->containsMarketinoCashRegisterWithRates())
        <br/>

        <table>
            <tr>
                <th>NOTA INFORMATIVA</th>
                <th>Prezzo unitario</th>
                <th>Quantità</th>
                <th>Prezzo</th>
                <th>Prezzo con IVA</th>
            </tr>

            @foreach ($quote->getRateRows() as $rate)
                <tr>
                    <td>{{$rate->name}}</td>
                    <td class="text-right">{{ number_format($rate->net_price, 2, ',', '.') }} &euro;</td>
                    <td class="text-right">{{$rate->quantity}}</td>
                    <td class="text-right">{{ number_format($rate->total_net_amount, 2, ',', '.') }} &euro;</td>
                    <td class="text-right">{{ number_format($rate->total_amount, 2, ',', '.') }} &euro;</td>
                </tr>
            @endforeach;

        </table>
        <b>* abbonamento incluso per 12 mesi</b>
    @endif

    <div>
        <p>Inviando il pagamento della presente offerta si accettano implicitamente le condizioni e i termini di vendita
            sempre disponibili sul sito
            <a href="https://www.marketino.it/condizioni-e-i-termini-di-vendita">https://www.marketino.it/condizioni-e-i-termini-di-vendita</a>
        </p>
    </div>

    {{--    <div class="notes">--}}
    {{--        {!! nl2br($quote->note) !!}--}}
    {{--    </div>--}}

@endsection

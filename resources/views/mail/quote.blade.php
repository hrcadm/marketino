
@component('mail::message')
**Preventivo n:{{$quote->document_number}} - Marketino**


Gentile cliente,

Grazie per aver richiesto un preventivo per Marketino.

In allegato trovi il tuo preventivo e l'offerta personalizzata.

Contattaci al numero **+390289735735** o scrivici a **info@marketino.it** qualora dovessi avere bisogno di ulteriori info.

Siamo felici di averti a bordo!


Cordiali saluti,


Il tuo team Marketino

    {{--@component('mail::button', ['url' => '#'])--}}
    {{--    View Order--}}
    {{--@endcomponent--}}

    {{--@component('mail::button', ['url' => '#', 'color' => 'success'])--}}
    {{--    View Order--}}
    {{--@endcomponent--}}
<div style="text-align: center">
<img src="http://hub.marketino.it/images/marketino_logo.jpg" alt="marketino" width="200" />
</div>

<hr>


<div style="font-size: smaller;color:#CCC">
La presente E-Mail, nel rispetto della normativa sulla privacy, è riservata ai destinatari indicati.
Qualora abbiate ricevuto la presente comunicazione per errore, Vi preghiamo di avvisare il mittente e di cancellarla dal vostro sistema informatico.
Gli allegati e il corpo dell'E-Mail sono stati sottoposti a scansione antivirus per verificare l'assenza di virus.
E' comunque vostra responsabilità verificare che la presente sia controllata da un vostro software antivirus.
</div>

@endcomponent

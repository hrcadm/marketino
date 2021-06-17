@push('styles')
<link rel="stylesheet" href="{{ asset('vendor/daterangepicker/daterangepicker.css') }}">
@endpush

@push('scripts')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>
<script type="text/javascript" src="{{ asset('vendor/daterangepicker/daterangepicker.js') }}"></script>
<script>
  function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, '\\$&');
    var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
    results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, ' '));
  }

  function getResults(page = 1) {
    var data = $('form input[name="q"], form input[name="daterange"], form input[name="type"]').serialize() + '&page=' + page;
    currentRequest = $.ajax({
      method: 'GET', 
      url: '{{ route('next-steps.index') }}',
      data: data,

      beforeSend : function()    {           
        if(currentRequest != null) {
          currentRequest.abort();
        }
        // $('#results').html('<h4 class="animate-pulse text-center">loading</h4>');
        $('#results table').css("filter", "blur(0.5rem)");
      },

      success (data) {
        $('#results').html(data.html);
        $('#results table').css("filter", "none");
      }
    });
    window.history.replaceState(null, "Search Results", '{{ route('next-steps.index') }}?'+data);
  } 


  var currentRequest = null;  
  getResults({{ request()->page }});

  $('form input').on('change', function () {
    getResults();
  });  

  $('body').on('click', '.pagination a', function(e) {
    e.preventDefault();
    getResults(getParameterByName('page', $(this).attr('href')));
    $("html, body").animate({ scrollTop: 0 }, "slow");
  });    


  $('body').on('click','button[data-toggle="modal"]',function(){
    $($(this).data('target')).show();
  });  

  $('body').on('click','button[data-toggle="copy"]',function(){
    var copyField = document.createElement('input');

    document.body.appendChild(copyField);
    copyField.value = $(this).data('text');
    /* Select the text field */
    copyField.select();
    copyField.setSelectionRange(0, 99999); /*For mobile devices*/

    /* Copy the text inside the text field */
    document.execCommand("copy");
    copyField.remove();
  });  

  $('body').on('click','.close',function(){
    $($(this).data('target')).hide();
  });



  $(function() {
    var date = new Date();
    d = new Date();
    $('input[name="daterange"]').daterangepicker({
      "autoApply": true,
      "linkedCalendars": false,
      "showDropdowns": true,
      "alwaysShowCalendars": true,
      "opens": "center",
      "locale": {
        "format": 'DD.MM.YYYY'
      },
      "ranges": {
        "{{ __('Today') }}": [
        date,
        date
        ],
        "{{ __('Last 3 Days') }}": [
        d.setDate(d.getDate() - 3),
        date
        ],
      },
    }, function(start, end, label) {
      console.log("New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')");
    });
  });



</script>
@endpush





<x-app-layout>
  <x-slot name="header">
    {{ __('Next Steps') }}

  </x-slot>

  <div class="px-4 py-1 bg-white border-b border-gray-200">

    <div class="bg-gray-100 rounded px-3 my-4" id="nextStep-form">
      <form class="my-6" role="form" method="GET" action="{{ route('next-steps.index') }}">

        <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-12">
          <div class="sm:col-span-4">
            <x-jet-label value="{{ __('Search') }}"></x-jet-label>
            <x-jet-input class="block mt-1 w-full" type="text" name="q"
            :value="old('comment', request()->q )" autofocus></x-jet-input>
          </div>
          <div class="sm:col-span-3">
            <x-jet-label value="{{ __('Date') }}"></x-jet-label>
            <x-jet-input class="block mt-1 w-full" type="text" name="daterange" :value="old('date', request()->daterange ?? date('d.m.Y', strtotime('-1 year')) . ' - ' . date('d.m.Y'))" autofocus readonly></x-jet-input>
          </div>
          <div class="sm:col-span-5">
            <x-jet-label value="{{ __('Options') }}"></x-jet-label>
            <div class="inline-block py-4 mr-4">
              <label class="flex items-center">
                <input type="radio" class="form-checkbox" name="type" value="all" {{ !request()->type || request()->type === 'all' ? 'checked' : '' }}>
                <span class="ml-2 text-sm text-gray-600">{{ __('All') }}</span>
              </label>
            </div>
            <div class="inline-block py-4 mr-4">
              <label class="flex items-center">
                <input type="radio" class="form-checkbox" name="type" value="in-progress" {{ (request()->type === 'in-progress' || !request()->type) ? 'checked' : '' }}>
                <span class="ml-2 text-sm text-gray-600">{{ __('In progress') }}</span>
              </label>
            </div>
            <div class="inline-block py-4 mr-4">
              <label class="flex items-center">
                <input type="radio" class="form-checkbox" name="type" value="only-mine" {{ request()->type === 'only-mine' ? 'checked' : '' }}>
                <span class="ml-2 text-sm text-gray-600">{{ __('Only mine') }}</span>
              </label>
            </div>
            <div class="inline-block py-4 mr-4">
              <label class="flex items-center">
                <input type="radio" class="form-checkbox" name="type" value="quotes" {{ request()->type === 'quotes' ? 'checked' : '' }}>
                <span class="ml-2 text-sm text-gray-600">{{ __('Quotes') }}</span>
              </label>
            </div>
          </div>

        </div>
<!--         <div class="flex items-center justify-end mt-4">
          <x-jet-button class="ml-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            {{ __('Filter') }}
          </x-jet-button>
        </div> -->

      </form>
    </div>
    <div id="results">{{ __('Loading') }}</div>
  </div>
</x-app-layout>


<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <style>
        @font-face {
            font-family: 'Open Sans';
            font-weight: normal;
            font-style: normal;
            font-variant: normal;
            src: url("https://fonts.googleapis.com/css2?family=Open+Sans&display=swap");
        }

        body {
            font-family: 'Open Sans', sans-serif;
        }
        .page-number:before {
             content: "Pagina " counter(page);
        }

        {{file_get_contents(base_path('public/css/pdf.css'))}}
    </style>
    <title>Marketino</title>
</head>
<body>
<div id="footer">
    <div class="page-number"></div>
</div>
<div class="container">
    <div class="logo">
        <img src="{{ base_path('public/images/marketino_logo.jpg') }}" width="200" class="mx-auto">
    </div>

    @yield('content')

    <footer class="footer">
        Marketino S.R.L. con sede in via Roma 20, 33075 Cordovado (PN), Italia, P.IVA 01873110934, Codice Fiscale
        01873110934
    </footer>


</div>
</body>
</html>

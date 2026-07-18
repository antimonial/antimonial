<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Antimonial')</title>
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
    @if(!empty(errors()))
        <div class="flash" id="flash">
            <ul>
            @foreach(errors() as $fieldErrors)
                @foreach($fieldErrors as $message)
                    <li>{{ e($message) }}</li>
                @endforeach
            @endforeach
            </ul>
        </div>
    @endif

    {{{ $content }}}
</body>
</html>

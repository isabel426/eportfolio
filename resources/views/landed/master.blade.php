<!DOCTYPE HTML>
<!--
 Landed by HTML5 UP
 html5up.net | @ajlkn
 Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>

<head>
    <title>Landed by HTML5 UP</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="{{ asset('/landed/assets/css/main.css') }}" />
    <noscript>
        <link rel="stylesheet" href="{{ asset('/landed/assets/css/noscript.css') }}" />
    </noscript>
    <link rel="stylesheet" href="{{ asset('css/flash-messages.css') }}">
</head>

<body class="is-preload landing">
    <x-flash-messages />
    <div id="page-wrapper">

        <!-- Header -->
        @include('landed.partials.header')

        <!-- Banner -->
        @include('landed.partials.main')

        <!-- Footer -->
        @include('landed.partials.footer')

    </div>

    <!-- Scripts -->
    <script src="{{ asset('/landed/assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('/landed/assets/js/jquery.dropotron.min.js') }}"></script>
    <script src="{{ asset('/landed/assets/js/browser.min.js') }}"></script>
    <script src="{{ asset('/landed/assets/js/breakpoints.min.js') }}"></script>
    <script src="{{ asset('/landed/assets/js/util.js') }}"></script>
    <script src="{{ asset('/landed/assets/js/main.js') }}"></script>
    <script>
        document.querySelectorAll('[data-flash]').forEach(el => {
            setTimeout(() => {
                el.style.transition = 'opacity 0.4s'
                el.style.opacity = '0'
                setTimeout(() => el.remove(), 400)
            }, 4000) // ms antes de desaparecer
        })
    </script>

</body>

</html>

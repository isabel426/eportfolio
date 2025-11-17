<!DOCTYPE HTML>
<!--
 Prologue by HTML5 UP
 html5up.net | @ajlkn
 Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>

<head>
    <title>Prologue by HTML5 UP</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="{{ asset('/prologue/assets/css/main.css') }}" />
</head>

<body class="is-preload">

    @include('prologue.partials.header')

    @include('prologue.partials.navbar')

    @include('prologue.partials.main')

    @include('prologue.partials.footer')

    <!-- Scripts -->
    <script src="{{ asset('/dopetrope/assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('/dopetrope/assets/js/jquery.dropotron.min.js') }}"></script>
    <script src="{{ asset('/dopetrope/assets/js/browser.min.js') }}"></script>
    <script src="{{ asset('/dopetrope/assets/js/breakpoints.min.js') }}"></script>
    <script src="{{ asset('/dopetrope/assets/js/util.js') }}"></script>
    <script src="{{ asset('/dopetrope/assets/js/main.js') }}"></script>

</body>

</html>

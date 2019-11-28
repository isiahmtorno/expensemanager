<!DOCTYPE html>
<html lang="en">

<head>

	<title>{{ $title }}</title>
	<meta name="description" content="">
	<meta name="keywords" content="">

	<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}" />
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

	{!! $css_files !!}

</head>
<body class="h-100">
	
	@yield('body')

	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
	{!! $js_files !!}

    <script src="https://unpkg.com/shards-ui@latest/dist/js/shards.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sharrre/2.0.1/jquery.sharrre.min.js"></script>

</body>
</html>
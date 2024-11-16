<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Web Form for Zoho CRM Deals and Accounts</title>
        @vite(['style.css'])
    </head>

    <body class="bg-light" style="">       
        
        <div id="app"></div>

        @vite(['resources/js/home.js'])

    </body>
    
</html>

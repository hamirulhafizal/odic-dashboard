@isset($pageConfigs)
{!!   app\Helpers\helper::updatePageConfig($pageConfigs) !!}
@endisset

<!DOCTYPE html>
@php
$configData =  app\Helpers\helper::applClasses();
@endphp

<html class="loading {{ ($configData['theme'] === 'light') ? '' : $configData['layoutTheme']}}"
lang="@if(session()->has('locale')){{session()->get('locale')}}@else{{$configData['defaultLanguage']}}@endif"
data-textdirection="{{ env('MIX_CONTENT_DIRECTION') === 'rtl' ? 'rtl' : 'ltr' }}"
@if($configData['theme'] === 'dark') data-layout="dark-layout" @endif>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  {{-- <meta name="description" content="Vuexy admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities."> --}}
  {{-- <meta name="keywords" content="admin template, Vuexy admin template, dashboard template, flat admin template, responsive admin template, web app"> --}}
  <meta name="author" content="PIXINVENT">
  <title>@yield('title')ONE DREAM PROPERTY</title>
  <link rel="apple-touch-icon" href="{{asset('images/ico/apple-icon-120.png')}}">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">

  <link rel="shortcut icon" type="image/x-icon" href="{{asset('images/logo/logo.png')}}">

  <!--Responsive Extension Datatables CSS-->
  <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>


  {{-- Include core + vendor Styles --}}
  @include('panels/styles')

<!-- Webstats -->
<script>var mywebstats_site_ids = mywebstats_site_ids || []; mywebstats_site_ids.push(101324156);</script>
    <script async src="//hello.staticstuff.net/w/mywebstats.js"></script>
</head>
<!-- END: Head-->

<!-- BEGIN: Body-->
@isset($configData["mainLayoutType"])
@extends((( $configData["mainLayoutType"] === 'horizontal') ? 'layouts.horizontalLayoutMaster' :
'layouts.verticalLayoutMaster' ))
@endisset

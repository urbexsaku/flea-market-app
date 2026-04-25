@extends('layouts.auth')

@section('content')
<p>登録していただいたメールアドレスに認証メールを送付しました。</p>
<p>メール認証を完了してください。</p>

@if (session('message'))
  
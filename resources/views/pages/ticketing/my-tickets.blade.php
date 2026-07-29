@extends('layouts.app-layout')

@section('title', 'Tiket Saya')
@section('subtitle', 'Daftar tiket bantuan yang telah Anda ajukan')

@section('content')
  <!-- ============ VIEW: TIKET SAYA ============ -->
  <section id="view-ticketing-my-tickets" class="view space-y-6">
    <div class="bg-white dark:bg-gray-900 rounded-3xl border border-gray-200 dark:border-gray-800 p-6 space-y-6">
      <h3 class="font-bold text-lg border-b border-gray-100 dark:border-gray-800 pb-3">Tiket Saya</h3>
      <p class="text-sm text-gray-500">Berikut adalah daftar tiket bantuan yang Anda buat beserta status penanganannya.</p>
    </div>
  </section>
@endsection

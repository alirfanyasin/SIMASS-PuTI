@extends('layouts.app-layout')

@section('title', 'Pengajuan Tiket')
@section('subtitle', 'Buat tiket bantuan baru untuk melaporkan masalah atau request')

@section('content')
  <!-- ============ VIEW: PENGAJUAN TIKET ============ -->
  <section id="view-ticketing-create" class="view space-y-6">
    <div class="bg-white dark:bg-gray-900 rounded-3xl border border-gray-200 dark:border-gray-800 p-6 space-y-6">
      <h3 class="font-bold text-lg border-b border-gray-100 dark:border-gray-800 pb-3">Form Pengajuan Tiket</h3>
      <p class="text-sm text-gray-500">Silakan isi formulir di bawah ini untuk mengajukan tiket bantuan baru.</p>
    </div>
  </section>
@endsection

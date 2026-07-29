@extends('layouts.app-layout')

@section('title', 'Task Management')
@section('subtitle', 'Pantau dan kelola tugas-tugas penanganan tiket')

@section('content')
  <!-- ============ VIEW: TASK MANAGEMENT ============ -->
  <section id="view-ticketing-tasks" class="view space-y-6">
    <div class="bg-white dark:bg-gray-900 rounded-3xl border border-gray-200 dark:border-gray-800 p-6 space-y-6">
      <h3 class="font-bold text-lg border-b border-gray-100 dark:border-gray-800 pb-3">Task Management</h3>
      <p class="text-sm text-gray-500">Halaman ini digunakan untuk mengelola tugas dari tiket bantuan.</p>
    </div>
  </section>
@endsection

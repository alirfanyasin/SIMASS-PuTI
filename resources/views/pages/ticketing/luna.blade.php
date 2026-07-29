@extends('layouts.app-layout')

@section('title', 'Luna AI Chatbot')
@section('subtitle', 'Tanyakan apa saja kepada asisten AI kami')

@section('content')
  <!-- ============ VIEW: LUNA AI CHATBOT ============ -->
  <section id="view-ticketing-luna" class="view">
    <div class="bg-white dark:bg-gray-900 rounded-3xl border border-gray-200 dark:border-gray-800 h-[calc(100vh-13rem)] flex flex-col overflow-hidden shadow-sm">
      <!-- Chat Header -->
      <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between shrink-0">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-2xl bg-telkom-50 dark:bg-telkom-950/50 flex items-center justify-center text-telkom-600 dark:text-telkom-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
              <path d="M12 2a10 10 0 0 1 10 10c0 5.523-4.477 10-10 10S2 17.523 2 12c0-2.4 1-4.8 2.75-6.5" />
              <path d="M12 10a2 2 0 1 0 0 4 2 2 0 0 0 0-4Z" />
              <path d="m17 7-5 5" />
            </svg>
          </div>
          <div>
            <h3 class="font-bold text-sm text-gray-900 dark:text-white">Luna AI</h3>
            <p class="text-[10px] font-semibold text-emerald-500 flex items-center gap-1.5 mt-0.5">
              <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Online
            </p>
          </div>
        </div>
      </div>

      <!-- Chat Bubble Container -->
      <div class="flex-1 p-6 overflow-y-auto space-y-4 bg-gray-50/30 dark:bg-gray-950/10" id="chatContainer">
        <!-- AI Welcome Message -->
        <div class="flex gap-3 max-w-[80%]">
          <div class="w-8 h-8 rounded-xl bg-telkom-50 dark:bg-telkom-950/50 flex items-center justify-center text-telkom-600 dark:text-telkom-400 shrink-0 shadow-sm border border-telkom-100/10">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path d="M12 2a10 10 0 0 1 10 10c0 5.523-4.477 10-10 10S2 17.523 2 12c0-2.4 1-4.8 2.75-6.5" />
              <circle cx="12" cy="12" r="2" />
              <path d="m17 7-5 5" />
            </svg>
          </div>
          <div class="bg-white dark:bg-gray-850 p-4 rounded-3xl rounded-tl-sm border border-gray-150 dark:border-gray-800 shadow-sm text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
            Halo! Saya **Luna AI**, asisten virtual Anda. Ada yang bisa saya bantu terkait kendala presensi, lembur, atau pelaporan tiket bantuan Anda hari ini? 😊
          </div>
        </div>
      </div>

      <!-- Chat Input Footer -->
      <div class="p-4 border-t border-gray-100 dark:border-gray-800 shrink-0 bg-white dark:bg-gray-900">
        <form onsubmit="sendMessage(event)" class="flex gap-2">
          <input type="text" id="chatInput" placeholder="Ketik pesan Anda di sini..." required
            class="flex-1 px-4 py-3 rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-telkom-500 transition">
          <button type="submit"
            class="px-5 bg-telkom-600 hover:bg-telkom-700 text-white rounded-2xl transition flex items-center justify-center shadow-lg shadow-telkom-600/20">
            <svg class="w-5 h-5 rotate-90" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <line x1="22" y1="2" x2="11" y2="13"></line>
              <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
            </svg>
          </button>
        </form>
      </div>
    </div>
  </section>
@endsection

@push('scripts')
<script>
  function sendMessage(event) {
    event.preventDefault();
    const input = document.getElementById('chatInput');
    const container = document.getElementById('chatContainer');
    const text = input.value.trim();
    if (!text) return;

    // Append User Message
    const userMsg = document.createElement('div');
    userMsg.className = 'flex gap-3 max-w-[80%] ml-auto justify-end';
    userMsg.innerHTML = `
      <div class="bg-telkom-600 text-white p-4 rounded-3xl rounded-tr-sm shadow-md text-sm leading-relaxed">
        ${text}
      </div>
    `;
    container.appendChild(userMsg);
    input.value = '';
    container.scrollTop = container.scrollHeight;

    // Mock AI Typing and response
    setTimeout(() => {
      const aiMsg = document.createElement('div');
      aiMsg.className = 'flex gap-3 max-w-[80%]';
      aiMsg.innerHTML = `
        <div class="w-8 h-8 rounded-xl bg-telkom-50 dark:bg-telkom-950/50 flex items-center justify-center text-telkom-600 dark:text-telkom-400 shrink-0 shadow-sm border border-telkom-100/10">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path d="M12 2a10 10 0 0 1 10 10c0 5.523-4.477 10-10 10S2 17.523 2 12c0-2.4 1-4.8 2.75-6.5" />
            <circle cx="12" cy="12" r="2" />
            <path d="m17 7-5 5" />
          </svg>
        </div>
        <div class="bg-white dark:bg-gray-850 p-4 rounded-3xl rounded-tl-sm border border-gray-150 dark:border-gray-800 shadow-sm text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
          Terima kasih atas laporan Anda. Saya sedang meneruskan pesan Anda ke tim support terkait. Silakan tunggu update status penanganan pada menu **Tiket Saya**.
        </div>
      `;
      container.appendChild(aiMsg);
      container.scrollTop = container.scrollHeight;
    }, 1000);
  }
</script>
@endpush

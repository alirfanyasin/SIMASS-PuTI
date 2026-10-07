@extends('layouts.app-layout')

@section('title', 'Luna AI Chatbot')
@section('subtitle', 'Asisten virtual cerdas untuk panduan layanan IT dan helpdesk PuTI Telkom University Surabaya')

@section('content')
    <!-- ============ VIEW: LUNA AI CHATBOT (DON NORMAN & ZANDER WHITEHURST DESIGN) ============ -->
    <section id="view-ticketing-luna" class="view max-w-5xl mx-auto">
        <!-- Main White Canvas Card -->
        <div
            class="bg-white dark:bg-gray-900 rounded-3xl border border-gray-200/90 dark:border-gray-800 h-[calc(100vh-12rem)] flex flex-col overflow-hidden shadow-sm">

            <!-- 1. Header (Signifiers, Visibility, Conceptual Model) -->
            <div
                class="px-6 py-4 bg-white dark:bg-gray-900 border-b border-gray-150 dark:border-gray-800 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-3.5">
                    <div
                        class="w-11 h-11 rounded-2xl bg-telkom-50 dark:bg-telkom-950/60 border border-telkom-200/60 dark:border-telkom-900/60 flex items-center justify-center text-telkom-600 dark:text-telkom-400 shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M12 2a10 10 0 0 1 10 10c0 5.523-4.477 10-10 10S2 17.523 2 12c0-2.4 1-4.8 2.75-6.5" />
                            <path d="M12 10a2 2 0 1 0 0 4 2 2 0 0 0 0-4Z" />
                            <path d="m17 7-5 5" />
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="font-bold text-base text-gray-900 dark:text-white">Luna AI Assistant</h3>
                            <span
                                class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/60">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Online
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Asisten Resmi PuTI Telkom University
                            Surabaya</p>
                    </div>
                </div>

                <!-- Quick Header Action (Affordance & Escalation to Human / New Ticket) -->
                <div class="flex items-center gap-2">
                    <a href="{{ route('ticket.create') }}" title="Buat tiket langsung melalui formulir standar"
                        class="hidden sm:inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-telkom-700 dark:text-telkom-300 bg-telkom-50 dark:bg-telkom-950/50 hover:bg-telkom-100 dark:hover:bg-telkom-900/80 border border-telkom-200/80 dark:border-telkom-900 transition">
                        <i data-lucide="plus-circle" class="w-3.5 h-3.5"></i>
                        <span>Form Tiket Manual</span>
                    </a>
                    <a href="{{ route('ticket.my-tickets') }}" title="Lihat riwayat tiket Anda"
                        class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-750 transition">
                        <i data-lucide="ticket" class="w-3.5 h-3.5"></i>
                        <span class="hidden md:inline">Tiket Saya</span>
                    </a>
                </div>
            </div>

            <!-- 2. Chat Conversation Canvas (Generous Whitespace, Conceptual Model) -->
            <div class="flex-1 p-6 overflow-y-auto space-y-4 bg-gray-50/40 dark:bg-gray-950/20" id="chatContainer">
                <!-- AI Welcome Message with Don Norman Visibility & Signifiers -->
                <div class="flex gap-3 max-w-[85%] sm:max-w-[75%]">
                    <div
                        class="w-9 h-9 rounded-xl bg-telkom-50 dark:bg-telkom-950/60 border border-telkom-100 dark:border-telkom-900/50 flex items-center justify-center text-telkom-600 dark:text-telkom-400 shrink-0 shadow-sm mt-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path d="M12 2a10 10 0 0 1 10 10c0 5.523-4.477 10-10 10S2 17.523 2 12c0-2.4 1-4.8 2.75-6.5" />
                            <circle cx="12" cy="12" r="2" />
                            <path d="m17 7-5 5" />
                        </svg>
                    </div>
                    <div
                        class="bg-white dark:bg-gray-800/90 p-4 rounded-3xl rounded-tl-sm border border-gray-200/80 dark:border-gray-700/80 shadow-sm text-sm text-gray-800 dark:text-gray-200 leading-relaxed space-y-2">
                        <p>Halo! Saya <strong class="text-telkom-600 dark:text-telkom-400 font-bold">Luna AI</strong>,
                            asisten virtual PuTI Telkom University Surabaya. 👋</p>
                        <p class="text-xs text-gray-600 dark:text-gray-300">
                            Saya siap membantu Anda menanyakan status tiket, memahami kendala jaringan Wi-Fi kampus, akun
                            iGracias/SSO, atau membantu merangkum tiket kendala Anda.
                        </p>
                        <!-- Quick Chips / Prompts (Affordance & Reduced Cognitive Load) -->
                        <div class="pt-2 border-t border-gray-100 dark:border-gray-700/60">
                            <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-2">Pilih Topik
                                Cepat:</p>
                            <div class="flex flex-wrap gap-2">
                                <button type="button"
                                    onclick="sendQuickPrompt('Bagaimana status tiket kendala saya yang terbaru?')"
                                    class="px-3 py-1.5 rounded-xl bg-gray-50 hover:bg-telkom-50 dark:bg-gray-700/50 dark:hover:bg-telkom-950/60 border border-gray-200 dark:border-gray-600 text-xs text-gray-700 dark:text-gray-200 hover:text-telkom-700 dark:hover:text-telkom-300 transition text-left">
                                    🔍 Cek status tiket saya
                                </button>
                                <button type="button"
                                    onclick="sendQuickPrompt('Wi-Fi kampus di lantai 2 kenapa lambat ya?')"
                                    class="px-3 py-1.5 rounded-xl bg-gray-50 hover:bg-telkom-50 dark:bg-gray-700/50 dark:hover:bg-telkom-950/60 border border-gray-200 dark:border-gray-600 text-xs text-gray-700 dark:text-gray-200 hover:text-telkom-700 dark:hover:text-telkom-300 transition text-left">
                                    📶 Kendala Wi-Fi lambat
                                </button>
                                <button type="button"
                                    onclick="sendQuickPrompt('Bagaimana prosedur reset kata sandi akun SSO iGracias?')"
                                    class="px-3 py-1.5 rounded-xl bg-gray-50 hover:bg-telkom-50 dark:bg-gray-700/50 dark:hover:bg-telkom-950/60 border border-gray-200 dark:border-gray-600 text-xs text-gray-700 dark:text-gray-200 hover:text-telkom-700 dark:hover:text-telkom-300 transition text-left">
                                    🔐 Reset sandi SSO iGracias
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Chat Input Footer (Affordance, Constraints, Instant Feedback) -->
            <div class="p-4 bg-white dark:bg-gray-900 border-t border-gray-150 dark:border-gray-800 shrink-0">
                <form id="chatForm" onsubmit="sendMessage(event)" class="flex items-center gap-2.5">
                    <div class="relative flex-1">
                        <input type="text" id="chatInput"
                            placeholder="Ketik kendala atau pertanyaan Anda di sini... (Tekan Enter untuk kirim)" required
                            autocomplete="off" oninput="handleInputState()"
                            class="w-full pl-4 pr-10 py-3.5 rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50/70 dark:bg-gray-800 text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:bg-white dark:focus:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-telkom-500/50 focus:border-telkom-500 transition shadow-inner">
                        <div
                            class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none hidden sm:block text-[11px] font-mono">
                            ↵
                        </div>
                    </div>

                    <!-- Submit Button (Affordance with disabled constraint) -->
                    <button type="submit" id="btnSend"
                        class="px-5 py-3.5 bg-telkom-600 hover:bg-telkom-700 active:scale-95 disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:bg-telkom-600 text-white rounded-2xl transition flex items-center justify-center gap-2 shadow-lg shadow-telkom-600/20 font-semibold text-sm shrink-0">
                        <span class="hidden sm:inline">Kirim</span>
                        <svg class="w-4 h-4 rotate-90" fill="none" stroke="currentColor" stroke-width="2.5"
                            viewBox="0 0 24 24">
                            <line x1="22" y1="2" x2="11" y2="13"></line>
                            <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                        </svg>
                    </button>
                </form>
                <p class="text-[11px] text-gray-400 text-center mt-2">
                    Luna AI menjaga kerahasiaan Anda. Jangan pernah membagikan password atau kredensial rahasia akun.
                </p>
            </div>

        </div>
    </section>
@endsection

@push('scripts')
    <script>
        let isSending = false;

        function handleInputState() {
            const input = document.getElementById('chatInput');
            const btn = document.getElementById('btnSend');
            if (!input || !btn) return;
            btn.disabled = isSending || !input.value.trim();
        }

        function sendQuickPrompt(promptText) {
            const input = document.getElementById('chatInput');
            if (!input) return;
            input.value = promptText;
            handleInputState();
            const fakeEvent = {
                preventDefault: () => {}
            };
            sendMessage(fakeEvent);
        }

        function sendMessage(event) {
            if (event && event.preventDefault) event.preventDefault();
            if (isSending) return;

            const input = document.getElementById('chatInput');
            const container = document.getElementById('chatContainer');
            const btn = document.getElementById('btnSend');
            const text = input.value.trim();
            if (!text) return;

            isSending = true;
            handleInputState();

            // 1. Append User Message (Telkom Red, Crisp Alignment)
            const userMsg = document.createElement('div');
            userMsg.className = 'flex gap-3 max-w-[85%] sm:max-w-[75%] ml-auto justify-end animate-fadeIn';
            userMsg.innerHTML = `
      <div class="bg-telkom-600 text-white p-4 rounded-3xl rounded-tr-sm shadow-md text-sm leading-relaxed">
        ${escapeHtml(text)}
      </div>
    `;
            container.appendChild(userMsg);
            input.value = '';
            container.scrollTop = container.scrollHeight;

            // 2. Append Typing Indicator (Feedback)
            const typingId = 'typing-' + Date.now();
            const typingMsg = document.createElement('div');
            typingMsg.id = typingId;
            typingMsg.className = 'flex gap-3 max-w-[85%] sm:max-w-[75%]';
            typingMsg.innerHTML = `
      <div class="w-9 h-9 rounded-xl bg-telkom-50 dark:bg-telkom-950/60 border border-telkom-100 dark:border-telkom-900/50 flex items-center justify-center text-telkom-600 dark:text-telkom-400 shrink-0 shadow-sm mt-0.5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
          <path d="M12 2a10 10 0 0 1 10 10c0 5.523-4.477 10-10 10S2 17.523 2 12c0-2.4 1-4.8 2.75-6.5" />
          <circle cx="12" cy="12" r="2" />
          <path d="m17 7-5 5" />
        </svg>
      </div>
      <div class="bg-white dark:bg-gray-800/90 px-4 py-3 rounded-3xl rounded-tl-sm border border-gray-200/80 dark:border-gray-700/80 shadow-sm text-xs text-gray-500 italic flex items-center gap-2">
        <span class="w-2 h-2 rounded-full bg-telkom-500 animate-ping"></span> Luna sedang menganalisis & mengetik...
      </div>
    `;
            container.appendChild(typingMsg);
            container.scrollTop = container.scrollHeight;

            // 3. Dispatch to API
            fetch('{{ route('ticket.luna-chat') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        message: text
                    })
                })
                .then(res => res.json())
                .then(data => {
                    const typingEl = document.getElementById(typingId);
                    if (typingEl) typingEl.remove();

                    const aiMsg = document.createElement('div');
                    aiMsg.className = 'flex gap-3 max-w-[85%] sm:max-w-[75%] animate-fadeIn';
                    aiMsg.innerHTML = `
        <div class="w-9 h-9 rounded-xl bg-telkom-50 dark:bg-telkom-950/60 border border-telkom-100 dark:border-telkom-900/50 flex items-center justify-center text-telkom-600 dark:text-telkom-400 shrink-0 shadow-sm mt-0.5">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path d="M12 2a10 10 0 0 1 10 10c0 5.523-4.477 10-10 10S2 17.523 2 12c0-2.4 1-4.8 2.75-6.5" />
            <circle cx="12" cy="12" r="2" />
            <path d="m17 7-5 5" />
          </svg>
        </div>
        <div class="bg-white dark:bg-gray-800/90 p-4 rounded-3xl rounded-tl-sm border border-gray-200/80 dark:border-gray-700/80 shadow-sm text-sm text-gray-800 dark:text-gray-200 leading-relaxed whitespace-pre-line">
          ${escapeHtml(data.reply || '')}
        </div>
      `;
                    container.appendChild(aiMsg);
                    container.scrollTop = container.scrollHeight;
                })
                .catch(() => {
                    const typingEl = document.getElementById(typingId);
                    if (typingEl) typingEl.remove();

                    const errEl = document.createElement('div');
                    errEl.className =
                        'p-3 rounded-2xl bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-900 text-xs text-red-600 dark:text-red-400 max-w-[80%]';
                    errEl.textContent =
                        'Gagal terhubung ke layanan Luna AI. Silakan coba kirim ulang atau buat tiket manual.';
                    container.appendChild(errEl);
                    container.scrollTop = container.scrollHeight;
                })
                .finally(() => {
                    isSending = false;
                    handleInputState();
                    input.focus();
                });
        }

        function escapeHtml(string) {
            return String(string)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        document.addEventListener('DOMContentLoaded', () => {
            handleInputState();
        });
    </script>
@endpush

@extends('layouts.app')

@section('title', 'Hubungi Bantuan CS & Live Chat - GameTopup')

@section('content')
  @php
    $faqs = App\Models\Faq::where('is_active', true)->orderBy('sort_order', 'asc')->get();
  @endphp

  <div class="flex-1 py-8" id="support-page">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      
      <!-- Page title banner -->
      <div class="text-center mb-10">
        <div class="inline-flex h-14 w-14 items-center justify-center rounded-2xl border border-white/55 neup-flat text-pink-655 mb-3.5 bg-white shadow-sm">
          <i data-lucide="help-circle" class="h-6 w-6 text-pink-600"></i>
        </div>
        <h1 class="text-xl md:text-2xl font-black text-slate-800 tracking-tight">Pusat Bantuan & Live Chat</h1>
        <p class="text-xs text-slate-500 mt-1 max-w-lg mx-auto font-bold leading-relaxed">Kami siap melayani kebutuhan informasi kendala top-up game Anda. Hubungi kami melalui Live Chat online di bawah.</p>
      </div>

      <!-- CORE GRID: Left (FAQs) & Right (Live Chat Hub) -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- LEFT: FAQ EXPLORE -->
        <div class="lg:col-span-7 space-y-6 text-left">
          <div class="rounded-3xl border border-white/50 neup-flat p-5 md:p-6 shadow-none bg-white shadow-sm">
            <div class="flex items-center gap-2.5 border-b border-slate-300/40 pb-3.5 mb-5 text-left">
              <i data-lucide="help-circle" class="h-5 w-5 text-pink-600"></i>
              <h3 class="text-sm font-black text-slate-800">Temukan Jawaban Cepat (FAQ)</h3>
            </div>

            <!-- Tabs list shortcuts -->
            <div class="flex flex-wrap gap-1.5 overflow-x-auto pb-1 scrollbar-none justify-start">
              <button onclick="filterFaq('all', this)" class="faq-tab-btn active text-[10px] md:text-xs font-black rounded-xl px-4 py-2.5 cursor-pointer transition-all border-none bg-[#ff007f] text-white shadow-sm">
                Semua Tanya Jawab
              </button>
              <button onclick="filterFaq('general', this)" class="faq-tab-btn text-[10px] md:text-xs font-black rounded-xl px-4 py-2.5 cursor-pointer transition-all bg-transparent text-slate-600 border border-white/20 neup-flat-sm hover:neup-pressed-sm">
                Umum & Proses
              </button>
              <button onclick="filterFaq('payment', this)" class="faq-tab-btn text-[10px] md:text-xs font-black rounded-xl px-4 py-2.5 cursor-pointer transition-all bg-transparent text-slate-600 border border-white/20 neup-flat-sm hover:neup-pressed-sm">
                Metode Pembayaran
              </button>
              <button onclick="filterFaq('refund', this)" class="faq-tab-btn text-[10px] md:text-xs font-black rounded-xl px-4 py-2.5 cursor-pointer transition-all bg-transparent text-slate-600 border border-white/20 neup-flat-sm hover:neup-pressed-sm">
                Refund & Salah ID
              </button>
            </div>

            <!-- FAQ Lists accordion -->
            <div class="space-y-3 mt-6 text-left" id="support-faq-lists">
              @foreach($faqs as $idx => $faq)
                <div class="faq-item rounded-2xl neup-flat border border-white/50 overflow-hidden text-left transition-all bg-white" data-category="{{ $faq->category }}">
                  <button onclick="toggleFaq({{ $idx }}, this)" class="w-full flex items-center justify-between p-5 text-left border-none bg-transparent cursor-pointer font-black text-xs text-slate-700">
                    <span>{{ $faq->question }}</span>
                    <i data-lucide="chevron-down" class="h-4.5 w-4.5 text-slate-400 transition-all dropdown-chevron"></i>
                  </button>
                  <div id="faq-ans-{{ $idx }}" class="faq-answer hidden px-5 pb-5 pt-1 text-xs text-slate-500 leading-relaxed font-semibold">
                    {{ $faq->answer }}
                  </div>
                </div>
              @endforeach
            </div>
          </div>

          <!-- Support contacts -->
          <div class="rounded-3xl border border-white/40 neup-flat p-5 text-left grid grid-cols-1 md:grid-cols-3 gap-5 bg-white shadow-sm">
            <div class="flex items-center gap-3">
              <span class="h-9 w-9 rounded-xl border border-white/40 bg-transparent neup-pressed-xs flex items-center justify-center text-pink-600 font-black flex-shrink-0"><i data-lucide="mail" class="h-4.5 w-4.5"></i></span>
              <div class="min-w-0">
                <p class="text-[9px] text-slate-400 font-black uppercase leading-none">Email Resmi</p>
                <p class="text-xs font-black text-slate-800 mt-1 truncate">support@gametopup.id</p>
              </div>
            </div>
            <div class="flex items-center gap-3">
              <span class="h-9 w-9 rounded-xl border border-white/40 bg-transparent neup-pressed-xs flex items-center justify-center text-emerald-600 font-bold flex-shrink-0"><i data-lucide="phone" class="h-4.5 w-4.5"></i></span>
              <div class="min-w-0">
                <p class="text-[9px] text-slate-400 font-black uppercase leading-none">Whatsapp Hotline</p>
                <p class="text-xs font-black text-slate-800 mt-1 truncate">+62 812-3456-7890</p>
              </div>
            </div>
            <div class="flex items-center gap-3">
              <span class="h-9 w-9 rounded-xl border border-white/40 bg-transparent neup-pressed-xs flex items-center justify-center text-indigo-600 font-bold flex-shrink-0"><i data-lucide="clock" class="h-4.5 w-4.5"></i></span>
              <div class="min-w-0">
                <p class="text-[9px] text-slate-400 font-black uppercase leading-none">Jam Kerja CS</p>
                <p class="text-xs font-black text-slate-800 mt-1 truncate">09:00 - 22:00 WIB</p>
              </div>
            </div>
          </div>

        </div>

        <!-- RIGHT: INTERACTIVE LIVE CHAT -->
        <div class="lg:col-span-5 max-w-md w-full mx-auto lg:max-w-none">
          <div class="border border-white/50 rounded-3xl overflow-hidden h-[460px] md:h-[520px] flex flex-col justify-between neup-flat bg-white shadow-sm">
            
            <!-- Chat Header details -->
            <div class="bg-slate-900 text-white p-4.5 flex items-center justify-between border-b border-black text-left">
              <div class="flex items-center gap-3 text-left">
                <div class="relative">
                  <img
                    src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&h=100&fit=crop&q=80"
                    alt="CS Representative"
                    class="h-10 w-10 rounded-xl object-cover border border-slate-700 ring-2 ring-emerald-500/20"
                  />
                  <span class="absolute bottom-0 right-0 h-2.5 w-2.5 rounded-full bg-emerald-500 ring-2 ring-slate-900" />
                </div>
                <div>
                  <h4 class="text-xs font-black text-white">Agen Jihan (CS Support)</h4>
                  <p class="text-[10px] text-emerald-400 font-bold flex items-center gap-1 mt-0.5">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Membalas Sangat Cepat (Aktif)
                  </p>
                </div>
              </div>

              <div class="text-right text-[9px] text-slate-400 font-bold">
                Game<span class="text-[#ff007f]">Topup</span>
              </div>
            </div>

            <!-- Chat Messages Body -->
            <div id="chat-messages-list" class="flex-1 p-4.5 overflow-y-auto space-y-4 bg-transparent scrollbar-none flex flex-col">
              
              <!-- Initial Welcome Message -->
              <div class="flex flex-col gap-1 max-w-[80%] self-start text-left">
                <div class="rounded-2xl p-3 text-xs font-bold bg-slate-100 text-slate-700 rounded-tl-none">
                  Halo! Selamat datang di Layanan Bantuan Client GameTopup. Ada yang bisa kami bantu mengenai transaksi atau kendala top up Anda hari ini?
                </div>
                <span class="text-[8px] text-slate-400 font-bold pl-1">10:30 CS Support</span>
              </div>

            </div>

            <!-- Chat Input form Footer -->
            <form id="chat-input-form" onsubmit="sendChatMessage(event)" class="p-3 border-t border-slate-300/30 bg-transparent flex gap-2.5 m-0">
              <input
                type="text"
                id="chat-input-text"
                placeholder="Ketik pesan kendala top up di sini..."
                class="flex-1 rounded-2xl border border-white/30 neup-pressed-xs px-4 py-3 text-xs font-bold text-slate-800 placeholder:text-slate-400 focus:outline-none bg-transparent"
                oninput="toggleSendBtn(this)"
              />
              
              <button
                type="submit"
                id="btn-send-message"
                disabled
                class="opacity-40 cursor-not-allowed border border-white/20 neup-flat-sm flex h-11 w-11 items-center justify-center rounded-2xl transition-all text-slate-400 bg-transparent"
              >
                <i data-lucide="send" class="h-4.5 w-4.5"></i>
              </button>
            </form>

          </div>
        </div>

      </div>

    </div>
  </div>

  <script>
    // FAQ tab filter
    function filterFaq(category, btn) {
      document.querySelectorAll('.faq-tab-btn').forEach(b => {
        b.className = "faq-tab-btn text-[10px] md:text-xs font-black rounded-xl px-4 py-2.5 cursor-pointer transition-all bg-transparent text-slate-600 border border-white/20 neup-flat-sm hover:neup-pressed-sm";
        b.style.background = 'transparent';
        b.style.color = '#475569';
      });

      btn.className = "faq-tab-btn active text-[10px] md:text-xs font-black rounded-xl px-4 py-2.5 cursor-pointer transition-all border-none bg-[#ff007f] text-white shadow-sm";
      btn.style.background = '#ff007f';
      btn.style.color = '#ffffff';

      document.querySelectorAll('.faq-item').forEach(item => {
        const cat = item.getAttribute('data-category');
        if (category === 'all' || cat === category) {
          item.style.display = 'block';
        } else {
          item.style.display = 'none';
        }
      });
    }

    // Toggle FAQ answers
    function toggleFaq(idx, btn) {
      const ans = document.getElementById(`faq-ans-${idx}`);
      const chevron = btn.querySelector('.dropdown-chevron');
      if (ans) {
        ans.classList.toggle('hidden');
        const isHidden = ans.classList.contains('hidden');
        if (chevron) {
          chevron.style.transform = isHidden ? 'rotate(0deg)' : 'rotate(180deg)';
        }
      }
    }

    // Enable/Disable Send Button based on input
    function toggleSendBtn(input) {
      const btn = document.getElementById('btn-send-message');
      if (input.value.trim().length > 0) {
        btn.removeAttribute('disabled');
        btn.classList.remove('opacity-40', 'cursor-not-allowed');
        btn.classList.add('text-indigo-650', 'hover:neup-pressed-sm', 'cursor-pointer');
      } else {
        btn.setAttribute('disabled', 'true');
        btn.classList.add('opacity-40', 'cursor-not-allowed');
        btn.classList.remove('text-indigo-650', 'hover:neup-pressed-sm', 'cursor-pointer');
      }
    }

    // Escape HTML to prevent XSS
    function escapeHtml(text) {
      const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
      };
      return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }

    let lastMessageId = 0;
    let isPolling = false;

    function appendMessageToUI(msg) {
      const list = document.getElementById('chat-messages-list');
      const time = new Date(msg.created_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
      
      const div = document.createElement('div');
      if (msg.sender_type === 'customer') {
        div.className = 'flex flex-col gap-1 max-w-[80%] self-end text-right mt-2';
        div.innerHTML = `
          <div class="rounded-2xl p-3 text-xs font-bold bg-[#ff007f] text-white rounded-tr-none shadow-sm">
            ${escapeHtml(msg.message)}
          </div>
          <span class="text-[8px] text-slate-400 font-bold pr-1">${time} Saya</span>
        `;
      } else {
        div.className = 'flex flex-col gap-1 max-w-[80%] self-start text-left mt-2';
        div.innerHTML = `
          <div class="rounded-2xl p-3 text-xs font-bold bg-slate-100 text-slate-700 rounded-tl-none border border-slate-200/50 shadow-sm">
            ${escapeHtml(msg.message)}
          </div>
          <span class="text-[8px] text-slate-400 font-bold pl-1">${time} CS Support</span>
        `;
      }
      
      list.appendChild(div);
      list.scrollTop = list.scrollHeight;
    }

    function pollMessages() {
      if (isPolling) return;
      isPolling = true;

      fetch('{{ route("support.chat.messages") }}')
        .then(res => res.json())
        .then(data => {
          isPolling = false;
          if (data.status === 'success' && data.messages.length > 0) {
            data.messages.forEach(msg => {
              if (msg.id > lastMessageId) {
                appendMessageToUI(msg);
                lastMessageId = msg.id;
              }
            });
          }
        })
        .catch(() => {
          isPolling = false;
        });
    }

    // Initialize chat polling
    pollMessages();
    setInterval(pollMessages, 3000);

    // Send customer message via AJAX
    function sendChatMessage(event) {
      event.preventDefault();
      const input = document.getElementById('chat-input-text');
      const text = input.value.trim();
      if (!text) return;

      // Optimistically clean the field
      input.value = '';
      toggleSendBtn(input);

      fetch('{{ route("support.chat") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ message: text })
      })
      .then(res => res.json())
      .then(data => {
        pollMessages(); // Fetch the new messages immediately
      })
      .catch(err => {
        console.error('Error sending message:', err);
      });
    }
  </script>
@endsection

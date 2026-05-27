@extends('layouts.admin')

@section('title', 'Admin Panel - Live Support Console')

@section('content')
  <div class="text-left animate-fade-in h-[calc(100vh-160px)] md:h-[calc(100vh-120px)] flex flex-col">
    <!-- Top Header -->
    <div class="border-b border-slate-800 pb-4 mb-5 flex-shrink-0 flex items-center justify-between">
      <div>
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/30 text-[10px] font-extrabold uppercase text-cyan-400 shadow-sm shadow-blue-500/5">
          <i data-lucide="message-square" class="h-3.5 w-3.5"></i> LIVE SUPPORT CONSOLE
        </span>
        <h1 class="text-xl font-black mt-1.5 tracking-tight text-white">Layanan Chat Bantuan Pelanggan</h1>
      </div>
      <div id="connection-status-badge" class="rounded bg-emerald-500/10 border border-emerald-500/25 px-2.5 py-1 text-[9px] font-black text-emerald-400 uppercase tracking-widest flex items-center gap-1.5 shadow-sm">
        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span> ONLINE
      </div>
    </div>

    <!-- THREE-COLUMN LIVE CHAT HUB -->
    <div class="flex-1 min-h-0 grid grid-cols-1 lg:grid-cols-12 border border-slate-800 rounded-3xl overflow-hidden bg-[#111827]/75 backdrop-blur-xl shadow-2xl items-stretch">
      
      <!-- COLUMN 1: LEFT CONVERSATION LIST (Col 3) -->
      <div class="lg:col-span-3 border-r border-slate-800 flex flex-col justify-between">
        <!-- Search bar -->
        <div class="p-4 border-b border-slate-800 flex-shrink-0">
          <div class="relative">
            <i data-lucide="search" class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500"></i>
            <input
              type="text"
              id="customer-search-input"
              placeholder="Cari customer..."
              class="w-full rounded-xl border border-slate-700 bg-slate-800 text-white py-2 pl-9 pr-3 text-xs font-semibold focus:outline-none placeholder:text-slate-500"
              oninput="filterConversations(this)"
            />
          </div>
        </div>

        <!-- Conversations Scroll Feed -->
        <div id="conversations-list-container" class="flex-1 overflow-y-auto p-2 space-y-1.5 scrollbar-none">
          <!-- Loading states -->
          <div class="py-12 text-center text-slate-500 font-bold text-xs animate-pulse">
            Memuat daftar percakapan...
          </div>
        </div>
      </div>

      <!-- COLUMN 2: CENTER ACTIVE CHAT PANEL (Col 6) -->
      <div class="lg:col-span-6 flex flex-col justify-between items-stretch border-r border-slate-800 min-w-0">
        
        <!-- Header details -->
        <div id="active-chat-header" class="p-4 border-b border-slate-800 bg-slate-900/30 flex items-center justify-between text-left flex-shrink-0">
          <div class="flex items-center gap-3">
            <div class="h-10 w-10 rounded-xl bg-slate-800 border border-slate-700 flex items-center justify-center text-cyan-400 font-black">
              <i data-lucide="user" class="h-5 w-5"></i>
            </div>
            <div>
              <h4 id="active-customer-name" class="text-xs font-black text-white">Pilih Percakapan</h4>
              <p id="active-customer-status" class="text-[9px] text-slate-400 mt-0.5 font-bold">Silakan klik salah satu obrolan aktif di sebelah kiri.</p>
            </div>
          </div>
          <button id="close-conversation-btn" onclick="closeActiveConversation()" disabled class="hidden opacity-30 border border-slate-700 bg-slate-800 hover:bg-slate-700 text-slate-300 font-extrabold rounded-xl px-3 py-1.5 text-[9px] cursor-pointer active:scale-95 shadow-sm">
            Tutup Chat
          </button>
        </div>

        <!-- Chat messages feed -->
        <div id="active-messages-feed" class="flex-1 overflow-y-auto p-5 space-y-4 bg-transparent scrollbar-none flex flex-col">
          <!-- Blank state -->
          <div class="flex-1 flex flex-col items-center justify-center py-16 text-center text-slate-500 font-bold text-xs">
            <i data-lucide="message-square" class="h-12 w-12 text-slate-700 mb-2"></i>
            <span>Pilih salah satu customer untuk mulai melayani bantuan.</span>
          </div>
        </div>

        <!-- Message input footer -->
        <form id="chat-input-form" onsubmit="sendSupportReply(event)" class="p-3 border-t border-slate-800 bg-slate-900/10 flex gap-2 flex-shrink-0 m-0">
          <input
            type="text"
            id="chat-input-text"
            disabled
            placeholder="Pilih percakapan terlebih dahulu..."
            class="flex-1 rounded-2xl border border-slate-700 bg-slate-800 text-white px-4 py-3 text-xs font-semibold focus:outline-none placeholder:text-slate-500"
            oninput="toggleSendBtn(this)"
          />
          <button
            type="submit"
            id="btn-send-reply"
            disabled
            class="opacity-40 cursor-not-allowed border border-slate-700 bg-slate-800 flex h-11 w-11 items-center justify-center rounded-2xl text-slate-500"
          >
            <i data-lucide="send" class="h-4.5 w-4.5"></i>
          </button>
        </form>

      </div>

      <!-- COLUMN 3: RIGHT SIDEBAR PROFILE & PURCHASES (Col 3) -->
      <div id="active-customer-profile-panel" class="lg:col-span-3 p-5 overflow-y-auto scrollbar-none flex flex-col justify-start gap-5 text-left border-t lg:border-t-0 border-slate-800">
        <div class="h-full flex flex-col items-center justify-center py-16 text-center text-slate-500 font-bold text-xs">
          <i data-lucide="user-check" class="h-10 w-10 text-slate-700 mb-2"></i>
          <span>Profil customer akan ditampilkan di sini.</span>
        </div>
      </div>

    </div>
  </div>
@endsection

@push('scripts')
  <script>
    let activeConversationId = null;
    let conversations = [];
    let conversationsPollInterval = null;
    let messagesPollInterval = null;

    document.addEventListener('DOMContentLoaded', () => {
      // 1. Initial Load Conversations
      fetchConversations();

      // 2. Poll Conversations List every 5 seconds to keep unread count and sorting active
      conversationsPollInterval = setInterval(fetchConversations, 5000);
    });

    // Fetch Conversations list from server via JSON
    function fetchConversations() {
      fetch('{{ route("admin.chat.conversations") }}')
        .then(res => res.json())
        .then(data => {
          conversations = data;
          renderConversations();
        })
        .catch(err => console.error('Gagal memuat obrolan:', err));
    }

    // Render Conversation items in left sidebar
    function renderConversations() {
      const container = document.getElementById('conversations-list-container');
      if (!container) return;

      const query = document.getElementById('customer-search-input')?.value.toLowerCase().trim() || '';

      const filtered = conversations.filter(c => {
        return c.guest_name.toLowerCase().includes(query) || (c.guest_email && c.guest_email.toLowerCase().includes(query));
      });

      if (filtered.length === 0) {
        container.innerHTML = `
          <div class="py-12 text-center text-slate-500 font-bold text-xs">
            Tidak ada chat yang sesuai.
          </div>
        `;
        return;
      }

      container.innerHTML = filtered.map(c => {
        const isActive = c.id === activeConversationId;
        const isClosed = c.status === 'closed';
        const hasUnread = c.unread_count > 0;
        
        return `
          <div 
            onclick="selectConversation(${c.id})" 
            class="p-3 rounded-2xl border cursor-pointer transition-all flex items-center justify-between gap-3 text-left
              ${isActive 
                ? 'bg-blue-500/10 border-blue-500/30 text-white' 
                : 'bg-slate-900/20 border-slate-850 hover:bg-slate-800/40 text-slate-300'}"
          >
            <div class="flex items-center gap-2.5 min-w-0">
              <div class="h-9 w-9 rounded-xl bg-slate-800 flex items-center justify-center flex-shrink-0 text-cyan-400 font-black relative">
                <i data-lucide="user" class="h-4.5 w-4.5"></i>
                ${!isClosed ? `<span class="absolute bottom-0 right-0 h-2.5 w-2.5 rounded-full bg-emerald-500 ring-2 ring-[#0d1324]" />` : ''}
              </div>
              <div class="min-w-0 text-left">
                <h4 class="text-xs font-black truncate ${hasUnread ? 'text-white font-extrabold' : 'text-slate-200'}">${c.guest_name}</h4>
                <p class="text-[10px] text-slate-400 truncate mt-0.5 leading-none font-semibold">${c.latest_message}</p>
              </div>
            </div>
            
            <div class="flex flex-col items-end gap-1 flex-shrink-0">
              <span class="text-[8px] text-slate-550 font-bold font-mono">${c.latest_message_time}</span>
              ${hasUnread 
                ? `<span class="h-4 min-w-4 rounded-full bg-cyan-400 px-1 text-[8px] font-black text-[#090e1a] flex items-center justify-center animate-bounce shadow-sm">${c.unread_count}</span>` 
                : ''}
              ${isClosed ? `<span class="text-[7px] bg-slate-800 border border-slate-700 text-slate-450 px-1 py-0.5 rounded font-black uppercase">CLOSED</span>` : ''}
            </div>
          </div>
        `;
      }).join('');

      if (window.lucide) window.lucide.createIcons();
    }

    // Filter conversations dynamically as typing
    function filterConversations(input) {
      renderConversations();
    }

    // Select active conversation and trigger messages poll
    function selectConversation(id) {
      activeConversationId = id;
      renderConversations();

      // Clear existing messages polling and start fresh
      if (messagesPollInterval) clearInterval(messagesPollInterval);

      // Instantly load messages once
      fetchMessages();

      // Start message feed polling every 3 seconds
      messagesPollInterval = setInterval(fetchMessages, 3000);

      // Enable text input and focus
      const input = document.getElementById('chat-input-text');
      if (input) {
        input.removeAttribute('disabled');
        input.placeholder = 'Ketik balasan CS di sini...';
        input.focus();
      }
    }

    // Fetch messages from server via JSON
    function fetchMessages() {
      if (!activeConversationId) return;

      fetch(`/admin/chat/messages/${activeConversationId}`)
        .then(res => res.json())
        .then(data => {
          renderMessagesFeed(data.messages, data.conversation);
          renderCustomerProfile(data.conversation, data.tx_history);
        })
        .catch(err => console.error('Gagal memuat pesan:', err));
    }

    // Render message bubbles in center panel
    function renderMessagesFeed(messages, conv) {
      const feed = document.getElementById('active-messages-feed');
      if (!feed) return;

      // Update header
      document.getElementById('active-customer-name').innerText = conv.guest_name;
      const statusBadge = document.getElementById('active-customer-status');
      const isClosed = conv.status === 'closed';

      if (isClosed) {
        statusBadge.innerHTML = `<span class="text-rose-400 font-extrabold">STATUS: PERCAKAPAN DITUTUP (ARCHIVED)</span>`;
      } else {
        statusBadge.innerHTML = `<span class="h-1.5 w-1.5 inline-block rounded-full bg-emerald-500 animate-pulse mr-1"></span> Customer sedang terhubung aktif`;
      }

      // Close button state
      const closeBtn = document.getElementById('close-conversation-btn');
      if (closeBtn) {
        closeBtn.removeAttribute('disabled');
        closeBtn.classList.remove('opacity-30', 'hidden');
        if (isClosed) {
          closeBtn.classList.add('hidden');
        }
      }

      // Input field state
      const input = document.getElementById('chat-input-text');
      if (input && isClosed) {
        input.setAttribute('disabled', 'true');
        input.placeholder = 'Percakapan telah ditutup.';
      }

      if (messages.length === 0) {
        feed.innerHTML = `
          <div class="flex-1 flex flex-col items-center justify-center py-12 text-center text-slate-500 font-bold text-xs">
            <i data-lucide="message-square" class="h-10 w-10 text-slate-700 mb-2"></i>
            <span>Belum ada isi pesan percakapan di sini.</span>
          </div>
        `;
        return;
      }

      // Track scroll position to auto-scroll if near bottom
      const isAtBottom = feed.scrollHeight - feed.scrollTop - feed.clientHeight < 120;

      feed.innerHTML = messages.map(m => {
        const isAdmin = m.sender_type === 'admin';
        const bubbleBg = isAdmin 
          ? 'bg-gradient-to-r from-blue-600 to-cyan-500 text-white rounded-tr-none' 
          : 'bg-slate-800 text-slate-200 rounded-tl-none border border-slate-750';
        const alignment = isAdmin ? 'self-end text-right' : 'self-start text-left';
        
        const timestamp = new Date(m.created_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });

        return `
          <div class="flex flex-col gap-1 max-w-[80%] ${alignment}">
            <div class="rounded-2xl p-3 text-xs font-semibold leading-relaxed ${bubbleBg}">
              ${m.message}
            </div>
            <span class="text-[8px] text-slate-500 font-mono font-bold px-1">${timestamp} ${isAdmin ? 'Admin' : 'Customer'}</span>
          </div>
        `;
      }).join('');

      // Auto scroll
      if (isAtBottom) {
        feed.scrollTop = feed.scrollHeight;
      }

      if (window.lucide) window.lucide.createIcons();
    }

    // Render Customer profile and purchase history in right sidebar
    function renderCustomerProfile(conv, history) {
      const panel = document.getElementById('active-customer-profile-panel');
      if (!panel) return;

      const emailVal = conv.guest_email ? conv.guest_email : 'Tamu (No Email)';
      const joinDate = new Date(conv.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });

      let listHTML = history.map(tx => {
        let statusBadge = '';
        if (tx.status === 'success') {
          statusBadge = '<span class="rounded bg-emerald-500/10 border border-emerald-500/20 px-1.5 py-0.5 text-[7px] font-black text-emerald-400 uppercase tracking-wider">PAID</span>';
        } else if (tx.status === 'failed') {
          statusBadge = '<span class="rounded bg-rose-500/10 border border-rose-500/20 px-1.5 py-0.5 text-[7px] font-black text-rose-400 uppercase tracking-wider">FAILED</span>';
        } else {
          statusBadge = '<span class="rounded bg-amber-500/10 border border-amber-500/20 px-1.5 py-0.5 text-[7px] font-black text-amber-400 uppercase tracking-wider animate-pulse">PENDING</span>';
        }

        return `
          <div class="p-2.5 rounded-xl bg-slate-900/40 border border-slate-850 flex items-center justify-between text-[10px] gap-2">
            <div class="min-w-0">
              <a href="/admin/transactions/${tx.id}" target="_blank" class="font-mono font-black text-slate-200 hover:text-cyan-400 hover:underline decoration-none truncate block">${tx.invoice}</a>
              <p class="text-[8px] text-slate-500 mt-0.5 font-bold truncate">${tx.game.name} • ${tx.nominal_name}</p>
            </div>
            <div class="text-right flex flex-col items-end gap-1 flex-shrink-0">
              <span class="font-mono font-black text-cyan-400">Rp ${tx.total_payment.toLocaleString('id-ID')}</span>
              ${statusBadge}
            </div>
          </div>
        `;
      }).join('');

      if (history.length === 0) {
        listHTML = `
          <p class="text-[10px] text-slate-500 font-bold py-6 text-center">Belum ada riwayat transaksi.</p>
        `;
      }

      panel.innerHTML = `
        <!-- Profile info -->
        <div class="flex flex-col items-center justify-center text-center pb-4 border-b border-slate-800">
          <div class="h-12 w-12 rounded-2xl bg-cyan-500/10 border border-cyan-500/25 flex items-center justify-center text-cyan-400 font-black mb-3">
            <i data-lucide="user-check" class="h-6 w-6"></i>
          </div>
          <h4 class="text-xs font-black text-white truncate max-w-full">${conv.guest_name}</h4>
          <span class="inline-block rounded-full bg-slate-800 text-slate-400 px-2.5 py-0.5 text-[8px] font-extrabold uppercase mt-1.5 tracking-wider border border-slate-700">
            ${conv.user_id ? 'REGISTERED MEMBER' : 'GUEST CLIENT'}
          </span>
        </div>

        <!-- Contact detail metadata -->
        <div class="space-y-3.5 text-[10px]">
          <div>
            <p class="text-slate-500 font-extrabold uppercase tracking-widest text-[8px]">Kontak Bantuan</p>
            <p class="text-slate-200 font-bold truncate mt-1">${emailVal}</p>
          </div>
          <div>
            <p class="text-slate-500 font-extrabold uppercase tracking-widest text-[8px]">Mulai Percakapan</p>
            <p class="text-slate-200 font-bold mt-1">${joinDate}</p>
          </div>
        </div>

        <!-- Purchase records -->
        <div class="pt-4 border-t border-slate-800 flex-1 flex flex-col justify-start">
          <p class="text-slate-400 font-black uppercase tracking-wider text-[9px] mb-3.5 flex items-center gap-1">
            <i data-lucide="shopping-bag" class="h-3.5 w-3.5 text-cyan-400"></i> Riwayat Belanja (Terakhir)
          </p>
          
          <div class="space-y-2 flex-1 overflow-y-auto max-h-56 scrollbar-none pr-1">
            ${listHTML}
          </div>
        </div>
      `;

      if (window.lucide) window.lucide.createIcons();
    }

    // Toggle Send button activation
    function toggleSendBtn(input) {
      const btn = document.getElementById('btn-send-reply');
      if (input.value.trim().length > 0) {
        btn.removeAttribute('disabled');
        btn.classList.remove('opacity-40', 'cursor-not-allowed');
        btn.classList.add('text-cyan-400', 'bg-blue-500/10', 'border-blue-500/30', 'cursor-pointer');
      } else {
        btn.setAttribute('disabled', 'true');
        btn.classList.add('opacity-40', 'cursor-not-allowed');
        btn.classList.remove('text-cyan-400', 'bg-blue-500/10', 'border-blue-500/30', 'cursor-pointer');
      }
    }

    // Post reply message to server
    function sendSupportReply(event) {
      event.preventDefault();
      if (!activeConversationId) return;

      const input = document.getElementById('chat-input-text');
      const text = input.value.trim();
      if (!text) return;

      // Clear input instantly for snappy feeling
      input.value = '';
      toggleSendBtn(input);

      // Instantly append local message bubble locally in UI
      const feed = document.getElementById('active-messages-feed');
      const time = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
      const userDiv = document.createElement('div');
      userDiv.className = 'flex flex-col gap-1 max-w-[80%] self-end text-right';
      userDiv.innerHTML = `
        <div class="rounded-2xl p-3 text-xs font-semibold leading-relaxed bg-gradient-to-r from-blue-600 to-cyan-500 text-white rounded-tr-none shadow-sm">
          ${text}
        </div>
        <span class="text-[8px] text-slate-500 font-mono font-bold px-1">${time} Admin</span>
      `;
      feed.appendChild(userDiv);
      feed.scrollTop = feed.scrollHeight;

      // Send to server in background
      fetch('{{ route("admin.chat.send") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
          conversation_id: activeConversationId,
          message: text
        })
      })
      .then(res => res.json())
      .then(data => {
        // Refresh conversations list to update unread/latest tags
        fetchConversations();
      })
      .catch(err => console.error('Gagal mengirim balasan:', err));
    }

    // Close active conversation
    function closeActiveConversation() {
      if (!activeConversationId) return;
      if (!confirm('Apakah Anda yakin ingin menyelesaikan dan menutup percakapan bantuan CS ini? Status akan diubah menjadi CLOSED.')) return;

      fetch(`/admin/chat/close/${activeConversationId}`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      })
      .then(res => res.json())
      .then(data => {
        // Refresh lists
        fetchConversations();
        selectConversation(activeConversationId);
      })
      .catch(err => console.error('Gagal menutup obrolan:', err));
    }
  </script>
@endpush

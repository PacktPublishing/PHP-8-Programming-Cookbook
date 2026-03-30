<?php
// instructions:
/*
File structure                                                                                                                                                                      
                                                                                                                                                                                        
IndoChat/
├── async
│   └── server.php
├── ChatServer.php
├── config
│   └── config.php
├── data
│   └── users.json
├── Platform
│   ├── OpenAi.php
│   └── PlatformInterface.php
└── public
    ├── index.php
    └── users.php

                                                                                                                                                                                        
  How to run                                                                                                                                                                            
                                                                                                                                                                                        
  1. Set your GenAI API key:                                                                                                                  
  Save your API key into /path/to/IndoChat/secure/api_key.txt
                                                                                                                                                                                        
  2. Configure settings in /config/config.php to match your preferred GenAI platform

  3. Start the WebSocket server from a terminal window:
  cd /path/to/cookbook
  # Linux/Mac
  admin.sh shell php8
  # Windows
  admin.ps1 shell php8
  # Async server
  cd /path/to/cookbook
  # Linux/Mac
  admin.sh shell php8
  # Windows
  admin.ps1 shell php8
  # Start backend async service
  php /repo/src/IndoChat/async/server.php
                
  4. Serve the frontend from another terminal windows:
  cd /path/to/cookbook
  # Linux/Mac
  admin.sh shell php8
  # Windows
  admin.ps1 shell php8
  # Async server
  cd /path/to/cookbook
  # Linux/Mac
  admin.sh shell php8
  # Windows
  admin.ps1 shell php8
  # Start "public" server
  php -S 0.0.0.0:8889 -t /repo/src//IndoChat/public
                                                
  Then open two browser tabs at http://localhost:8889:         
  - Tab 1 — enter username Bog, select English → Start Chatting       
  - Tab 2 — enter username Nimol, select Khmer → Start Chatting                                                                                                                           
                                                                                                                                                                                        
  Each tab's dropdown will show the other user. Select them, type a message, and the Claude API translates it in real time. Both users see the original text and the translation        
  side-by-side in styled chat bubbles.                                                                                                                                                  
   
  Key behaviours                                                                                                                                                                        
                                                            
  - WebSocket connection auto-reconnects on drop                                                                                                                                        
  - The users dropdown refreshes via AJAX on change events and every 8 seconds
  - If the chosen user disconnects mid-conversation, a notification appears and the chat disables                                                                                       
  - Stop button returns to the setup form (pre-filled) so the user can start a fresh conversation                                                                                       
  - Translation uses claude-haiku-4-5-20251001 for speed; falls back to a readable error if the API key is missing      
 */
require_once __DIR__ . '/../config/config.php';
error_log(USERS_FILE);
$wsPort = WS_PORT;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>IndoChat — Real-time Multilingual Chat</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Noto+Sans+Khmer:wght@400;600&display=swap" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<style>
/* ── Reset & variables ───────────────────────────────────────────────────── */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
  --indigo:     #4F46E5;
  --indigo-dk:  #3730A3;
  --violet:     #7C3AED;
  --teal:       #0D9488;
  --teal-lt:    #CCFBF1;
  --surface:    #F8FAFF;
  --card:       #FFFFFF;
  --border:     #E2E8F0;
  --muted:      #64748B;
  --text:       #1E293B;
  --danger:     #EF4444;
  --sent-bg:    #4F46E5;
  --sent-text:  #FFFFFF;
  --recv-bg:    #F1F5F9;
  --recv-text:  #1E293B;
  --radius:     14px;
  --shadow:     0 4px 24px rgba(79,70,229,.12);
  --shadow-sm:  0 2px 8px rgba(0,0,0,.08);
}

body {
  font-family: 'Inter', 'Noto Sans Khmer', sans-serif;
  background: var(--surface);
  color: var(--text);
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

/* ── Header ─────────────────────────────────────────────────────────────── */
#app-header {
  background: linear-gradient(135deg, var(--indigo-dk) 0%, var(--indigo) 45%, var(--violet) 100%);
  color: #fff;
  padding: 0 24px;
  height: 64px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  box-shadow: 0 2px 16px rgba(79,70,229,.35);
  position: sticky;
  top: 0;
  z-index: 100;
}
#app-header .brand {
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 1.35rem;
  font-weight: 700;
  letter-spacing: -.3px;
}
#app-header .brand .globe { font-size: 1.5rem; }
#app-header .brand small {
  font-weight: 300;
  font-size: .75rem;
  opacity: .75;
  display: block;
  letter-spacing: .2px;
}
#status-indicator {
  display: flex;
  align-items: center;
  gap: 7px;
  font-size: .8rem;
  opacity: .9;
}
#status-dot {
  width: 9px; height: 9px;
  border-radius: 50%;
  background: #6EE7B7;
  box-shadow: 0 0 0 2px rgba(110,231,183,.35);
  transition: background .3s;
}
#status-dot.offline { background: #FCA5A5; box-shadow: 0 0 0 2px rgba(252,165,165,.35); }

/* ── Notification toast ──────────────────────────────────────────────────── */
#toast {
  position: fixed;
  top: 80px; right: 20px;
  background: #1E293B;
  color: #fff;
  padding: 10px 18px;
  border-radius: 10px;
  font-size: .875rem;
  box-shadow: var(--shadow);
  display: none;
  z-index: 999;
  max-width: 340px;
  animation: slideIn .25s ease;
}
#toast.error  { background: var(--danger); }
#toast.info   { background: var(--teal); }
@keyframes slideIn { from { opacity:0; transform: translateY(-10px); } to { opacity:1; transform: translateY(0); } }

/* ── Layout ─────────────────────────────────────────────────────────────── */
#content {
  flex: 1;
  display: flex;
  align-items: flex-start;
  justify-content: center;
  padding: 40px 20px;
}

/* ── Setup panel ─────────────────────────────────────────────────────────── */
#setup-panel {
  width: 100%;
  max-width: 460px;
}
.card {
  background: var(--card);
  border-radius: var(--radius);
  box-shadow: var(--shadow);
  padding: 36px 40px;
  border: 1px solid var(--border);
}
.card-title {
  font-size: 1.25rem;
  font-weight: 700;
  margin-bottom: 6px;
  color: var(--text);
}
.card-subtitle {
  font-size: .875rem;
  color: var(--muted);
  margin-bottom: 28px;
}
.form-group { margin-bottom: 20px; }
.form-group label {
  display: block;
  font-size: .8125rem;
  font-weight: 600;
  color: var(--muted);
  text-transform: uppercase;
  letter-spacing: .5px;
  margin-bottom: 8px;
}
input[type=text] {
  width: 100%;
  padding: 11px 14px;
  border: 1.5px solid var(--border);
  border-radius: 9px;
  font-size: .9375rem;
  font-family: inherit;
  color: var(--text);
  background: #FAFBFF;
  transition: border-color .2s, box-shadow .2s;
  outline: none;
}
input[type=text]:focus {
  border-color: var(--indigo);
  box-shadow: 0 0 0 3px rgba(79,70,229,.15);
  background: #fff;
}
.radio-group {
  display: flex;
  gap: 12px;
  flex-wrap: wrap;
}
.radio-option {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 10px 16px;
  border: 1.5px solid var(--border);
  border-radius: 9px;
  cursor: pointer;
  transition: all .2s;
  font-size: .9375rem;
  user-select: none;
  flex: 1;
  min-width: 130px;
}
.radio-option:hover { border-color: var(--indigo); background: #F5F5FF; }
.radio-option input[type=radio] { accent-color: var(--indigo); cursor: pointer; }
.radio-option.selected { border-color: var(--indigo); background: #EEEEFF; }
.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 7px;
  padding: 11px 22px;
  border: none;
  border-radius: 9px;
  font-size: .9375rem;
  font-weight: 600;
  font-family: inherit;
  cursor: pointer;
  transition: all .2s;
  outline: none;
}
.btn:active { transform: scale(.97); }
.btn-primary {
  background: linear-gradient(135deg, var(--indigo), var(--violet));
  color: #fff;
  width: 100%;
  padding: 13px;
  font-size: 1rem;
  box-shadow: 0 4px 14px rgba(79,70,229,.35);
}
.btn-primary:hover { box-shadow: 0 6px 20px rgba(79,70,229,.45); transform: translateY(-1px); }
.btn-primary:disabled { opacity: .6; cursor: not-allowed; transform: none; }
.btn-secondary {
  background: var(--recv-bg);
  color: var(--muted);
  font-size: .8125rem;
  padding: 7px 14px;
}
.btn-secondary:hover { background: #E2E8F0; color: var(--text); }
.btn-danger {
  background: #FEE2E2;
  color: var(--danger);
  font-size: .875rem;
  padding: 8px 18px;
}
.btn-danger:hover { background: #FCA5A5; color: #fff; }
.btn-send {
  background: var(--indigo);
  color: #fff;
  padding: 10px 22px;
}
.btn-send:hover { background: var(--indigo-dk); }
.btn-send:disabled { opacity: .5; cursor: not-allowed; }

/* ── Main chat panel ─────────────────────────────────────────────────────── */
#main-panel {
  display: none;
  width: 100%;
  max-width: 1100px;
  gap: 20px;
  align-items: flex-start;
}
#main-panel.active { display: flex; }

/* ── Sidebar ─────────────────────────────────────────────────────────────── */
#sidebar {
  width: 280px;
  flex-shrink: 0;
  display: flex;
  flex-direction: column;
  gap: 16px;
}
.sidebar-card {
  background: var(--card);
  border-radius: var(--radius);
  box-shadow: var(--shadow-sm);
  border: 1px solid var(--border);
  padding: 20px;
}
.sidebar-card h3 {
  font-size: .75rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: .6px;
  color: var(--muted);
  margin-bottom: 14px;
}
.user-badge {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px 12px;
  background: linear-gradient(135deg, #EEEEFF, #F5F0FF);
  border-radius: 10px;
}
.avatar {
  width: 38px; height: 38px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  font-size: .9rem;
  color: #fff;
  background: linear-gradient(135deg, var(--indigo), var(--violet));
  flex-shrink: 0;
}
.user-badge .info .name { font-weight: 600; font-size: .9375rem; }
.user-badge .info .lang { font-size: .75rem; color: var(--muted); margin-top: 1px; }

#users-select {
  width: 100%;
  padding: 10px 12px;
  border: 1.5px solid var(--border);
  border-radius: 9px;
  font-family: inherit;
  font-size: .875rem;
  color: var(--text);
  background: #FAFBFF;
  cursor: pointer;
  margin-bottom: 10px;
  outline: none;
  appearance: none;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' fill='none'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%2364748B' stroke-width='1.5' stroke-linecap='round'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 12px center;
  padding-right: 32px;
}
#users-select:focus { border-color: var(--indigo); box-shadow: 0 0 0 3px rgba(79,70,229,.12); }
#user-count { font-size: .75rem; color: var(--muted); margin-bottom: 10px; }

/* ── Chat area ───────────────────────────────────────────────────────────── */
#chat-section {
  flex: 1;
  min-width: 0;
}
#no-user-selected {
  background: var(--card);
  border-radius: var(--radius);
  box-shadow: var(--shadow-sm);
  border: 1px solid var(--border);
  padding: 60px 40px;
  text-align: center;
  color: var(--muted);
}
#no-user-selected .icon { font-size: 3rem; margin-bottom: 14px; }
#no-user-selected p { font-size: 1rem; }
#no-user-selected small { font-size: .8rem; display: block; margin-top: 6px; }

#chat-area {
  display: none;
  flex-direction: column;
  background: var(--card);
  border-radius: var(--radius);
  box-shadow: var(--shadow-sm);
  border: 1px solid var(--border);
  height: calc(100vh - 150px);
  min-height: 500px;
}
#chat-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 16px 20px;
  border-bottom: 1px solid var(--border);
  background: #FAFBFF;
  border-radius: var(--radius) var(--radius) 0 0;
}
#chat-header .with-user {
  display: flex;
  align-items: center;
  gap: 10px;
}
#chat-header .chat-avatar {
  width: 34px; height: 34px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  font-size: .8rem;
  color: #fff;
  background: linear-gradient(135deg, var(--teal), var(--indigo));
}
#chat-header .chat-name { font-weight: 600; font-size: .9375rem; }
#chat-header .chat-lang { font-size: .75rem; color: var(--muted); margin-top: 1px; }

#message-history {
  flex: 1;
  overflow-y: auto;
  padding: 20px;
  display: flex;
  flex-direction: column;
  gap: 14px;
  scroll-behavior: smooth;
}
#message-history::-webkit-scrollbar { width: 5px; }
#message-history::-webkit-scrollbar-track { background: transparent; }
#message-history::-webkit-scrollbar-thumb { background: var(--border); border-radius: 99px; }

/* ── Message bubbles ─────────────────────────────────────────────────────── */
.message {
  display: flex;
  flex-direction: column;
  max-width: 72%;
  animation: msgIn .2s ease;
}
@keyframes msgIn { from { opacity:0; transform: translateY(6px); } to { opacity:1; transform: translateY(0); } }

.message.sent  { align-self: flex-end; align-items: flex-end; }
.message.recv  { align-self: flex-start; align-items: flex-start; }

.msg-meta {
  font-size: .7rem;
  color: var(--muted);
  margin-bottom: 4px;
  display: flex;
  gap: 6px;
  align-items: center;
}
.msg-bubble {
  padding: 11px 15px;
  border-radius: 16px;
  font-size: .9375rem;
  line-height: 1.55;
  word-break: break-word;
}
.message.sent  .msg-bubble { background: var(--sent-bg); color: var(--sent-text); border-bottom-right-radius: 4px; }
.message.recv  .msg-bubble { background: var(--recv-bg); color: var(--recv-text); border-bottom-left-radius: 4px; }

.msg-translation {
  margin-top: 6px;
  padding: 8px 13px;
  font-size: .8125rem;
  font-style: italic;
  line-height: 1.5;
  border-radius: 10px;
  display: flex;
  align-items: flex-start;
  gap: 6px;
  word-break: break-word;
}
.message.sent .msg-translation {
  background: rgba(255,255,255,.18);
  color: rgba(255,255,255,.9);
  border: 1px solid rgba(255,255,255,.25);
}
.message.recv .msg-translation {
  background: #EEF2FF;
  color: var(--indigo-dk);
  border: 1px solid #C7D2FE;
}
.msg-translation .flag { flex-shrink: 0; font-style: normal; }

.msg-time { font-size: .68rem; color: var(--muted); margin-top: 4px; }

/* ── Input area ──────────────────────────────────────────────────────────── */
#input-area {
  padding: 14px 16px;
  border-top: 1px solid var(--border);
  display: flex;
  gap: 10px;
  align-items: flex-end;
  background: #FAFBFF;
  border-radius: 0 0 var(--radius) var(--radius);
}
#message-input {
  flex: 1;
  padding: 10px 14px;
  border: 1.5px solid var(--border);
  border-radius: 10px;
  font-family: inherit;
  font-size: .9375rem;
  resize: none;
  outline: none;
  max-height: 120px;
  line-height: 1.5;
  background: #fff;
  color: var(--text);
  transition: border-color .2s, box-shadow .2s;
}
#message-input:focus {
  border-color: var(--indigo);
  box-shadow: 0 0 0 3px rgba(79,70,229,.12);
}
#message-input::placeholder { color: #B0BAC8; }

#translating-bar {
  display: none;
  text-align: center;
  font-size: .75rem;
  color: var(--teal);
  padding: 5px 0 0;
  font-style: italic;
  letter-spacing: .2px;
}

/* ── Disconnected overlay ────────────────────────────────────────────────── */
#disconnected-notice {
  display: none;
  background: #FFF7ED;
  border: 1px solid #FDBA74;
  border-radius: 9px;
  padding: 10px 16px;
  font-size: .8125rem;
  color: #92400E;
  text-align: center;
  margin-bottom: 12px;
}

/* ── Responsive ──────────────────────────────────────────────────────────── */
@media (max-width: 700px) {
  #main-panel { flex-direction: column; }
  #sidebar { width: 100%; }
  #chat-area { height: 70vh; }
}
</style>
</head>
<body>

<!-- ── Header ──────────────────────────────────────────────────────────── -->
<header id="app-header">
  <div class="brand">
    <span class="globe">🌏</span>
    <div>
      IndoChat
      <small>Real-time multilingual messaging</small>
    </div>
  </div>
  <div id="status-indicator">
    <div id="status-dot" class="offline"></div>
    <span id="status-text">Connecting…</span>
  </div>
</header>

<!-- ── Toast notification ──────────────────────────────────────────────── -->
<div id="toast"></div>

<!-- ── Main content ────────────────────────────────────────────────────── -->
<div id="content">

  <!-- Setup panel -->
  <div id="setup-panel">
    <div id="disconnected-notice">
      ⚠ Connection lost. Attempting to reconnect…
    </div>
    <div class="card">
      <div class="card-title">Welcome to IndoChat</div>
      <div class="card-subtitle">Set up your profile to start chatting across language barriers.</div>

      <div class="form-group">
        <label>Your username</label>
        <input type="text" id="username" placeholder="e.g. Alice" maxlength="30" autocomplete="off">
      </div>

      <div class="form-group">
        <label>Your language</label>
        <div class="radio-group">
          <label class="radio-option selected" id="lbl-en">
            <input type="radio" name="language" value="en" checked>
            🇬🇧 English
          </label>
          <label class="radio-option" id="lbl-km">
            <input type="radio" name="language" value="km">
            🇰🇭 ខ្មែរ&nbsp;(Khmer)
          </label>
        </div>
      </div>

      <button class="btn btn-primary" id="set-user-btn">Start Chatting →</button>
    </div>
  </div>

  <!-- Main panel -->
  <div id="main-panel">

    <!-- Sidebar -->
    <div id="sidebar">
      <div class="sidebar-card">
        <h3>You</h3>
        <div class="user-badge">
          <div class="avatar" id="my-avatar">?</div>
          <div class="info">
            <div class="name" id="my-username-display">—</div>
            <div class="lang" id="my-lang-display">—</div>
          </div>
        </div>
      </div>

      <div class="sidebar-card">
        <h3>Connected users</h3>
        <div id="user-count">Loading…</div>
        <select id="users-select">
          <option value="">— Select a user —</option>
        </select>
        <button class="btn btn-secondary" id="refresh-btn" style="width:100%">↻ Refresh list</button>
      </div>
    </div>

    <!-- Chat section -->
    <div id="chat-section">
      <div id="no-user-selected">
        <div class="icon">💬</div>
        <p>Select a user from the list to begin</p>
        <small>Messages are translated automatically in real time.</small>
      </div>

      <div id="chat-area">
        <div id="chat-header">
          <div class="with-user">
            <div class="chat-avatar" id="chat-avatar">?</div>
            <div>
              <div class="chat-name" id="chat-with-name">—</div>
              <div class="chat-lang" id="chat-with-lang">—</div>
            </div>
          </div>
          <button class="btn btn-danger" id="stop-btn">⏹ Stop</button>
        </div>

        <div id="message-history"></div>

        <div style="padding:0 16px">
          <div id="translating-bar">⏳ Sending &amp; translating…</div>
        </div>

        <div id="input-area">
          <textarea id="message-input" rows="1" placeholder="Type your message… (Enter to send, Shift+Enter for new line)"></textarea>
          <button class="btn btn-send" id="send-btn">Send</button>
        </div>
      </div>
    </div>

  </div><!-- /#main-panel -->
</div><!-- /#content -->

<script>
/* ── Config ──────────────────────────────────────────────────────────────── */
const WS_PORT = <?= (int)$wsPort ?>;
const WS_HOST = window.location.hostname || 'localhost';

/* ── State ───────────────────────────────────────────────────────────────── */
let ws            = null;
let wsReady       = false;
let currentUser   = null;
let currentLang   = 'en';
let chosenUser    = null;
let chosenLang    = 'en';
let isSending     = false;
let reconnectTimer = null;

/* ── Lang helpers ────────────────────────────────────────────────────────── */
const LANG_LABEL = { en: '🇬🇧 English', km: '🇰🇭 ខ្មែរ (Khmer)' };
const LANG_FLAG  = { en: '🇬🇧', km: '🇰🇭' };

function langLabel(code) { return LANG_LABEL[code] || code; }
function langFlag(code)  { return LANG_FLAG[code] || '🌐'; }

/* ── WebSocket ───────────────────────────────────────────────────────────── */
function connectWS() {
  if (reconnectTimer) { clearTimeout(reconnectTimer); reconnectTimer = null; }

  ws = new WebSocket(`ws://${WS_HOST}:${WS_PORT}`);

  ws.onopen = () => {
    wsReady = true;
    setStatus(true);
    $('#disconnected-notice').hide();
    if (currentUser) {
      // Re-register after reconnect
      wsSend({ type: 'set_user', username: currentUser, language: currentLang });
    }
  };

  ws.onmessage = (evt) => {
    try { handleWSMessage(JSON.parse(evt.data)); }
    catch (e) { console.error('WS parse error', e); }
  };

  ws.onclose = () => {
    wsReady = false;
    setStatus(false);
    if (currentUser) { $('#disconnected-notice').show(); }
    reconnectTimer = setTimeout(connectWS, 3500);
  };

  ws.onerror = () => {
    wsReady = false;
  };
}

function wsSend(obj) {
  if (ws && ws.readyState === WebSocket.OPEN) {
    ws.send(JSON.stringify(obj));
  }
}

/* ── WS message router ───────────────────────────────────────────────────── */
function handleWSMessage(data) {
  switch (data.type) {
    case 'user_set':    onUserSet(data);    break;
    case 'users_list':  onUsersList(data);  break;
    case 'message':     onMessage(data);    break;
    case 'error':       showToast(data.message, 'error'); break;
  }
}

function onUserSet(data) {
  currentUser = data.username;
  currentLang = data.language;
  showMainPanel();
  loadUsersAjax();
}

function onUsersList(data) {
  populateSelect(data.users);
}

function onMessage(data) {
  // Ignore messages not involving our current conversation
  const relevant = (data.from === currentUser || data.from === chosenUser) &&
                   (data.to   === currentUser || data.to   === chosenUser);
  if (!relevant) return;

  // Clear sending state if this is our own message returning from server
  if (data.from === currentUser) {
    isSending = false;
    $('#send-btn').prop('disabled', false).text('Send');
    $('#translating-bar').hide();
  }

  appendMessage(data);
}

/* ── Set user ────────────────────────────────────────────────────────────── */
function setUser() {
  const username = $('#username').val().trim();
  const language = $('input[name="language"]:checked').val();

  if (!username) { showToast('Please enter a username.', 'error'); return; }
  if (!wsReady)  { showToast('Not connected to server. Please wait…', 'error'); return; }

  wsSend({ type: 'set_user', username, language });
}

/* ── UI: panels ──────────────────────────────────────────────────────────── */
function showMainPanel() {
  $('#setup-panel').hide();
  $('#main-panel').addClass('active');

  // Update sidebar user card
  $('#my-avatar').text(currentUser.charAt(0).toUpperCase());
  $('#my-username-display').text(currentUser);
  $('#my-lang-display').text(langLabel(currentLang));
}

function showSetupPanel() {
  // Pre-fill form with current values so user can quickly restart
  if (currentUser) $('#username').val(currentUser);
  $(`input[name="language"][value="${currentLang}"]`).prop('checked', true);
  syncRadioHighlight();

  $('#main-panel').removeClass('active');
  $('#setup-panel').show();

  // Reset conversation state
  chosenUser = null;
  chosenLang = 'en';
  $('#chat-area').hide();
  $('#no-user-selected').show();
  $('#message-history').empty();
  $('#users-select').val('');
}

/* ── Load users via AJAX ─────────────────────────────────────────────────── */
function loadUsersAjax() {
  $.ajax({
    url: '/users.php',
    type: 'GET',
    dataType: 'json',
    success: (users) => populateSelect(users),
    error:   ()      => showToast('Could not load user list.', 'error'),
  });
}

/* ── Populate users dropdown ─────────────────────────────────────────────── */
function populateSelect(users) {
  const $sel   = $('#users-select');
  const curVal = $sel.val();

  $sel.empty().append('<option value="">— Select a user —</option>');

  const others = users.filter(u => u.username !== currentUser);

  others.forEach(u => {
    $sel.append(
      $('<option>', {
        value: u.username,
        'data-lang': u.language,
        text: `${u.username}  (${langLabel(u.language)})`,
      })
    );
  });

  // Restore selection if user still present; otherwise close chat
  if (curVal) {
    $sel.val(curVal);
    const stillHere = others.some(u => u.username === curVal);
    if (!stillHere && chosenUser === curVal) {
      showToast(`${curVal} has disconnected.`, 'error');
      closeConversation();
    }
  }

  const count = others.length;
  $('#user-count').text(count === 0 ? 'No other users online.' : `${count} user${count > 1 ? 's' : ''} online`);
}

/* ── User selected from dropdown ────────────────────────────────────────── */
function onSelectUser() {
  const $opt = $('#users-select option:selected');
  const uname = $opt.val();

  if (!uname) { closeConversation(); return; }

  chosenUser = uname;
  chosenLang = $opt.data('lang') || 'en';

  $('#chat-with-name').text(uname);
  $('#chat-with-lang').text(langLabel(chosenLang));
  $('#chat-avatar').text(uname.charAt(0).toUpperCase());

  $('#no-user-selected').hide();
  $('#chat-area').css('display', 'flex');
  $('#message-history').empty();
}

function closeConversation() {
  chosenUser = null;
  chosenLang = 'en';
  $('#users-select').val('');
  $('#chat-area').hide();
  $('#no-user-selected').show();
  isSending = false;
  $('#send-btn').prop('disabled', false).text('Send');
  $('#translating-bar').hide();
}

/* ── Send message ────────────────────────────────────────────────────────── */
function sendMessage() {
  if (isSending) return;

  const message = $('#message-input').val().trim();
  if (!message || !chosenUser) return;
  if (!wsReady) { showToast('Not connected.', 'error'); return; }

  isSending = true;
  $('#send-btn').prop('disabled', true).text('…');
  $('#translating-bar').show();

  wsSend({ type: 'send_message', to: chosenUser, message });
  $('#message-input').val('').css('height', 'auto');
}

/* ── Append message bubble ───────────────────────────────────────────────── */
function appendMessage(data) {
  const isSent     = data.from === currentUser;
  const samelang   = data.fromLang === data.toLang;
  const senderName = isSent ? 'You' : escHtml(data.from);
  const direction  = isSent ? 'sent' : 'recv';

  // Translation label: show what language it was translated INTO
  const targetLang = isSent ? data.toLang : data.fromLang === currentLang ? data.fromLang : data.fromLang;
  // For sent: show the recipient's language. For received: show sender's language + translation into ours.

  let translationHtml = '';
  if (!samelang) {
    if (isSent) {
      // Show what the recipient will read (their language)
      translationHtml = `
        <div class="msg-translation">
          <span class="flag">${langFlag(data.toLang)}</span>
          <span>${escHtml(data.translated)}</span>
        </div>`;
    } else {
      // Show translation into our language
      translationHtml = `
        <div class="msg-translation">
          <span class="flag">${langFlag(data.toLang)}</span>
          <span>${escHtml(data.translated)}</span>
        </div>`;
    }
  }

  const html = `
    <div class="message ${direction}">
      <div class="msg-meta">
        <span>${senderName}</span>
        <span>${langFlag(data.fromLang)}</span>
      </div>
      <div class="msg-bubble">${escHtml(data.original)}</div>
      ${translationHtml}
      <div class="msg-time">${escHtml(data.timestamp)}</div>
    </div>`;

  $('#message-history').append(html);
  scrollToBottom();
}

/* ── Helpers ─────────────────────────────────────────────────────────────── */
function scrollToBottom() {
  const el = document.getElementById('message-history');
  if (el) el.scrollTop = el.scrollHeight;
}

function escHtml(str) {
  return $('<div>').text(String(str ?? '')).html();
}

function setStatus(online) {
  $('#status-dot').toggleClass('offline', !online);
  $('#status-text').text(online ? 'Connected' : 'Reconnecting…');
}

let toastTimer = null;
function showToast(msg, type = 'info') {
  const $t = $('#toast');
  $t.removeClass('error info').addClass(type).text(msg).show();
  if (toastTimer) clearTimeout(toastTimer);
  toastTimer = setTimeout(() => $t.fadeOut(300), 4000);
}

function syncRadioHighlight() {
  $('.radio-option').removeClass('selected');
  $('input[name="language"]:checked').closest('.radio-option').addClass('selected');
}

/* ── Auto-grow textarea ──────────────────────────────────────────────────── */
function autoGrow(el) {
  el.style.height = 'auto';
  el.style.height = Math.min(el.scrollHeight, 120) + 'px';
}

/* ── DOM ready ───────────────────────────────────────────────────────────── */
$(function () {

  connectWS();

  /* Setup form */
  $('#set-user-btn').on('click', setUser);
  $('#username').on('keydown', (e) => { if (e.key === 'Enter') setUser(); });

  /* Language radio highlight */
  $('input[name="language"]').on('change', syncRadioHighlight);

  /* User selection */
  $('#users-select').on('change', onSelectUser);
  $('#refresh-btn').on('click', loadUsersAjax);

  /* Messaging */
  $('#send-btn').on('click', sendMessage);
  $('#message-input').on('keydown', (e) => {
    if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); }
  });
  $('#message-input').on('input', function () { autoGrow(this); });

  /* Stop */
  $('#stop-btn').on('click', () => {
    showSetupPanel();
    showToast('Conversation ended.', 'info');
  });

  /* Poll users every 8 s while in main panel */
  setInterval(() => {
    if (currentUser && wsReady) loadUsersAjax();
  }, 8000);
});
</script>
</body>
</html>

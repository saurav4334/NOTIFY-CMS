<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>NotifySMS — Super Admin CMS</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('mobile.css') }}">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
:root{
  --navy:#003087;--blue:#009cde;--gold:#ffc439;
  --sidebar:#0a1628;--sidebar2:#0f2040;--sideborder:rgba(255,255,255,.07);
  --bg:#f5f7fb;--card:#ffffff;--border:#e8eef8;
  --text:#1e293b;--muted:#64748b;--light:#f1f5f9;
}
body{font-family:'Inter',sans-serif;background:var(--bg);color:var(--text);min-height:100vh;overflow-x:hidden;}

/* ── Login ── */
#login-screen{position:fixed;inset:0;background:linear-gradient(135deg,#001260,#0033b0,#0055d4);display:flex;align-items:center;justify-content:center;z-index:9999;}
.login-box{background:white;border-radius:24px;padding:48px 40px;width:100%;max-width:400px;box-shadow:0 32px 80px rgba(0,0,0,.4);}
.login-logo{display:flex;align-items:center;gap:10px;justify-content:center;margin-bottom:32px;}
.login-logo-icon{width:48px;height:48px;border-radius:14px;background:linear-gradient(135deg,#009cde,#003087);display:flex;align-items:center;justify-content:center;}
.login-logo-text{font-size:22px;font-weight:800;color:#003087;}
.login-box h2{font-size:20px;font-weight:800;color:#003087;text-align:center;margin-bottom:6px;}
.login-box p{font-size:13px;color:#64748b;text-align:center;margin-bottom:28px;}
.login-field{margin-bottom:16px;}
.login-field label{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#64748b;display:block;margin-bottom:6px;}
.login-field input{width:100%;border:1.5px solid #e2e8f0;border-radius:12px;padding:12px 14px;font-size:14px;font-family:inherit;outline:none;transition:border-color .2s;}
.login-field input:focus{border-color:#003087;}
.login-btn{width:100%;background:#003087;color:white;border:none;border-radius:12px;padding:13px;font-size:15px;font-weight:700;cursor:pointer;margin-top:8px;transition:all .2s;}
.login-btn:hover{background:#0044bb;}
.login-err{background:#fee2e2;color:#dc2626;font-size:12px;border-radius:8px;padding:8px 12px;margin-bottom:12px;display:none;}

/* ── Layout ── */
#app{display:block;height:100vh;overflow:hidden;}
.layout{display:flex;height:100vh;}

/* ── Sidebar ── */
.sidebar{width:260px;background:var(--sidebar);flex-shrink:0;display:flex;flex-direction:column;overflow:hidden;}
.sidebar-header{padding:20px 20px 16px;border-bottom:1px solid var(--sideborder);display:flex;align-items:center;gap:10px;}
.sidebar-logo-icon{width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#009cde,#003087);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.sidebar-logo-text{font-size:16px;font-weight:800;color:white;}
.sidebar-logo-text span{color:#009cde;}
.sidebar-badge{font-size:9px;font-weight:700;background:rgba(255,196,57,.2);color:#ffc439;border:1px solid rgba(255,196,57,.3);padding:2px 8px;border-radius:20px;display:block;margin-top:2px;letter-spacing:.4px;}
.sidebar-nav{flex:1;overflow-y:auto;padding:12px 0;}
.sidebar-nav::-webkit-scrollbar{width:4px;}.sidebar-nav::-webkit-scrollbar-thumb{background:rgba(255,255,255,.1);border-radius:4px;}
.nav-section{padding:10px 16px 4px;font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:rgba(255,255,255,.3);}
.nav-item{display:flex;align-items:center;gap:10px;padding:9px 16px;cursor:pointer;border-radius:0;transition:all .2s;color:rgba(255,255,255,.55);font-size:13px;font-weight:500;border-left:3px solid transparent;margin:1px 0;}
.nav-item:hover{background:rgba(255,255,255,.06);color:rgba(255,255,255,.85);}
.nav-item.active{background:rgba(0,156,222,.12);color:#38bdf8;border-left-color:#009cde;font-weight:600;}
.nav-item i{width:18px;text-align:center;font-size:13px;}
.sidebar-footer{padding:14px 16px;border-top:1px solid var(--sideborder);}
.sidebar-user{display:flex;align-items:center;gap:10px;}
.sidebar-avatar{width:34px;height:34px;border-radius:10px;background:linear-gradient(135deg,#ffc439,#ff9500);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:12px;color:#003087;flex-shrink:0;}
.sidebar-user-info{flex:1;min-width:0;}
.sidebar-user-name{font-size:12px;font-weight:700;color:white;line-height:1.2;}
.sidebar-user-role{font-size:10px;color:rgba(255,255,255,.4);}
.logout-btn{background:none;border:none;cursor:pointer;color:rgba(255,255,255,.35);font-size:14px;transition:color .2s;}
.logout-btn:hover{color:#f87171;}

/* ── Main ── */
.main{flex:1;display:flex;flex-direction:column;overflow:hidden;}
.topbar{background:white;border-bottom:1px solid var(--border);padding:0 28px;height:62px;display:flex;align-items:center;justify-content:space-between;flex-shrink:0;box-shadow:0 1px 8px rgba(0,48,135,.05);}
.topbar-title{font-size:18px;font-weight:800;color:var(--navy);}
.topbar-title span{color:var(--muted);font-weight:400;font-size:13px;margin-left:6px;}
.topbar-actions{display:flex;align-items:center;gap:10px;}
.topbar-preview{display:flex;align-items:center;gap:6px;padding:7px 16px;border-radius:10px;border:1.5px solid var(--navy);color:var(--navy);font-size:12px;font-weight:700;cursor:pointer;text-decoration:none;transition:all .2s;}
.topbar-preview:hover{background:var(--navy);color:white;}
.content{flex:1;overflow-y:auto;padding:28px;}
.content::-webkit-scrollbar{width:6px;}.content::-webkit-scrollbar-thumb{background:#e2e8f0;border-radius:6px;}

/* ── Cards & Form ── */
.card{background:var(--card);border:1px solid var(--border);border-radius:16px;overflow:hidden;margin-bottom:20px;}
.card-header{padding:16px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;}
.card-title{font-size:14px;font-weight:700;color:var(--text);display:flex;align-items:center;gap:8px;}
.card-title i{color:var(--navy);}
.card-body{padding:20px;}
.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;}
.form-grid.cols3{grid-template-columns:1fr 1fr 1fr;}
.form-grid.cols1{grid-template-columns:1fr;}
.field{display:flex;flex-direction:column;gap:5px;}
.field label{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--muted);}
.field input,.field textarea,.field select{border:1.5px solid var(--border);border-radius:10px;padding:9px 12px;font-size:13px;font-family:inherit;outline:none;transition:border-color .2s;background:white;color:var(--text);}
.field input:focus,.field textarea:focus,.field select:focus{border-color:var(--navy);}
.field textarea{resize:vertical;min-height:80px;}
.field .hint{font-size:11px;color:var(--muted);margin-top:2px;}
.btn{display:inline-flex;align-items:center;gap:6px;padding:9px 18px;border-radius:10px;font-size:12px;font-weight:700;cursor:pointer;border:none;transition:all .2s;font-family:inherit;}
.btn-primary{background:var(--navy);color:white;}.btn-primary:hover{background:#0044bb;}
.btn-gold{background:var(--gold);color:var(--navy);}.btn-gold:hover{opacity:.9;}
.btn-danger{background:#fee2e2;color:#dc2626;}.btn-danger:hover{background:#fecaca;}
.btn-outline{background:transparent;border:1.5px solid var(--border);color:var(--muted);}.btn-outline:hover{border-color:var(--navy);color:var(--navy);}
.btn-success{background:#dcfce7;color:#166534;}.btn-success:hover{background:#bbf7d0;}
.btn-sm{padding:6px 12px;font-size:11px;}
.save-row{display:flex;justify-content:flex-end;padding-top:14px;border-top:1px solid var(--border);margin-top:16px;}

/* ── Dashboard ── */
.dash-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px;}
.dash-stat{background:var(--card);border:1px solid var(--border);border-radius:16px;padding:20px;display:flex;align-items:center;gap:14px;}
.dash-stat-icon{width:48px;height:48px;border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:18px;}
.dash-stat-val{font-size:26px;font-weight:900;line-height:1;color:var(--navy);}
.dash-stat-lbl{font-size:11px;color:var(--muted);margin-top:2px;}
.quick-actions{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:24px;}
.quick-btn{background:var(--card);border:1px solid var(--border);border-radius:14px;padding:16px;text-align:center;cursor:pointer;transition:all .3s;}
.quick-btn:hover{border-color:var(--navy);transform:translateY(-3px);box-shadow:0 8px 24px rgba(0,48,135,.1);}
.quick-btn i{font-size:24px;margin-bottom:8px;display:block;}
.quick-btn span{font-size:12px;font-weight:700;color:var(--muted);}

/* ── Table ── */
.data-table{width:100%;border-collapse:collapse;}
.data-table th{background:#f8fafc;text-align:left;padding:10px 14px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--muted);border-bottom:1px solid var(--border);}
.data-table td{padding:11px 14px;font-size:13px;border-bottom:1px solid #f8fafc;vertical-align:middle;}
.data-table tr:last-child td{border-bottom:none;}
.data-table tr:hover td{background:#fafbff;}
.badge{display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;}
.badge-blue{background:rgba(0,48,135,.08);color:var(--navy);}
.badge-green{background:#dcfce7;color:#166534;}
.badge-amber{background:#fef3c7;color:#92400e;}
.badge-red{background:#fee2e2;color:#dc2626;}
.icon-prev{width:32px;height:32px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;}

/* ── Client Manager ── */
.client-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:12px;}
.client-card{border:1.5px solid var(--border);border-radius:14px;padding:14px;display:flex;align-items:center;gap:12px;transition:all .2s;}
.client-card:hover{border-color:rgba(0,48,135,.25);box-shadow:0 4px 16px rgba(0,48,135,.08);}
.client-icon-prev{width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0;}
.client-card-name{font-size:13px;font-weight:700;color:var(--text);}
.client-card-actions{margin-left:auto;display:flex;gap:4px;}

/* ── Toast ── */
#toast-container{position:fixed;top:20px;right:20px;z-index:99999;display:flex;flex-direction:column;gap:8px;}
.toast{display:flex;align-items:center;gap:10px;padding:12px 18px;border-radius:12px;font-size:13px;font-weight:600;color:white;box-shadow:0 8px 24px rgba(0,0,0,.15);animation:toastIn .3s ease forwards;}
.toast-success{background:#22c55e;}
.toast-error{background:#ef4444;}
.toast-info{background:#003087;}
@keyframes toastIn{from{opacity:0;transform:translateX(20px)}to{opacity:1;transform:translateX(0)}}

/* ── Section visibility ── */
.cms-section{display:none;}
.cms-section.active{display:block;}

/* ── Color dot ── */
.color-dot{width:20px;height:20px;border-radius:50%;display:inline-block;border:2px solid white;box-shadow:0 0 0 1px #e2e8f0;}

/* ── Star rating ── */
.star-input{display:flex;gap:3px;}
.star-input span{cursor:pointer;font-size:18px;color:#d1d5db;transition:color .15s;}
.star-input span.on{color:#fbbf24;}

/* ── Responsive ── */
@media(max-width:1024px){.dash-grid{grid-template-columns:repeat(2,1fr);}.quick-actions{grid-template-columns:repeat(2,1fr);}.form-grid{grid-template-columns:1fr;}}
</style>
</head>
<body>

<!-- ══════════ APP ══════════ -->
<div id="app">
<div id="sidebar-backdrop" onclick="toggleAdminSidebar()"></div>
  <div class="layout">

    <!-- SIDEBAR -->
    <aside class="sidebar">
      <div class="sidebar-header">
        <div class="sidebar-logo-icon"><i class="fas fa-comment-sms text-white" style="font-size:16px;"></i></div>
        <div>
          <div class="sidebar-logo-text">Notify<span>SMS</span></div>
          <span class="sidebar-badge">SUPER ADMIN</span>
        </div>
      </div>

      <nav class="sidebar-nav">
        <div class="nav-section">Main</div>
        <div class="nav-item active" onclick="showSection('dashboard')"><i class="fas fa-chart-pie"></i>Dashboard</div>
        @if($can['cms'])
        <div class="nav-item" onclick="showSection('settings')"><i class="fas fa-cog"></i>General Settings</div>
        @endif

        @if($can['leads'] || $can['media'])
        <div class="nav-section">Inbox</div>
        @if($can['leads'])
        <div class="nav-item" onclick="showSection('leads')"><i class="fas fa-inbox"></i>Contact Leads
          <span id="nav-leads-badge" style="display:none;margin-left:auto;background:#dc2626;color:#fff;font-size:10px;font-weight:700;padding:1px 7px;border-radius:10px;"></span>
        </div>
        @endif
        @if($can['media'])
        <div class="nav-item" onclick="showSection('media')"><i class="fas fa-photo-film"></i>Media Library</div>
        @endif
        @endif

        @if($can['cms'])
        <div class="nav-section">Homepage</div>
        <div class="nav-item" onclick="showSection('hero')"><i class="fas fa-star"></i>Hero Section</div>
        <div class="nav-item" onclick="showSection('clients')"><i class="fas fa-handshake"></i>Trusted Clients</div>
        <div class="nav-item" onclick="showSection('whyus')"><i class="fas fa-check-double"></i>Why Choose Us</div>
        <div class="nav-item" onclick="showSection('testimonials')"><i class="fas fa-quote-left"></i>Testimonials</div>
        <div class="nav-item" onclick="showSection('about')"><i class="fas fa-circle-info"></i>About Page</div>

        <div class="nav-section">Content</div>
        <div class="nav-item" onclick="showSection('services')"><i class="fas fa-layer-group"></i>Services Manager</div>
        <div class="nav-item" onclick="showSection('pricing')"><i class="fas fa-tags"></i>Pricing Manager</div>
        <div class="nav-item" onclick="showSection('faq')"><i class="fas fa-circle-question"></i>FAQ Manager</div>
        <div class="nav-item" onclick="showSection('contact')"><i class="fas fa-phone"></i>Contact Info</div>
        @endif

        <div class="nav-section">Advanced</div>
        @if($can['cms'])
        <div class="nav-item" onclick="showSection('seo')"><i class="fas fa-search"></i>SEO Settings</div>
        @endif
        <div class="nav-item" onclick="showSection('security')"><i class="fas fa-lock"></i>Security</div>
        @if($can['cms'])
        <div class="nav-item" onclick="showSection('backup')"><i class="fas fa-database"></i>Backup / Restore</div>
        @endif
      </nav>

      <div class="sidebar-footer">
        <div class="sidebar-user">
          <div class="sidebar-avatar">{{ \Illuminate\Support\Str::of($user->name)->explode(' ')->map(fn($p) => mb_substr($p, 0, 1))->take(2)->implode('') }}</div>
          <div class="sidebar-user-info">
            <div class="sidebar-user-name">{{ $user->name }}</div>
            <div class="sidebar-user-role">{{ $user->role?->name ?? 'Admin' }}</div>
          </div>
          <button class="logout-btn" onclick="doLogout()" title="Logout"><i class="fas fa-sign-out-alt"></i></button>
        </div>
      </div>
    </aside>

    <!-- MAIN -->
    <div class="main">
      <div class="topbar">
        <div>
          <div class="topbar-title" id="topbar-title">Dashboard <span>Overview</span></div>
        </div>
        <button id="admin-menu-btn" onclick="toggleAdminSidebar()" style="display:none;background:none;border:none;cursor:pointer;padding:6px;margin-right:8px;color:var(--navy);font-size:20px;" aria-label="Menu"><i class="fas fa-bars"></i></button>
        <div class="topbar-actions">
          <a href="index.html" target="_blank" class="topbar-preview"><i class="fas fa-eye"></i>Preview Site</a>
          @if($can['cms'])
          <button class="btn btn-gold btn-sm" onclick="saveAll()"><i class="fas fa-save"></i>Save All Changes</button>
          @endif
        </div>
      </div>

      <div class="content">

        <!-- ── DASHBOARD ── -->
        <div class="cms-section active" id="sec-dashboard">
          <div class="dash-grid">
            @if($can['leads'])
            <div class="dash-stat" onclick="showSection('leads')" style="cursor:pointer;"><div class="dash-stat-icon" style="background:rgba(220,38,38,.08);color:#dc2626;"><i class="fas fa-inbox"></i></div><div><div class="dash-stat-val" id="ds-leads">{{ $stats['leads_new'] }}</div><div class="dash-stat-lbl">New Leads ({{ $stats['leads_total'] }} total)</div></div></div>
            @endif
            @if($can['cms'])
            <div class="dash-stat"><div class="dash-stat-icon" style="background:rgba(0,48,135,.08);color:#003087;"><i class="fas fa-tags"></i></div><div><div class="dash-stat-val" id="ds-clients">0</div><div class="dash-stat-lbl">Trusted Clients</div></div></div>
            <div class="dash-stat"><div class="dash-stat-icon" style="background:rgba(0,200,83,.08);color:#16a34a;"><i class="fas fa-dollar-sign"></i></div><div><div class="dash-stat-val" id="ds-pricing">0</div><div class="dash-stat-lbl">Pricing Slabs</div></div></div>
            <div class="dash-stat"><div class="dash-stat-icon" style="background:rgba(124,58,237,.08);color:#7c3aed;"><i class="fas fa-circle-question"></i></div><div><div class="dash-stat-val" id="ds-faq">0</div><div class="dash-stat-lbl">FAQ Items</div></div></div>
            <div class="dash-stat"><div class="dash-stat-icon" style="background:rgba(249,115,22,.08);color:#f97316;"><i class="fas fa-quote-left"></i></div><div><div class="dash-stat-val" id="ds-testi">0</div><div class="dash-stat-lbl">Testimonials</div></div></div>
            @endif
          </div>
          @if($can['cms'])
          <div class="quick-actions">
            <div class="quick-btn" onclick="showSection('hero')"><i class="fas fa-star" style="color:#003087;"></i><span>Edit Hero</span></div>
            <div class="quick-btn" onclick="showSection('clients')"><i class="fas fa-handshake" style="color:#059669;"></i><span>Manage Clients</span></div>
            <div class="quick-btn" onclick="showSection('pricing')"><i class="fas fa-tags" style="color:#7c3aed;"></i><span>Update Pricing</span></div>
            <div class="quick-btn" onclick="showSection('faq')"><i class="fas fa-circle-question" style="color:#f97316;"></i><span>Edit FAQs</span></div>
          </div>
          <div class="card">
            <div class="card-header"><div class="card-title"><i class="fas fa-info-circle"></i>CMS Quick Reference</div></div>
            <div class="card-body">
              <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;">
                <div style="padding:14px;background:#f8fafc;border-radius:12px;border:1px solid #e8eef8;">
                  <div style="font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;margin-bottom:6px;">Hero Section</div>
                  <div style="font-size:12px;color:#334155;">Edit headline, subtitle, buttons &amp; stats</div>
                </div>
                <div style="padding:14px;background:#f8fafc;border-radius:12px;border:1px solid #e8eef8;">
                  <div style="font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;margin-bottom:6px;">Trusted Clients</div>
                  <div style="font-size:12px;color:#334155;">Add/remove/edit logo slider clients</div>
                </div>
                <div style="padding:14px;background:#f8fafc;border-radius:12px;border:1px solid #e8eef8;">
                  <div style="font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;margin-bottom:6px;">Pricing</div>
                  <div style="font-size:12px;color:#334155;">Masking &amp; Non-Masking rate management</div>
                </div>
              </div>
              <div style="margin-top:14px;padding:12px 16px;background:#eff6ff;border-radius:10px;border:1px solid #bfdbfe;font-size:12px;color:#1e40af;">
                <i class="fas fa-lightbulb mr-2"></i><strong>Tip:</strong> After saving changes, click "Preview Site" to see them live in the frontend.
              </div>
            </div>
          </div>
          @endif
        </div>

        <!-- ── CONTACT LEADS ── -->
        <div class="cms-section" id="sec-leads">
          <div class="card">
            <div class="card-header" style="flex-wrap:wrap;gap:10px;">
              <div class="card-title"><i class="fas fa-inbox"></i>Contact Leads</div>
              <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
                <input type="text" id="leads-search" placeholder="Search name, email, phone…" onkeydown="if(event.key==='Enter')loadLeads()" style="border:1.5px solid #e8eef8;border-radius:10px;padding:7px 12px;font-size:13px;width:220px;">
                <select id="leads-status-filter" onchange="loadLeads()" style="border:1.5px solid #e8eef8;border-radius:10px;padding:7px 12px;font-size:13px;">
                  <option value="">All statuses</option>
                  <option value="new">New</option>
                  <option value="in_progress">In progress</option>
                  <option value="responded">Responded</option>
                  <option value="closed">Closed</option>
                  <option value="spam">Spam</option>
                </select>
                <button class="btn btn-outline btn-sm" onclick="loadLeads()"><i class="fas fa-rotate"></i>Refresh</button>
                <a class="btn btn-success btn-sm" id="leads-export-btn" href="#"><i class="fas fa-file-csv"></i>Export CSV</a>
              </div>
            </div>
            <div class="card-body">
              <div id="leads-empty" style="display:none;text-align:center;padding:40px;color:#94a3b8;">
                <i class="fas fa-inbox" style="font-size:32px;margin-bottom:10px;"></i>
                <div>No leads found.</div>
              </div>
              <div id="leads-list"></div>
            </div>
          </div>
        </div>

        <!-- ── MEDIA LIBRARY ── -->
        <div class="cms-section" id="sec-media">
          <div class="card">
            <div class="card-header"><div class="card-title"><i class="fas fa-photo-film"></i>Media Library <span id="media-count" style="font-size:12px;color:#94a3b8;font-weight:500;margin-left:6px;"></span></div></div>
            <div class="card-body">
              <div id="media-dropzone" style="border:2px dashed #cbd5e1;border-radius:14px;padding:28px;text-align:center;background:#f8fafc;cursor:pointer;transition:all .2s;">
                <i class="fas fa-cloud-arrow-up" style="font-size:30px;color:#94a3b8;"></i>
                <div style="margin-top:10px;font-size:14px;color:#334155;font-weight:600;">Drop files here or click to browse</div>
                <div style="margin-top:4px;font-size:11px;color:#94a3b8;">JPG, PNG, WEBP, SVG, PDF — up to 5&nbsp;MB each</div>
                <input type="file" id="media-input" multiple accept=".jpg,.jpeg,.png,.webp,.svg,.pdf" style="display:none;">
              </div>
              <div style="display:flex;gap:8px;align-items:center;margin-top:12px;flex-wrap:wrap;">
                <label style="font-size:12px;color:#64748b;font-weight:600;">Folder</label>
                <input type="text" id="media-folder" value="uploads" placeholder="uploads" style="border:1.5px solid #e8eef8;border-radius:10px;padding:7px 12px;font-size:13px;width:160px;">
                <select id="media-folder-filter" onchange="loadMedia()" style="border:1.5px solid #e8eef8;border-radius:10px;padding:7px 12px;font-size:13px;">
                  <option value="">All folders</option>
                </select>
                <span id="media-uploading" style="display:none;font-size:12px;color:#2563eb;"><i class="fas fa-spinner fa-spin"></i> Uploading…</span>
              </div>
            </div>
          </div>
          <div class="card" style="margin-top:16px;">
            <div class="card-body">
              <div id="media-empty" style="display:none;text-align:center;padding:36px;color:#94a3b8;">
                <i class="fas fa-image" style="font-size:30px;margin-bottom:8px;"></i><div>No media yet.</div>
              </div>
              <div id="media-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:14px;"></div>
            </div>
          </div>
        </div>

        <!-- ── ABOUT PAGE ── -->
        <div class="cms-section" id="sec-about">
          <div class="card">
            <div class="card-header" style="flex-wrap:wrap;gap:10px;">
              <div class="card-title"><i class="fas fa-circle-info"></i>About Page</div>
              <button class="btn btn-gold btn-sm" onclick="saveAbout()"><i class="fas fa-save"></i>Save About Page</button>
            </div>
            <div class="card-body">
              <div class="form-grid cols1">
                <div class="field"><label>Hero Tagline</label><input type="text" id="ab-vision"></div>
                <div class="field"><label>Story Heading</label><input type="text" id="ab-heading"></div>
                <div class="field"><label>Story Paragraph 1</label><textarea id="ab-story1" rows="3"></textarea></div>
                <div class="field"><label>Story Paragraph 2</label><textarea id="ab-story2" rows="3"></textarea></div>
                <div class="field"><label>Mission Statement</label><textarea id="ab-mission" rows="3"></textarea></div>
              </div>
              <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#64748b;margin:18px 0 10px;">Statistics (4 cards)</div>
              <div id="about-stats" style="display:grid;grid-template-columns:repeat(2,1fr);gap:12px;"></div>
            </div>
          </div>
        </div>

        <!-- ── SERVICES MANAGER ── -->
        <div class="cms-section" id="sec-services">
          <div class="card">
            <div class="card-header" style="flex-wrap:wrap;gap:10px;">
              <div class="card-title"><i class="fas fa-layer-group"></i>Services Manager <span id="services-count" style="font-size:12px;color:#94a3b8;font-weight:500;margin-left:6px;"></span></div>
              <div style="display:flex;gap:8px;">
                <button class="btn btn-outline btn-sm" onclick="addService()"><i class="fas fa-plus"></i>Add Service</button>
                <button class="btn btn-gold btn-sm" onclick="saveServices()"><i class="fas fa-save"></i>Save Services</button>
              </div>
            </div>
            <div class="card-body">
              <div class="hint" style="margin-bottom:12px;">These cards appear on the public <strong>Services</strong> page. Use Font Awesome icon names (e.g. <code>fa-bullhorn</code>). Separate features with commas.</div>
              <div id="services-list"></div>
            </div>
          </div>
        </div>

        <!-- ── GENERAL SETTINGS ── -->
        <div class="cms-section" id="sec-settings">
          <div class="card">
            <div class="card-header"><div class="card-title"><i class="fas fa-globe"></i>Site Identity</div></div>
            <div class="card-body">
              <div class="form-grid">
                <div class="field"><label>Site Name</label><input type="text" id="s-siteName" placeholder="NotifySMS"></div>
                <div class="field"><label>Tagline</label><input type="text" id="s-tagline" placeholder="#1 Bulk SMS Platform"></div>
              </div>
              <div class="form-grid" style="margin-top:14px;">
                <div class="field"><label>Customer Login URL</label><input type="url" id="s-loginUrl" placeholder="https://customer.notifysms.com.bd/login"><div class="hint">URL where Login buttons redirect</div></div>
                <div class="field"><label>Register URL</label><input type="url" id="s-registerUrl" placeholder="https://customer.notifysms.com.bd/register"></div>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-header"><div class="card-title"><i class="fas fa-address-card"></i>Contact Details</div></div>
            <div class="card-body">
              <div class="form-grid">
                <div class="field"><label>Phone</label><input type="text" id="s-phone"></div>
                <div class="field"><label>Email</label><input type="email" id="s-email"></div>
                <div class="field"><label>WhatsApp</label><input type="text" id="s-whatsapp"></div>
                <div class="field"><label>Address</label><input type="text" id="s-address"></div>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-header"><div class="card-title"><i class="fas fa-share-nodes"></i>Social Media Links</div></div>
            <div class="card-body">
              <div class="form-grid cols3">
                <div class="field"><label><i class="fab fa-facebook mr-1"></i>Facebook</label><input type="url" id="s-facebook"></div>
                <div class="field"><label><i class="fab fa-linkedin mr-1"></i>LinkedIn</label><input type="url" id="s-linkedin"></div>
                <div class="field"><label><i class="fab fa-youtube mr-1"></i>YouTube</label><input type="url" id="s-youtube"></div>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-header"><div class="card-title"><i class="fas fa-file-lines"></i>Footer Text</div></div>
            <div class="card-body">
              <div class="form-grid">
                <div class="field"><label>Copyright Text</label><input type="text" id="s-copyright"></div>
                <div class="field"><label>BTRC Line</label><input type="text" id="s-btrc"></div>
              </div>
            </div>
          </div>
          <div class="save-row"><button class="btn btn-primary" onclick="saveSettings()"><i class="fas fa-save"></i>Save Settings</button></div>
        </div>

        <!-- ── HERO SECTION ── -->
        <div class="cms-section" id="sec-hero">
          <div class="card">
            <div class="card-header"><div class="card-title"><i class="fas fa-star"></i>Hero Headline &amp; Content</div></div>
            <div class="card-body">
              <div class="form-grid">
                <div class="field"><label>Badge Text</label><input type="text" id="h-badge"></div>
              </div>
              <div class="form-grid cols3" style="margin-top:14px;">
                <div class="field"><label>Headline Line 1</label><input type="text" id="h-line1"></div>
                <div class="field"><label>Headline Gradient Word</label><input type="text" id="h-line2"></div>
                <div class="field"><label>Headline Line 3</label><input type="text" id="h-line3"></div>
              </div>
              <div class="form-grid cols1" style="margin-top:14px;">
                <div class="field"><label>Subtitle Paragraph</label><textarea id="h-subtitle" rows="3"></textarea></div>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-header"><div class="card-title"><i class="fas fa-mouse-pointer"></i>CTA Buttons</div></div>
            <div class="card-body">
              <div class="form-grid">
                <div class="field"><label>Button 1 Text</label><input type="text" id="h-btn1text"></div>
                <div class="field"><label>Button 1 Link</label><input type="text" id="h-btn1link"></div>
                <div class="field"><label>Button 2 Text</label><input type="text" id="h-btn2text"></div>
                <div class="field"><label>Button 2 Link</label><input type="text" id="h-btn2link"></div>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-header"><div class="card-title"><i class="fas fa-chart-bar"></i>Hero Stats (3 Numbers)</div></div>
            <div class="card-body">
              <div class="form-grid cols3">
                <div class="field"><label>Stat 1 Number</label><input type="text" id="h-s1num" placeholder="500M+"></div>
                <div class="field"><label>Stat 2 Number</label><input type="text" id="h-s2num" placeholder="10K+"></div>
                <div class="field"><label>Stat 3 Number</label><input type="text" id="h-s3num" placeholder="99.9%"></div>
                <div class="field"><label>Stat 1 Label</label><input type="text" id="h-s1lbl" placeholder="SMS Delivered"></div>
                <div class="field"><label>Stat 2 Label</label><input type="text" id="h-s2lbl" placeholder="Active Clients"></div>
                <div class="field"><label>Stat 3 Label</label><input type="text" id="h-s3lbl" placeholder="Uptime SLA"></div>
              </div>
            </div>
          </div>
          <div class="save-row"><button class="btn btn-primary" onclick="saveHero()"><i class="fas fa-save"></i>Save Hero Section</button></div>
        </div>

        <!-- ── TRUSTED CLIENTS ── -->
        <div class="cms-section" id="sec-clients">
          <div class="card" style="margin-bottom:16px;">
            <div class="card-header">
              <div class="card-title"><i class="fas fa-plus-circle"></i>Add New Client</div>
            </div>
            <div class="card-body">
              <div class="form-grid">
                <div class="field"><label>Company Name</label><input type="text" id="nc-name" placeholder="e.g. Grameenphone"></div>
                <div class="field">
                  <label>Icon (FontAwesome class)</label>
                  <input type="text" id="nc-icon" placeholder="e.g. fa-signal">
                  <div class="hint">Browse: <a href="https://fontawesome.com/icons" target="_blank" style="color:#003087;">fontawesome.com/icons</a></div>
                </div>
                <div class="field">
                  <label>Text Color</label>
                  <div style="display:flex;gap:8px;align-items:center;">
                    <input type="color" id="nc-color" value="#003087" style="width:44px;height:38px;border-radius:8px;border:1.5px solid #e2e8f0;padding:2px;cursor:pointer;">
                    <input type="text" id="nc-colorhex" value="#003087" placeholder="#003087" style="flex:1;">
                  </div>
                </div>
                <div class="field">
                  <label>Background Gradient</label>
                  <input type="text" id="nc-bg" placeholder="linear-gradient(135deg,#003087,#009cde)">
                  <div class="hint">Use CSS gradient or solid color</div>
                </div>
              </div>
              <div style="margin-top:14px;display:flex;align-items:center;gap:12px;">
                <button class="btn btn-primary" onclick="addClient()"><i class="fas fa-plus"></i>Add Client</button>
                <div id="nc-preview" style="display:none;padding:10px 20px;background:white;border:1.5px solid #e8eef8;border-radius:14px;display:none;align-items:center;gap:12px;">
                  <div id="nc-prev-icon" style="width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;"></div>
                  <span id="nc-prev-name" style="font-weight:800;font-size:14px;"></span>
                </div>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-header">
              <div class="card-title"><i class="fas fa-list"></i>Current Clients (<span id="client-count">0</span>)</div>
              <div style="display:flex;gap:8px;">
                <button class="btn btn-outline btn-sm" onclick="sortClients()"><i class="fas fa-sort-alpha-down"></i>Sort A-Z</button>
              </div>
            </div>
            <div class="card-body">
              <div class="client-grid" id="clients-grid"><!-- rendered by JS --></div>
            </div>
          </div>
        </div>

        <!-- ── WHY CHOOSE US ── -->
        <div class="cms-section" id="sec-whyus">
          <div class="card">
            <div class="card-header">
              <div class="card-title"><i class="fas fa-check-double"></i>Why Choose Us Items</div>
              <button class="btn btn-primary btn-sm" onclick="addWhyItem()"><i class="fas fa-plus"></i>Add Item</button>
            </div>
            <div class="card-body">
              <div id="why-list"><!-- rendered by JS --></div>
            </div>
          </div>
          <div class="save-row"><button class="btn btn-primary" onclick="saveWhyUs()"><i class="fas fa-save"></i>Save Changes</button></div>
        </div>

        <!-- ── PRICING ── -->
        <div class="cms-section" id="sec-pricing">
          <!-- NON-MASKING -->
          <div class="card">
            <div class="card-header">
              <div class="card-title"><i class="fas fa-satellite-dish" style="color:#003087;"></i>Non-Masking Pricing Slabs</div>
              <button class="btn btn-primary btn-sm" onclick="addPricingSlab('nm')"><i class="fas fa-plus"></i>Add Slab</button>
            </div>
            <div class="card-body" style="padding:0;">
              <table class="data-table" id="pricing-nm-table">
                <thead><tr><th>Tier Name</th><th>Min SMS</th><th>Max SMS</th><th>Price (৳/SMS)</th><th>Actions</th></tr></thead>
                <tbody id="pricing-nm-body"></tbody>
              </table>
            </div>
          </div>
          <!-- MASKING -->
          <div class="card">
            <div class="card-header">
              <div class="card-title"><i class="fas fa-id-badge" style="color:#b45309;"></i>Masking Pricing Slabs</div>
              <button class="btn btn-primary btn-sm" onclick="addPricingSlab('m')"><i class="fas fa-plus"></i>Add Slab</button>
            </div>
            <div class="card-body" style="padding:0;">
              <table class="data-table" id="pricing-m-table">
                <thead><tr><th>Tier Name</th><th>Min SMS</th><th>Max SMS</th><th>Price (৳/SMS)</th><th>Actions</th></tr></thead>
                <tbody id="pricing-m-body"></tbody>
              </table>
            </div>
          </div>
          <div style="padding:12px 16px;background:#eff6ff;border-radius:12px;border:1px solid #bfdbfe;font-size:12px;color:#1e40af;margin-bottom:16px;">
            <i class="fas fa-shield-halved mr-2"></i><strong>Note:</strong> All prices shown include VAT, TAX &amp; Dipping. Set "Max SMS" to 0 for unlimited (100K+).
          </div>
        </div>

        <!-- ── FAQ ── -->
        <div class="cms-section" id="sec-faq">
          <div class="card">
            <div class="card-header">
              <div class="card-title"><i class="fas fa-circle-question"></i>FAQ Items (<span id="faq-count">0</span>)</div>
              <button class="btn btn-primary btn-sm" onclick="addFaq()"><i class="fas fa-plus"></i>Add FAQ</button>
            </div>
            <div class="card-body">
              <div id="faq-list"><!-- rendered by JS --></div>
            </div>
          </div>
          <div class="save-row"><button class="btn btn-primary" onclick="saveFaq()"><i class="fas fa-save"></i>Save FAQs</button></div>
        </div>

        <!-- ── TESTIMONIALS ── -->
        <div class="cms-section" id="sec-testimonials">
          <div class="card">
            <div class="card-header">
              <div class="card-title"><i class="fas fa-quote-left"></i>Testimonials</div>
              <button class="btn btn-primary btn-sm" onclick="addTestimonial()"><i class="fas fa-plus"></i>Add Testimonial</button>
            </div>
            <div class="card-body">
              <div id="testi-list"><!-- rendered by JS --></div>
            </div>
          </div>
          <div class="save-row"><button class="btn btn-primary" onclick="saveTestimonials()"><i class="fas fa-save"></i>Save Testimonials</button></div>
        </div>

        <!-- ── CONTACT INFO ── -->
        <div class="cms-section" id="sec-contact">
          <div class="card">
            <div class="card-header"><div class="card-title"><i class="fas fa-phone"></i>Contact Information</div></div>
            <div class="card-body">
              <div class="form-grid">
                <div class="field"><label>Phone Number</label><input type="text" id="ci-phone"></div>
                <div class="field"><label>Email Address</label><input type="email" id="ci-email"></div>
                <div class="field"><label>WhatsApp Number</label><input type="text" id="ci-whatsapp"></div>
                <div class="field"><label>Office Address</label><input type="text" id="ci-address"></div>
                <div class="field"><label>Office Hours</label><input type="text" id="ci-hours" placeholder="Sat-Thu: 9AM-9PM"></div>
                <div class="field"><label>Response Time Guarantee</label><input type="text" id="ci-response" placeholder="1 business hour"></div>
              </div>
            </div>
          </div>
          <div class="save-row"><button class="btn btn-primary" onclick="saveContact()"><i class="fas fa-save"></i>Save Contact Info</button></div>
        </div>

        <!-- ── SEO ── -->
        <div class="cms-section" id="sec-seo">
          <div id="seo-pages"><!-- rendered by JS --></div>
          <div class="save-row"><button class="btn btn-primary" onclick="saveSEO()"><i class="fas fa-save"></i>Save SEO Settings</button></div>
        </div>

        <!-- ── SECURITY ── -->
        <div class="cms-section" id="sec-security">
          <div class="card">
            <div class="card-header"><div class="card-title"><i class="fas fa-lock"></i>Change Admin Password</div></div>
            <div class="card-body">
              <div class="form-grid">
                <div class="field"><label>Current Password</label><input type="password" id="sec-cur"></div>
                <div class="field" style="grid-column:span 2;"></div>
                <div class="field"><label>New Password</label><input type="password" id="sec-new"></div>
                <div class="field"><label>Confirm New Password</label><input type="password" id="sec-confirm"></div>
              </div>
              <div style="margin-top:16px;">
                <button class="btn btn-primary" onclick="changePassword()"><i class="fas fa-key"></i>Change Password</button>
              </div>
            </div>
          </div>
        </div>

        <!-- ── BACKUP ── -->
        <div class="cms-section" id="sec-backup">
          <div class="card">
            <div class="card-header"><div class="card-title"><i class="fas fa-download"></i>Export CMS Data</div></div>
            <div class="card-body">
              <p style="font-size:13px;color:#64748b;margin-bottom:16px;">Download all your CMS data as a JSON file. Use this to backup or transfer settings.</p>
              <button class="btn btn-primary" onclick="exportData()"><i class="fas fa-download"></i>Export JSON Backup</button>
            </div>
          </div>
          <div class="card">
            <div class="card-header"><div class="card-title"><i class="fas fa-upload"></i>Import CMS Data</div></div>
            <div class="card-body">
              <p style="font-size:13px;color:#64748b;margin-bottom:16px;">Restore from a previously exported JSON backup file.</p>
              <div style="display:flex;gap:10px;align-items:center;">
                <input type="file" id="import-file" accept=".json" style="font-size:13px;">
                <button class="btn btn-primary" onclick="importData()"><i class="fas fa-upload"></i>Import</button>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-header"><div class="card-title"><i class="fas fa-rotate-left" style="color:#dc2626;"></i>Reset to Defaults</div></div>
            <div class="card-body">
              <p style="font-size:13px;color:#64748b;margin-bottom:16px;">This will reset ALL CMS data to factory defaults. This action cannot be undone.</p>
              <button class="btn btn-danger" onclick="if(confirm('Are you sure? All changes will be lost!'))resetAll()"><i class="fas fa-trash"></i>Reset All Data</button>
            </div>
          </div>
        </div>

      </div><!-- /content -->
    </div><!-- /main -->
  </div><!-- /layout -->
</div><!-- /app -->

<!-- Toast container -->
<div id="toast-container"></div>

<script>
/* ──────────────────────────────────────────
   CMS BRIDGE — talks to the Laravel backend
   instead of localStorage. Reads are served
   from data embedded at page load; writes are
   POSTed to /admin/cms.
────────────────────────────────────────── */
const CSRF = document.querySelector('meta[name="csrf-token"]').content;
window.__HAS_CMS__ = @json($can['cms']);
window.__HAS_LEADS__ = @json($can['leads']);
let __CMS_STATE = @json($cms) || {};

window.CMS = {
  get(){ return JSON.parse(JSON.stringify(__CMS_STATE)); },
  save(data){
    __CMS_STATE = JSON.parse(JSON.stringify(data));
    fetch('{{ route('admin.cms.update') }}', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
      body: JSON.stringify(data),
    })
    .then(r => { if(!r.ok) throw new Error('save failed'); })
    .catch(() => toast('Could not save to server — check your connection','error'));
  },
  reset(){ return this.get(); }, // server is the source of truth
  update(section, value){ const d = this.get(); d[section] = value; this.save(d); },
};

/* ──────────────────────────────────────────
   AUTH
────────────────────────────────────────── */
function doLogout(){
  const f = document.createElement('form');
  f.method = 'POST';
  f.action = '{{ route('admin.logout') }}';
  f.innerHTML = '<input type="hidden" name="_token" value="'+CSRF+'">';
  document.body.appendChild(f);
  f.submit();
}
function changePassword(){
  const cur = document.getElementById('sec-cur').value;
  const nw  = document.getElementById('sec-new').value;
  const cf  = document.getElementById('sec-confirm').value;
  if(nw !== cf){ toast('New passwords do not match','error'); return; }
  if(nw.length < 6){ toast('Password must be at least 6 characters','error'); return; }
  fetch('{{ route('admin.password') }}', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
    body: JSON.stringify({ current: cur, password: nw, password_confirmation: cf }),
  })
  .then(async r => {
    const body = await r.json().catch(() => ({}));
    if(!r.ok) throw new Error(body.message || 'Could not change password');
    toast(body.message || 'Password changed successfully!','success');
    document.getElementById('sec-cur').value = document.getElementById('sec-new').value = document.getElementById('sec-confirm').value = '';
  })
  .catch(err => toast(err.message,'error'));
}

// Boot the panel (auth is enforced server-side)
window.addEventListener('DOMContentLoaded', initCMS);

/* ──────────────────────────────────────────
   NAVIGATION
────────────────────────────────────────── */
const SECTION_TITLES = {
  dashboard:'Dashboard / Overview', leads:'Contact Leads', media:'Media Library', settings:'General Settings', hero:'Hero Section',
  clients:'Trusted Clients', whyus:'Why Choose Us', testimonials:'Testimonials', services:'Services Manager', about:'About Page',
  pricing:'Pricing Manager', faq:'FAQ Manager', contact:'Contact Info',
  seo:'SEO Settings', security:'Security', backup:'Backup / Restore'
};
function showSection(name){
  document.querySelectorAll('.cms-section').forEach(s=>s.classList.remove('active'));
  document.querySelectorAll('.nav-item').forEach(n=>n.classList.remove('active'));
  document.getElementById('sec-'+name).classList.add('active');
  document.querySelectorAll('.nav-item').forEach(n=>{
    if(n.getAttribute('onclick')&&n.getAttribute('onclick').includes("'"+name+"'"))n.classList.add('active');
  });
  document.getElementById('topbar-title').innerHTML = SECTION_TITLES[name]+' <span>Management</span>';
  if(name==='leads') loadLeads();
  if(name==='media') loadMedia();
  if(name==='services') loadServices();
  if(name==='about') loadAbout();
}

/* ──────────────────────────────────────────
   SERVICES MANAGER
────────────────────────────────────────── */
function loadServices(){ renderServicesList(); }
function renderServicesList(){
  if(!Array.isArray(DATA.services)) DATA.services=[];
  const list=document.getElementById('services-list');
  document.getElementById('services-count').textContent='('+DATA.services.length+')';
  list.innerHTML=DATA.services.map((s,i)=>`
    <div style="border:1.5px solid #e8eef8;border-radius:14px;padding:16px;margin-bottom:12px;background:#fafbff;">
      <div style="display:grid;grid-template-columns:1fr 200px auto;gap:12px;align-items:start;">
        <div class="field" style="margin:0;"><label>Service Name</label><input type="text" value="${escHtml(s.name)}" oninput="DATA.services[${i}].name=this.value"></div>
        <div class="field" style="margin:0;"><label>Icon (fa-...)</label><input type="text" value="${escHtml(s.icon||'')}" oninput="DATA.services[${i}].icon=this.value" placeholder="fa-bullhorn"></div>
        <div style="margin-top:20px;"><button class="btn btn-danger btn-sm" onclick="deleteService(${i})"><i class="fas fa-trash"></i></button></div>
      </div>
      <div class="field" style="margin-top:10px;"><label>Description</label><textarea rows="2" oninput="DATA.services[${i}].description=this.value">${escHtml(s.description||'')}</textarea></div>
      <div style="display:grid;grid-template-columns:1fr 160px 160px;gap:12px;margin-top:4px;">
        <div class="field" style="margin:0;"><label>Features (comma separated)</label><input type="text" value="${escHtml((s.features||[]).join(', '))}" oninput="DATA.services[${i}].features=this.value.split(',').map(x=>x.trim()).filter(Boolean)"></div>
        <div class="field" style="margin:0;"><label>Button Text</label><input type="text" value="${escHtml(s.cta_text||'Get Started')}" oninput="DATA.services[${i}].cta_text=this.value"></div>
        <div class="field" style="margin:0;"><label>Button Link</label><input type="text" value="${escHtml(s.cta_link||'contact.html')}" oninput="DATA.services[${i}].cta_link=this.value"></div>
      </div>
    </div>`).join('');
}
function addService(){
  if(!Array.isArray(DATA.services)) DATA.services=[];
  DATA.services.push({ name:'New Service', icon:'fa-comment-sms', description:'Describe this service.', features:['Feature one','Feature two'], cta_text:'Get Started', cta_link:'contact.html' });
  renderServicesList();
}
function deleteService(i){ if(!confirm('Delete this service?')) return; DATA.services.splice(i,1); renderServicesList(); toast('Service removed','info'); }
function saveServices(){ window.CMS.save(DATA); toast('Services saved!'); }

/* ──────────────────────────────────────────
   ABOUT PAGE
────────────────────────────────────────── */
function loadAbout(){
  const a = DATA.about = DATA.about || {};
  const set=(id,v)=>{ const el=document.getElementById(id); if(el)el.value=v||''; };
  set('ab-heading',a.heading); set('ab-story1',a.story1); set('ab-story2',a.story2);
  set('ab-mission',a.mission); set('ab-vision',a.vision);
  if(!Array.isArray(a.stats)||!a.stats.length) a.stats=[{value:'',label:'',sub:''},{value:'',label:'',sub:''},{value:'',label:'',sub:''},{value:'',label:'',sub:''}];
  document.getElementById('about-stats').innerHTML = a.stats.map((s,i)=>`
    <div style="border:1.5px solid #e8eef8;border-radius:12px;padding:12px;background:#fafbff;">
      <div class="field" style="margin:0 0 8px;"><label>Stat ${i+1} Value</label><input type="text" value="${escHtml(s.value||'')}" oninput="DATA.about.stats[${i}].value=this.value" placeholder="500M+"></div>
      <div class="field" style="margin:0 0 8px;"><label>Label</label><input type="text" value="${escHtml(s.label||'')}" oninput="DATA.about.stats[${i}].label=this.value" placeholder="SMS Delivered"></div>
      <div class="field" style="margin:0;"><label>Subtext</label><input type="text" value="${escHtml(s.sub||'')}" oninput="DATA.about.stats[${i}].sub=this.value" placeholder="Per year"></div>
    </div>`).join('');
}
function saveAbout(){
  const g=id=>document.getElementById(id)?.value||'';
  DATA.about = Object.assign(DATA.about||{}, {
    heading:g('ab-heading'), story1:g('ab-story1'), story2:g('ab-story2'),
    mission:g('ab-mission'), vision:g('ab-vision'),
  });
  window.CMS.save(DATA);
  toast('About page saved!');
}

/* ──────────────────────────────────────────
   MEDIA LIBRARY
────────────────────────────────────────── */
function fmtBytes(b){
  if(!b) return '0 B';
  const u=['B','KB','MB','GB']; const i=Math.floor(Math.log(b)/Math.log(1024));
  return (b/Math.pow(1024,i)).toFixed(i?1:0)+' '+u[i];
}
function isImage(ext){ return ['jpg','jpeg','png','webp','svg'].includes((ext||'').toLowerCase()); }

function loadMedia(){
  const folder = document.getElementById('media-folder-filter').value;
  const url = '{{ route('admin.media.index') }}' + (folder ? ('?folder='+encodeURIComponent(folder)) : '');
  fetch(url, { headers:{'Accept':'application/json'}, credentials:'same-origin' })
    .then(r => r.json())
    .then(d => { renderMedia(d.media); populateFolders(d.folders, folder); document.getElementById('media-count').textContent = '('+d.total+')'; })
    .catch(() => toast('Could not load media','error'));
}

function populateFolders(folders, current){
  const sel = document.getElementById('media-folder-filter');
  sel.innerHTML = '<option value="">All folders</option>' + folders.map(f => `<option value="${escHtml(f)}" ${f===current?'selected':''}>${escHtml(f)}</option>`).join('');
}

function renderMedia(items){
  const grid = document.getElementById('media-grid');
  const empty = document.getElementById('media-empty');
  if(!items.length){ grid.innerHTML=''; empty.style.display='block'; return; }
  empty.style.display='none';
  grid.innerHTML = items.map(m => {
    const thumb = isImage(m.extension)
      ? `<img src="${m.url}" alt="${escHtml(m.name)}" style="width:100%;height:100px;object-fit:cover;display:block;">`
      : `<div style="height:100px;display:flex;align-items:center;justify-content:center;background:#f1f5f9;"><i class="fas fa-file-pdf" style="font-size:34px;color:#dc2626;"></i></div>`;
    return `
    <div style="border:1.5px solid #e8eef8;border-radius:12px;overflow:hidden;background:#fff;">
      <div style="position:relative;">${thumb}</div>
      <div style="padding:8px 10px;">
        <div style="font-size:11px;font-weight:600;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="${escHtml(m.name)}">${escHtml(m.name)}</div>
        <div style="font-size:10px;color:#94a3b8;margin-top:2px;">${(m.extension||'').toUpperCase()} · ${fmtBytes(m.size)}</div>
        <div style="display:flex;gap:6px;margin-top:8px;">
          <button class="btn btn-outline btn-sm" style="flex:1;" onclick="copyMediaUrl('${m.url}')" title="Copy URL"><i class="fas fa-link"></i></button>
          <button class="btn btn-danger btn-sm" onclick="deleteMedia(${m.id})" title="Delete"><i class="fas fa-trash"></i></button>
        </div>
      </div>
    </div>`;
  }).join('');
}

function copyMediaUrl(path){
  const full = location.origin + path;
  navigator.clipboard.writeText(full).then(() => toast('URL copied: '+full)).catch(() => toast('Copy failed','error'));
}

function uploadMedia(files){
  if(!files || !files.length) return;
  const fd = new FormData();
  fd.append('folder', document.getElementById('media-folder').value || 'uploads');
  for(const f of files) fd.append('files[]', f);
  document.getElementById('media-uploading').style.display='inline';
  fetch('{{ route('admin.media.store') }}', {
    method:'POST', headers:{ 'X-CSRF-TOKEN':CSRF, 'Accept':'application/json' },
    credentials:'same-origin', body: fd,
  })
  .then(async r => { const b = await r.json().catch(()=>({}));
    if(!r.ok) throw new Error(b.message || (b.errors ? Object.values(b.errors)[0][0] : 'Upload failed'));
    toast(b.message); loadMedia();
  })
  .catch(err => toast(err.message,'error'))
  .finally(() => { document.getElementById('media-uploading').style.display='none'; document.getElementById('media-input').value=''; });
}

function deleteMedia(id){
  if(!confirm('Delete this file permanently?')) return;
  fetch('{{ url('admin/media') }}/' + id, {
    method:'DELETE', headers:{ 'X-CSRF-TOKEN':CSRF, 'Accept':'application/json' }, credentials:'same-origin',
  })
  .then(async r => { const b = await r.json().catch(()=>({})); if(!r.ok) throw new Error(b.message||'Delete failed');
    toast('File deleted','info'); loadMedia(); })
  .catch(err => toast(err.message,'error'));
}

// Dropzone wiring
(function(){
  const dz = document.getElementById('media-dropzone');
  const input = document.getElementById('media-input');
  if(!dz || !input) return;
  dz.addEventListener('click', () => input.click());
  input.addEventListener('change', () => uploadMedia(input.files));
  ['dragover','dragenter'].forEach(ev => dz.addEventListener(ev, e => { e.preventDefault(); dz.style.borderColor='#2563eb'; dz.style.background='#eff6ff'; }));
  ['dragleave','drop'].forEach(ev => dz.addEventListener(ev, e => { e.preventDefault(); dz.style.borderColor='#cbd5e1'; dz.style.background='#f8fafc'; }));
  dz.addEventListener('drop', e => uploadMedia(e.dataTransfer.files));
})();

/* ──────────────────────────────────────────
   CONTACT LEADS
────────────────────────────────────────── */
const LEAD_STATUSES = ['new','in_progress','responded','closed','spam'];
const LEAD_STATUS_STYLES = {
  new:        {bg:'#fee2e2', color:'#dc2626', label:'New'},
  in_progress:{bg:'#fef3c7', color:'#b45309', label:'In progress'},
  responded:  {bg:'#dbeafe', color:'#1e40af', label:'Responded'},
  closed:     {bg:'#dcfce7', color:'#15803d', label:'Closed'},
  spam:       {bg:'#f1f5f9', color:'#64748b', label:'Spam'},
};

function leadsExportHref(){
  const status = document.getElementById('leads-status-filter').value;
  return '{{ route('admin.leads.export') }}' + (status ? ('?status=' + encodeURIComponent(status)) : '');
}

function loadLeads(){
  const status = document.getElementById('leads-status-filter').value;
  const q = document.getElementById('leads-search').value.trim();
  const params = new URLSearchParams();
  if(status) params.set('status', status);
  if(q) params.set('q', q);
  document.getElementById('leads-export-btn').href = leadsExportHref();
  fetch('{{ route('admin.leads.index') }}?' + params.toString(), {
    headers: { 'Accept':'application/json' }, credentials:'same-origin',
  })
  .then(r => r.json())
  .then(d => { renderLeads(d.leads); updateLeadsBadge(d.counts); })
  .catch(() => toast('Could not load leads','error'));
}

function updateLeadsBadge(counts){
  const badge = document.getElementById('nav-leads-badge');
  const n = counts && counts.new ? counts.new : 0;
  if(n > 0){ badge.style.display='inline-block'; badge.textContent = n; }
  else { badge.style.display='none'; }
  const ds = document.getElementById('ds-leads'); if(ds) ds.textContent = n;
}

function renderLeads(leads){
  const list = document.getElementById('leads-list');
  const empty = document.getElementById('leads-empty');
  if(!leads.length){ list.innerHTML=''; empty.style.display='block'; return; }
  empty.style.display='none';
  list.innerHTML = leads.map(l => {
    const opts = LEAD_STATUSES.map(s => `<option value="${s}" ${s===l.status?'selected':''}>${LEAD_STATUS_STYLES[s].label}</option>`).join('');
    const st = LEAD_STATUS_STYLES[l.status] || LEAD_STATUS_STYLES.new;
    const date = (l.created_at||'').replace('T',' ').slice(0,16);
    return `
    <div style="border:1.5px solid #e8eef8;border-radius:14px;padding:16px;margin-bottom:12px;background:#fafbff;">
      <div style="display:flex;justify-content:space-between;gap:12px;flex-wrap:wrap;align-items:flex-start;">
        <div style="min-width:0;">
          <div style="font-weight:700;color:#0f172a;font-size:15px;">${escHtml(l.name)}
            <span style="margin-left:8px;font-size:11px;font-weight:700;padding:2px 9px;border-radius:10px;background:${st.bg};color:${st.color};">${st.label}</span>
          </div>
          <div style="font-size:12px;color:#64748b;margin-top:3px;">
            ${l.email?`<i class="fas fa-envelope" style="margin-right:4px;"></i>${escHtml(l.email)}`:''}
            ${l.phone?`<span style="margin-left:10px;"><i class="fas fa-phone" style="margin-right:4px;"></i>${escHtml(l.phone)}</span>`:''}
            ${l.company?`<span style="margin-left:10px;"><i class="fas fa-building" style="margin-right:4px;"></i>${escHtml(l.company)}</span>`:''}
          </div>
        </div>
        <div style="font-size:11px;color:#94a3b8;white-space:nowrap;">${date}${l.source?' · '+escHtml(l.source):''}</div>
      </div>
      ${l.message?`<div style="margin-top:10px;font-size:13px;color:#334155;background:#fff;border:1px solid #e8eef8;border-radius:10px;padding:10px 12px;">${escHtml(l.message)}</div>`:''}
      <div style="display:grid;grid-template-columns:180px 1fr auto;gap:10px;align-items:end;margin-top:12px;">
        <div class="field" style="margin:0;"><label>Status</label>
          <select onchange="updateLead(${l.id}, {status:this.value})" style="width:100%;border:1.5px solid #e8eef8;border-radius:8px;padding:7px 10px;font-size:13px;">${opts}</select>
        </div>
        <div class="field" style="margin:0;"><label>Admin notes</label>
          <input type="text" id="lead-notes-${l.id}" value="${escHtml(l.admin_notes||'')}" placeholder="Internal note…" style="width:100%;border:1.5px solid #e8eef8;border-radius:8px;padding:7px 10px;font-size:13px;">
        </div>
        <div style="display:flex;gap:6px;">
          <button class="btn btn-success btn-sm" onclick="updateLead(${l.id}, {admin_notes:document.getElementById('lead-notes-${l.id}').value})"><i class="fas fa-save"></i>Save</button>
          <button class="btn btn-danger btn-sm" onclick="deleteLead(${l.id})"><i class="fas fa-trash"></i></button>
        </div>
      </div>
    </div>`;
  }).join('');
}

function updateLead(id, payload){
  fetch('{{ url('admin/leads') }}/' + id, {
    method:'PATCH',
    headers:{ 'Content-Type':'application/json', 'X-CSRF-TOKEN':CSRF, 'Accept':'application/json' },
    credentials:'same-origin', body: JSON.stringify(payload),
  })
  .then(async r => { const b = await r.json().catch(()=>({})); if(!r.ok) throw new Error(b.message||'Update failed');
    toast('Lead updated'); updateLeadsBadge(b.counts); })
  .catch(err => toast(err.message,'error'));
}

function deleteLead(id){
  if(!confirm('Delete this lead permanently?')) return;
  fetch('{{ url('admin/leads') }}/' + id, {
    method:'DELETE',
    headers:{ 'X-CSRF-TOKEN':CSRF, 'Accept':'application/json' }, credentials:'same-origin',
  })
  .then(async r => { const b = await r.json().catch(()=>({})); if(!r.ok) throw new Error(b.message||'Delete failed');
    toast('Lead deleted','info'); updateLeadsBadge(b.counts); loadLeads(); })
  .catch(err => toast(err.message,'error'));
}

/* ──────────────────────────────────────────
   TOAST
────────────────────────────────────────── */
function toast(msg, type='success'){
  const c = document.getElementById('toast-container');
  const t = document.createElement('div');
  t.className = `toast toast-${type}`;
  t.innerHTML = `<i class="fas fa-${type==='success'?'check-circle':type==='error'?'times-circle':'info-circle'}"></i>${msg}`;
  c.appendChild(t);
  setTimeout(()=>t.remove(), 3500);
}

/* ──────────────────────────────────────────
   INIT
────────────────────────────────────────── */
let DATA = {};
function initCMS(){
  // Site-content panels only load for users with the CMS capability.
  if(window.__HAS_CMS__){
    DATA = window.CMS.get();
    loadDashboard();
    loadSettings();
    loadHero();
    loadClients();
    loadWhyUs();
    loadPricing();
    loadFaq();
    loadTestimonials();
    loadContact();
    loadSEO();
    loadServices();
    loadAbout();
  }
  if(window.__HAS_LEADS__) refreshLeadsBadge();
}

// Lightweight: fetch only the lead counts to populate the sidebar badge on load.
function refreshLeadsBadge(){
  fetch('{{ route('admin.leads.index') }}?status=__count__', { headers:{'Accept':'application/json'}, credentials:'same-origin' })
    .then(r => r.json())
    .then(d => updateLeadsBadge(d.counts))
    .catch(() => {});
}

/* ──────────────────────────────────────────
   DASHBOARD
────────────────────────────────────────── */
function loadDashboard(){
  document.getElementById('ds-clients').textContent = DATA.clients.length;
  document.getElementById('ds-pricing').textContent = DATA.pricingNM.length + DATA.pricingM.length;
  document.getElementById('ds-faq').textContent = DATA.faq.length;
  document.getElementById('ds-testi').textContent = DATA.testimonials.length;
}

/* ──────────────────────────────────────────
   SETTINGS
────────────────────────────────────────── */
function loadSettings(){
  const s = DATA.settings;
  const set = (id,v)=>{ const el=document.getElementById(id); if(el)el.value=v||''; };
  set('s-siteName',s.siteName); set('s-tagline',s.tagline);
  set('s-phone',s.phone); set('s-email',s.email);
  set('s-whatsapp',s.whatsapp); set('s-address',s.address);
  set('s-facebook',s.facebook); set('s-linkedin',s.linkedin); set('s-youtube',s.youtube);
  set('s-copyright',s.copyright); set('s-btrc',s.btrc);
  set('s-loginUrl',s.loginUrl); set('s-registerUrl',s.registerUrl);
}
function saveSettings(){
  const g = id => document.getElementById(id)?.value || '';
  DATA.settings = { siteName:g('s-siteName'), tagline:g('s-tagline'), phone:g('s-phone'), email:g('s-email'),
    whatsapp:g('s-whatsapp'), address:g('s-address'), facebook:g('s-facebook'), linkedin:g('s-linkedin'),
    youtube:g('s-youtube'), copyright:g('s-copyright'), btrc:g('s-btrc'),
    loginUrl:g('s-loginUrl'), registerUrl:g('s-registerUrl') };
  window.CMS.save(DATA);
  toast('Settings saved successfully!');
}

/* ──────────────────────────────────────────
   HERO
────────────────────────────────────────── */
function loadHero(){
  const h = DATA.hero;
  const set = (id,v)=>{ const el=document.getElementById(id); if(el)el.value=v||''; };
  set('h-badge',h.badge); set('h-line1',h.line1); set('h-line2',h.line2Gradient); set('h-line3',h.line3);
  set('h-subtitle',h.subtitle); set('h-btn1text',h.btn1Text); set('h-btn1link',h.btn1Link);
  set('h-btn2text',h.btn2Text); set('h-btn2link',h.btn2Link);
  set('h-s1num',h.stat1Num); set('h-s2num',h.stat2Num); set('h-s3num',h.stat3Num);
  set('h-s1lbl',h.stat1Label); set('h-s2lbl',h.stat2Label); set('h-s3lbl',h.stat3Label);
}
function saveHero(){
  const g = id => document.getElementById(id)?.value || '';
  DATA.hero = { badge:g('h-badge'), line1:g('h-line1'), line2Gradient:g('h-line2'), line3:g('h-line3'),
    subtitle:g('h-subtitle'), btn1Text:g('h-btn1text'), btn1Link:g('h-btn1link'),
    btn2Text:g('h-btn2text'), btn2Link:g('h-btn2link'),
    stat1Num:g('h-s1num'), stat2Num:g('h-s2num'), stat3Num:g('h-s3num'),
    stat1Label:g('h-s1lbl'), stat2Label:g('h-s2lbl'), stat3Label:g('h-s3lbl') };
  window.CMS.save(DATA);
  toast('Hero section saved!');
}

/* ──────────────────────────────────────────
   CLIENTS
────────────────────────────────────────── */
// Sync color input ↔ hex text
document.getElementById('nc-color').addEventListener('input',function(){ document.getElementById('nc-colorhex').value=this.value; updateClientPreview(); });
document.getElementById('nc-colorhex').addEventListener('input',function(){ if(/^#[0-9a-fA-F]{6}$/.test(this.value)){ document.getElementById('nc-color').value=this.value; updateClientPreview(); }});
document.getElementById('nc-name').addEventListener('input',updateClientPreview);
document.getElementById('nc-icon').addEventListener('input',updateClientPreview);
document.getElementById('nc-bg').addEventListener('input',updateClientPreview);

function updateClientPreview(){
  const name=document.getElementById('nc-name').value;
  const icon=document.getElementById('nc-icon').value;
  const color=document.getElementById('nc-colorhex').value||'#003087';
  const bg=document.getElementById('nc-bg').value||`linear-gradient(135deg,${color},${color}dd)`;
  const prev=document.getElementById('nc-preview');
  if(name||icon){ prev.style.display='flex'; prev.style.alignItems='center'; prev.style.gap='12px';
    document.getElementById('nc-prev-icon').style.cssText=`width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;background:${bg};`;
    document.getElementById('nc-prev-icon').innerHTML=icon?`<i class="fas ${icon}" style="color:white;font-size:16px;"></i>`:''
    document.getElementById('nc-prev-name').style.color=color;
    document.getElementById('nc-prev-name').textContent=name;
  }
}

function loadClients(){
  renderClientsGrid();
  document.getElementById('client-count').textContent = DATA.clients.length;
}
function renderClientsGrid(){
  const grid = document.getElementById('clients-grid');
  grid.innerHTML = DATA.clients.map((c,i)=>`
    <div class="client-card" id="cc-${c.id}">
      <div class="client-icon-prev" style="background:${c.bg};">
        <i class="fas ${c.icon}" style="color:white;font-size:15px;"></i>
      </div>
      <div style="flex:1;min-width:0;">
        <div class="client-card-name" id="cc-name-${c.id}">${c.name}</div>
        <div style="font-size:10px;color:#94a3b8;">${c.icon}</div>
      </div>
      <div class="client-card-actions">
        <button class="btn btn-outline btn-sm" onclick="editClient(${c.id})" title="Edit"><i class="fas fa-pen"></i></button>
        <button class="btn btn-danger btn-sm" onclick="deleteClient(${c.id})" title="Delete"><i class="fas fa-trash"></i></button>
      </div>
    </div>`).join('');
  document.getElementById('client-count').textContent = DATA.clients.length;
}
function addClient(){
  const name = document.getElementById('nc-name').value.trim();
  const icon = document.getElementById('nc-icon').value.trim() || 'fa-building';
  const color= document.getElementById('nc-colorhex').value || '#003087';
  const bg   = document.getElementById('nc-bg').value || `linear-gradient(135deg,${color},${color}bb)`;
  if(!name){ toast('Please enter a company name','error'); return; }
  const id = Date.now();
  DATA.clients.push({ id, name, icon, color, bg });
  window.CMS.save(DATA);
  renderClientsGrid();
  document.getElementById('nc-name').value=''; document.getElementById('nc-icon').value='';
  document.getElementById('nc-bg').value=''; document.getElementById('nc-preview').style.display='none';
  toast(`"${name}" added to clients!`);
}
function editClient(id){
  const c = DATA.clients.find(x=>x.id===id);
  if(!c) return;
  const name = prompt('Company Name:', c.name);
  if(name === null) return;
  const icon = prompt('Icon class (e.g. fa-signal):', c.icon) || c.icon;
  const color = prompt('Text color (hex):', c.color) || c.color;
  const bg = prompt('Background gradient:', c.bg) || c.bg;
  c.name = name; c.icon = icon; c.color = color; c.bg = bg;
  window.CMS.save(DATA);
  renderClientsGrid();
  toast('Client updated!');
}
function deleteClient(id){
  if(!confirm('Delete this client?')) return;
  DATA.clients = DATA.clients.filter(c=>c.id!==id);
  window.CMS.save(DATA);
  renderClientsGrid();
  toast('Client removed','info');
}
function sortClients(){
  DATA.clients.sort((a,b)=>a.name.localeCompare(b.name));
  window.CMS.save(DATA);
  renderClientsGrid();
  toast('Sorted A–Z');
}

/* ──────────────────────────────────────────
   WHY CHOOSE US
────────────────────────────────────────── */
function loadWhyUs(){ renderWhyList(); }
function renderWhyList(){
  const list = document.getElementById('why-list');
  list.innerHTML = DATA.whyUs.map((w,i)=>`
    <div style="display:grid;grid-template-columns:auto 1fr 1fr auto;gap:12px;align-items:start;padding:14px;background:#f8fafc;border-radius:12px;margin-bottom:10px;border:1px solid #e8eef8;">
      <div style="width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;background:${w.bg};flex-shrink:0;margin-top:20px;">
        <i class="fas ${w.icon} text-white" style="font-size:18px;"></i>
      </div>
      <div class="field"><label>Title</label><input type="text" value="${w.title}" oninput="DATA.whyUs[${i}].title=this.value"></div>
      <div class="field"><label>Icon (fa-...)</label><input type="text" value="${w.icon}" oninput="DATA.whyUs[${i}].icon=this.value"></div>
      <div style="display:flex;gap:6px;margin-top:20px;">
        <button class="btn btn-danger btn-sm" onclick="removeWhyItem(${i})"><i class="fas fa-trash"></i></button>
      </div>
      <div></div>
      <div class="field" style="grid-column:span 2;"><label>Description</label><textarea oninput="DATA.whyUs[${i}].desc=this.value" rows="2">${w.desc}</textarea></div>
      <div></div>
      <div></div>
      <div class="field"><label>Tag / Label</label><input type="text" value="${w.tag||''}" oninput="DATA.whyUs[${i}].tag=this.value"></div>
      <div class="field"><label>Background Gradient</label><input type="text" value="${w.bg}" oninput="DATA.whyUs[${i}].bg=this.value"></div>
    </div>`).join('');
}
function addWhyItem(){
  DATA.whyUs.push({ id:Date.now(), icon:'fa-star', bg:'linear-gradient(135deg,#003087,#009cde)', title:'New Feature', desc:'Description here.', tag:'Tag here' });
  renderWhyList();
}
function removeWhyItem(i){
  if(!confirm('Remove this item?')) return;
  DATA.whyUs.splice(i,1);
  renderWhyList();
  toast('Item removed','info');
}
function saveWhyUs(){ window.CMS.save(DATA); toast('Why Choose Us saved!'); }

/* ──────────────────────────────────────────
   PRICING
────────────────────────────────────────── */
function loadPricing(){ renderPricingTable('nm'); renderPricingTable('m'); }
function renderPricingTable(type){
  const data = type==='nm'?DATA.pricingNM:DATA.pricingM;
  const body = document.getElementById('pricing-'+type+'-body');
  body.innerHTML = data.map((s,i)=>`
    <tr>
      <td><input type="text" value="${s.tier}" oninput="DATA.pricing${type==='nm'?'NM':'M'}[${i}].tier=this.value" style="border:1.5px solid #e8eef8;border-radius:8px;padding:6px 10px;font-size:13px;width:120px;"></td>
      <td><input type="number" value="${s.min}" oninput="DATA.pricing${type==='nm'?'NM':'M'}[${i}].min=+this.value" style="border:1.5px solid #e8eef8;border-radius:8px;padding:6px 10px;font-size:13px;width:100px;"></td>
      <td><input type="number" value="${s.max||0}" oninput="DATA.pricing${type==='nm'?'NM':'M'}[${i}].max=+this.value||null" style="border:1.5px solid #e8eef8;border-radius:8px;padding:6px 10px;font-size:13px;width:100px;"><div style="font-size:10px;color:#94a3b8;margin-top:2px;">0 = unlimited</div></td>
      <td>
        <div style="display:flex;align-items:center;gap:4px;">
          <span style="font-size:14px;font-weight:700;color:#64748b;">৳</span>
          <input type="number" step="0.01" value="${s.price}" oninput="DATA.pricing${type==='nm'?'NM':'M'}[${i}].price=+this.value" style="border:1.5px solid #e8eef8;border-radius:8px;padding:6px 10px;font-size:13px;font-weight:700;color:#003087;width:90px;">
          <span style="font-size:11px;color:#94a3b8;">/SMS</span>
        </div>
      </td>
      <td>
        <div style="display:flex;gap:6px;">
          <button class="btn btn-success btn-sm" onclick="savePricingType('${type}')"><i class="fas fa-save"></i>Save</button>
          <button class="btn btn-danger btn-sm" onclick="deletePricingSlab('${type}',${i})"><i class="fas fa-trash"></i></button>
        </div>
      </td>
    </tr>`).join('');
}
function addPricingSlab(type){
  const arr = type==='nm'?DATA.pricingNM:DATA.pricingM;
  arr.push({ id:Date.now(), tier:'New Tier', min:0, max:0, price: type==='nm'?0.35:0.55 });
  renderPricingTable(type);
}
function deletePricingSlab(type,i){
  if(!confirm('Delete this pricing slab?')) return;
  if(type==='nm') DATA.pricingNM.splice(i,1);
  else DATA.pricingM.splice(i,1);
  window.CMS.save(DATA);
  renderPricingTable(type);
  toast('Slab deleted','info');
}
function savePricingType(type){
  window.CMS.save(DATA);
  toast(`${type==='nm'?'Non-Masking':'Masking'} pricing saved!`);
}

/* ──────────────────────────────────────────
   FAQ
────────────────────────────────────────── */
function loadFaq(){ renderFaqList(); }
function renderFaqList(){
  const list = document.getElementById('faq-list');
  list.innerHTML = DATA.faq.map((f,i)=>`
    <div style="border:1.5px solid #e8eef8;border-radius:14px;padding:16px;margin-bottom:10px;background:#fafbff;">
      <div style="display:flex;gap:10px;align-items:flex-start;">
        <div style="flex:1;">
          <div class="field" style="margin-bottom:10px;">
            <label>Question</label>
            <input type="text" value="${escHtml(f.q)}" oninput="DATA.faq[${i}].q=this.value" style="width:100%;">
          </div>
          <div class="field">
            <label>Answer</label>
            <textarea oninput="DATA.faq[${i}].a=this.value" rows="3" style="width:100%;">${escHtml(f.a)}</textarea>
          </div>
        </div>
        <div style="display:flex;gap:6px;margin-top:20px;flex-shrink:0;">
          ${i>0?`<button class="btn btn-outline btn-sm" onclick="moveFaq(${i},-1)" title="Move up"><i class="fas fa-chevron-up"></i></button>`:''}
          ${i<DATA.faq.length-1?`<button class="btn btn-outline btn-sm" onclick="moveFaq(${i},1)" title="Move down"><i class="fas fa-chevron-down"></i></button>`:''}
          <button class="btn btn-danger btn-sm" onclick="deleteFaq(${i})"><i class="fas fa-trash"></i></button>
        </div>
      </div>
    </div>`).join('');
  document.getElementById('faq-count').textContent = DATA.faq.length;
}
function addFaq(){
  DATA.faq.push({ id:Date.now(), q:'New Question?', a:'Answer here.' });
  renderFaqList();
}
function deleteFaq(i){ if(!confirm('Delete this FAQ?')) return; DATA.faq.splice(i,1); renderFaqList(); toast('FAQ deleted','info'); }
function moveFaq(i,dir){ const arr=DATA.faq; const tmp=arr[i]; arr[i]=arr[i+dir]; arr[i+dir]=tmp; renderFaqList(); }
function saveFaq(){ window.CMS.save(DATA); toast('FAQs saved!'); loadDashboard(); }

/* ──────────────────────────────────────────
   TESTIMONIALS
────────────────────────────────────────── */
function loadTestimonials(){ renderTestiList(); }
function renderTestiList(){
  const list = document.getElementById('testi-list');
  list.innerHTML = DATA.testimonials.map((t,i)=>`
    <div style="border:1.5px solid #e8eef8;border-radius:14px;padding:16px;margin-bottom:10px;background:#fafbff;">
      <div style="display:grid;grid-template-columns:1fr 1fr auto;gap:12px;align-items:start;">
        <div class="field"><label>Name</label><input type="text" value="${escHtml(t.name)}" oninput="DATA.testimonials[${i}].name=this.value"></div>
        <div class="field"><label>Role / Company</label><input type="text" value="${escHtml(t.role)}" oninput="DATA.testimonials[${i}].role=this.value"></div>
        <div style="display:flex;gap:6px;margin-top:20px;">
          <button class="btn btn-danger btn-sm" onclick="deleteTesti(${i})"><i class="fas fa-trash"></i></button>
        </div>
        <div class="field" style="grid-column:span 2;"><label>Testimonial Text</label><textarea rows="3" oninput="DATA.testimonials[${i}].text=this.value">${escHtml(t.text)}</textarea></div>
        <div style="margin-top:20px;">
          <label style="font-size:11px;font-weight:700;text-transform:uppercase;color:#64748b;letter-spacing:.5px;display:block;margin-bottom:6px;">Rating</label>
          <div class="star-input" id="stars-${i}">${[1,2,3,4,5].map(n=>`<span class="${n<=t.rating?'on':''}" onclick="setRating(${i},${n})">★</span>`).join('')}</div>
        </div>
      </div>
    </div>`).join('');
}
function addTestimonial(){
  DATA.testimonials.push({ id:Date.now(), name:'Client Name', role:'Company', text:'Great service!', rating:5, initials:'XX', bg:'#bfdbfe', color:'#1e40af' });
  renderTestiList();
}
function deleteTesti(i){ if(!confirm('Delete?')) return; DATA.testimonials.splice(i,1); renderTestiList(); toast('Deleted','info'); }
function setRating(i,r){ DATA.testimonials[i].rating=r; renderTestiList(); }
function saveTestimonials(){ window.CMS.save(DATA); toast('Testimonials saved!'); }

/* ──────────────────────────────────────────
   CONTACT
────────────────────────────────────────── */
function loadContact(){
  const c = DATA.contactInfo || {};
  const set=(id,v)=>{ const el=document.getElementById(id); if(el)el.value=v||''; };
  set('ci-phone',c.phone); set('ci-email',c.email); set('ci-whatsapp',c.whatsapp);
  set('ci-address',c.address); set('ci-hours',c.officeHours); set('ci-response',c.responseTime);
}
function saveContact(){
  const g=id=>document.getElementById(id)?.value||'';
  DATA.contactInfo = { phone:g('ci-phone'), email:g('ci-email'), whatsapp:g('ci-whatsapp'),
    address:g('ci-address'), officeHours:g('ci-hours'), responseTime:g('ci-response') };
  window.CMS.save(DATA);
  toast('Contact info saved!');
}

/* ──────────────────────────────────────────
   SEO
────────────────────────────────────────── */
const SEO_PAGES = { home:'Home', about:'About', services:'Services', pricing:'Pricing', calculator:'Calculator', faq:'FAQ', contact:'Contact' };
function loadSEO(){
  const container = document.getElementById('seo-pages');
  container.innerHTML = Object.entries(SEO_PAGES).map(([key,label])=>{
    const s = DATA.seo[key] || {};
    return `
      <div class="card" style="margin-bottom:16px;">
        <div class="card-header"><div class="card-title"><i class="fas fa-file"></i>${label} Page</div></div>
        <div class="card-body">
          <div class="form-grid cols1">
            <div class="field"><label>SEO Title</label><input type="text" id="seo-${key}-title" value="${escHtml(s.title||'')}"></div>
            <div class="field"><label>Meta Description</label><textarea id="seo-${key}-desc" rows="2">${escHtml(s.desc||'')}</textarea></div>
            <div class="field"><label>Keywords</label><input type="text" id="seo-${key}-kw" value="${escHtml(s.keywords||'')}"><div class="hint">Comma separated</div></div>
          </div>
        </div>
      </div>`;
  }).join('');
}
function saveSEO(){
  DATA.seo = {};
  Object.keys(SEO_PAGES).forEach(key=>{
    DATA.seo[key]={
      title:document.getElementById('seo-'+key+'-title')?.value||'',
      desc:document.getElementById('seo-'+key+'-desc')?.value||'',
      keywords:document.getElementById('seo-'+key+'-kw')?.value||''
    };
  });
  window.CMS.save(DATA);
  toast('SEO settings saved!');
}

/* ──────────────────────────────────────────
   BACKUP
────────────────────────────────────────── */
function exportData(){
  const blob = new Blob([JSON.stringify(DATA,null,2)],{type:'application/json'});
  const a = document.createElement('a'); a.href=URL.createObjectURL(blob);
  a.download='notifysms-cms-backup-'+new Date().toISOString().slice(0,10)+'.json';
  a.click(); toast('Backup downloaded!');
}
function importData(){
  const file = document.getElementById('import-file').files[0];
  if(!file){ toast('Select a JSON file first','error'); return; }
  const r = new FileReader();
  r.onload = e => {
    try{
      const d = JSON.parse(e.target.result);
      window.CMS.save(d); DATA=d;
      initCMS(); toast('Data imported successfully!');
    } catch(err){ toast('Invalid JSON file','error'); }
  };
  r.readAsText(file);
}
function resetAll(){
  DATA = window.CMS.reset();
  initCMS(); toast('Reset to defaults','info');
}

/* ──────────────────────────────────────────
   SAVE ALL
────────────────────────────────────────── */
function saveAll(){
  saveSettings(); saveHero(); saveWhyUs();
  saveFaq(); saveTestimonials(); saveContact(); saveSEO();
  window.CMS.save(DATA);
  toast('All changes saved successfully! 🎉');
}

/* ──────────────────────────────────────────
   UTILS
────────────────────────────────────────── */
function toggleAdminSidebar(){
  document.querySelector(".sidebar").classList.toggle("open");
  document.getElementById("sidebar-backdrop").classList.toggle("show");
}
function escHtml(s){ return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }
</script>
</body>
</html>

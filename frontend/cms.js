/**
 * NotifySMS CMS — Shared Data Manager
 * Reads/writes all site content from/to localStorage.
 * Include this script on every frontend page.
 */

const CMS_KEY = 'notifysms_cms_v1';

const CMS_DEFAULTS = {
  settings: {
    siteName: 'NotifySMS',
    tagline: '#1 Bulk SMS Platform in Bangladesh',
    phone: '+880 1XXXXXXXXX',
    email: 'info@notifysms.com.bd',
    address: 'Dhaka, Bangladesh',
    whatsapp: '+880 1XXXXXXXXX',
    facebook: '#',
    linkedin: '#',
    youtube: '#',
    copyright: '© 2025 NotifySMS. All rights reserved.',
    btrc: 'BTRC Licensed | Powered by NotifySMS Bangladesh',
    loginUrl: 'https://customer.notifysms.com.bd/login',
    registerUrl: '#',
  },
  hero: {
    badge: '#1 Bulk SMS Platform in Bangladesh',
    line1: 'Send Millions of',
    line2Gradient: 'SMS Messages',
    line3: 'Instantly',
    subtitle: "Bangladesh's most reliable bulk SMS service. Promotional, transactional, masking & API SMS — all in one platform with real-time delivery reports.",
    btn1Text: 'Get Started Free', btn1Link: 'contact.html',
    btn2Text: 'Calculate Price',  btn2Link: 'calculator.html',
    stat1Num: '500M+', stat1Label: 'SMS Delivered',
    stat2Num: '10K+',  stat2Label: 'Active Clients',
    stat3Num: '99.9%', stat3Label: 'Uptime SLA',
  },
  clients: [
    { id:1,  name:'Daraz',          icon:'fa-shopping-cart',        color:'#ea580c', bg:'linear-gradient(135deg,#f97316,#ea580c)' },
    { id:2,  name:'Chaldal',        icon:'fa-leaf',                  color:'#15803d', bg:'linear-gradient(135deg,#16a34a,#15803d)' },
    { id:3,  name:'Pathao',         icon:'fa-motorcycle',            color:'#dc2626', bg:'linear-gradient(135deg,#dc2626,#b91c1c)' },
    { id:4,  name:'bKash',          icon:'fa-mobile-screen-button',  color:'#c2185b', bg:'linear-gradient(135deg,#e91e8c,#c2185b)' },
    { id:5,  name:'Nagad',          icon:'fa-coins',                 color:'#d97706', bg:'linear-gradient(135deg,#f59e0b,#d97706)' },
    { id:6,  name:'Bangladesh Bank',icon:'fa-university',            color:'#003087', bg:'linear-gradient(135deg,#003087,#0046b8)' },
    { id:7,  name:'Shohoz',         icon:'fa-car',                   color:'#0284c7', bg:'linear-gradient(135deg,#0ea5e9,#0284c7)' },
    { id:8,  name:'HungryNaki',     icon:'fa-utensils',              color:'#dc2626', bg:'linear-gradient(135deg,#ef4444,#dc2626)' },
    { id:9,  name:'ShopUp',         icon:'fa-store',                 color:'#6d28d9', bg:'linear-gradient(135deg,#7c3aed,#6d28d9)' },
    { id:10, name:'LabAid',         icon:'fa-hospital',              color:'#0e7490', bg:'linear-gradient(135deg,#0891b2,#0e7490)' },
    { id:11, name:'10 Minute School',icon:'fa-graduation-cap',       color:'#047857', bg:'linear-gradient(135deg,#059669,#047857)' },
    { id:12, name:'Bikroy',         icon:'fa-tag',                   color:'#003087', bg:'linear-gradient(135deg,#003087,#009cde)' },
    { id:13, name:'DBBL',           icon:'fa-landmark',              color:'#1e293b', bg:'linear-gradient(135deg,#0f172a,#1e293b)' },
    { id:14, name:'Shajgoj',        icon:'fa-leaf',                  color:'#15803d', bg:'linear-gradient(135deg,#16a34a,#15803d)' },
    { id:15, name:'RedX',           icon:'fa-truck',                 color:'#b45309', bg:'linear-gradient(135deg,#d97706,#b45309)' },
    { id:16, name:'Grameenphone',   icon:'fa-signal',                color:'#14532d', bg:'linear-gradient(135deg,#16a34a,#14532d)' },
    { id:17, name:'Robi Axiata',    icon:'fa-tv',                    color:'#0c4a6e', bg:'linear-gradient(135deg,#0284c7,#0c4a6e)' },
    { id:18, name:'Banglalink',     icon:'fa-fire',                  color:'#9a3412', bg:'linear-gradient(135deg,#ea580c,#9a3412)' },
    { id:19, name:'BRAC',           icon:'fa-globe',                 color:'#065f46', bg:'linear-gradient(135deg,#059669,#065f46)' },
    { id:20, name:'Prothom Alo',    icon:'fa-newspaper',             color:'#075985', bg:'linear-gradient(135deg,#0369a1,#075985)' },
  ],
  pricingNM: [
    { id:1, tier:'Starter',    min:5000,   max:10000,  price:0.35 },
    { id:2, tier:'Business',   min:11000,  max:20000,  price:0.30 },
    { id:3, tier:'Enterprise', min:40000,  max:99999,  price:0.28 },
    { id:4, tier:'Elite',      min:100000, max:null,   price:0.26 },
  ],
  pricingM: [
    { id:1, tier:'Starter',    min:5000,   max:10000,  price:0.55 },
    { id:2, tier:'Business',   min:11000,  max:20000,  price:0.52 },
    { id:3, tier:'Enterprise', min:40000,  max:99999,  price:0.50 },
    { id:4, tier:'Elite',      min:100000, max:null,   price:0.48 },
  ],
  faq: [
    { id:1, q:'Bulk SMS ki ebong ki kaje lage?', a:'Bulk SMS holo ek sathe baro sankhyok manush-ke SMS pathano-r service. Promotional offer, OTP, transactional alert, ebong marketing campaign-er jonno use kora hoy.' },
    { id:2, q:'SMS delivery time koto lage?', a:'Amader system-e 1,000 SMS approximately 3 seconds-e deliver hoy. Network condition-er upor nirbhor kore kichukhon bethe jete pare tobe sobcheye beshi 30 seconds.' },
    { id:3, q:'Masking ebong Non-masking SMS er parthokyo ki?', a:'Masking SMS-e sender ID hisebe apnar company naam dekhabe. Non-masking-e numeric number dekhabe. Masking SMS-er price ektu beshi kintu brand trust baraye.' },
    { id:4, q:'Minimum koto SMS order korte hobe?', a:'Amader minimum order quantity 5,000 SMS. Tar kome custom pricing-er jonno sales team-er shathe contact korun.' },
    { id:5, q:'API integration possible ki?', a:'Hya, amader REST API v2 available. PHP, Python, Node.js, Java shob popular language-er jonno SDK thakbe.' },
    { id:6, q:'Payment process ki?', a:'bKash, Nagad, bank transfer, ebong online card payment accept kori. bKash/Nagad-e payment korar 5-10 minutes-er moddhe account-e credit add hoy.' },
  ],
  testimonials: [
    { id:1, name:'Ahmed Rahman',   role:'CEO, TechBD Solutions', text:'NotifySMS transformed our marketing campaigns. The delivery rate is exceptional and pricing is very competitive.', rating:5, initials:'AR', bg:'#bfdbfe', color:'#1e40af' },
    { id:2, name:'Farhan Khan',    role:'CTO, ShopBD.com',       text:'API integration was seamless. Within 2 hours we had SMS running. Support team is incredibly responsive!', rating:5, initials:'FK', bg:'#3b82f6', color:'#ffffff', featured:true },
    { id:3, name:'Tahmina Islam',  role:'VP Technology, FinTech BD', text:'We send OTP for our banking app. NotifySMS delivers in under 2 seconds every time. Never let us down.', rating:5, initials:'TI', bg:'#ddd6fe', color:'#5b21b6' },
  ],
  seo: {
    home:       { title:'NotifySMS – #1 Bulk SMS Platform Bangladesh', desc:'Bangladesh most reliable bulk SMS service. Send promotional, transactional, masking & API SMS.', keywords:'bulk sms bangladesh, sms gateway, masking sms' },
    about:      { title:'About NotifySMS – Trusted Bulk SMS Provider', desc:'Learn about NotifySMS — founded 2015, 10K+ clients, BTRC certified.', keywords:'about notifysms, sms company bangladesh' },
    services:   { title:'Bulk SMS Services – NotifySMS', desc:'Complete SMS solutions: Promotional, Masking, OTP, API, Campaign SMS.', keywords:'bulk sms services, masking sms, otp sms' },
    pricing:    { title:'SMS Pricing – NotifySMS Bangladesh', desc:'Transparent SMS pricing including VAT, TAX & Dipping. From ৳0.26/SMS.', keywords:'sms price bangladesh, bulk sms rate' },
    calculator: { title:'SMS Price Calculator – NotifySMS', desc:'Calculate your bulk SMS cost instantly. Masking & Non-masking rates.', keywords:'sms calculator, bulk sms price calculator' },
    faq:        { title:'FAQ – NotifySMS', desc:'Frequently asked questions about bulk SMS, pricing, API, and more.', keywords:'sms faq, bulk sms questions' },
    contact:    { title:'Contact NotifySMS – Get in Touch', desc:'Contact our SMS experts. 24/7 support available.', keywords:'contact notifysms, sms support' },
  },
  contactInfo: {
    phone: '+880 1XXXXXXXXX',
    email: 'info@notifysms.com.bd',
    address: 'Dhaka, Bangladesh',
    whatsapp: '+880 1XXXXXXXXX',
    officeHours: 'Sat–Thu: 9AM–9PM | Fri: 2PM–9PM',
    responseTime: '1 business hour',
  },
  whyUs: [
    { id:1, icon:'fa-tags',         bg:'linear-gradient(135deg,#003087,#0055cc)', title:'Cheap SMS Rates',    desc:'Starting at just ৳0.26/SMS including all taxes.', tag:'Starting ৳0.26/SMS' },
    { id:2, icon:'fa-location-dot', bg:'linear-gradient(135deg,#059669,#10b981)', title:'Location Based SMS', desc:'Target by division, district or 500m–50km radius.', tag:'64 Districts covered' },
    { id:3, icon:'fa-headset',      bg:'linear-gradient(135deg,#7c3aed,#8b5cf6)', title:'24×7 Live Support',  desc:'Expert support with under 1 hour response time.', tag:'Always online' },
    { id:4, icon:'fa-arrows-left-right', bg:'linear-gradient(135deg,#f97316,#fb923c)', title:'Two-way SMS', desc:'Send and receive SMS — enable customer replies.', tag:'Send & Receive' },
    { id:5, icon:'fa-bolt',         bg:'linear-gradient(135deg,#06b6d4,#0ea5e9)', title:'Instant Delivery',  desc:'1,000 SMS in under 3 seconds with tracking.', tag:'<3 seconds delivery' },
    { id:6, icon:'fa-shield-halved',bg:'linear-gradient(135deg,#10b981,#059669)', title:'BTRC Certified',    desc:'Fully licensed & 100% compliant and secure.', tag:'Licensed & Verified' },
    { id:7, icon:'fa-code',         bg:'linear-gradient(135deg,#6366f1,#8b5cf6)', title:'Simple REST API',   desc:'Integrate in minutes — PHP, Python, Node.js SDKs.', tag:'REST API v2' },
    { id:8, icon:'fa-signal',       bg:'linear-gradient(135deg,#f59e0b,#ffc439)', title:'All 4 Operators',   desc:'GP, Robi, Banglalink & Teletalk with auto failover.', tag:'99.9% uptime' },
  ],
};

// ─── Backend API ─────────────────────────────────────────────────
// Override on a page (before this script) with:  window.NOTIFY_API_BASE = '...';
const NOTIFY_API = window.NOTIFY_API_BASE || 'http://127.0.0.1:8000/api/v1';
window.NOTIFY_API = NOTIFY_API;

let _cmsData = CMS_DEFAULTS;   // fallback until the API responds

window.CMS = {
  // Current content (defaults until load() resolves).
  data() { return _cmsData; },
  // Fetch the live content from the Laravel backend.
  async load() {
    try {
      const res = await fetch(NOTIFY_API + '/content', { headers: { Accept: 'application/json' } });
      if (res.ok) _cmsData = await res.json();
    } catch (e) {
      console.warn('NotifySMS: using offline defaults —', e.message);
    }
    return _cmsData;
  },
  apiBase: NOTIFY_API,
  defaults: CMS_DEFAULTS,
};

// ─── Auto-apply CMS data to frontend pages ───────────────────────
document.addEventListener('DOMContentLoaded', async () => {
  const d = await window.CMS.load();
  _applySettings(d.settings || {});
  _applyHero(d.hero || {});
  _applyClients(d.clients || []);
  _applySEO(d.seo || {});
  document.dispatchEvent(new CustomEvent('cms:ready', { detail: d }));
});

function _applySettings(s) {
  // Site name in navbar
  document.querySelectorAll('[data-cms="siteName"]').forEach(el => el.textContent = s.siteName);
  document.querySelectorAll('[data-cms="phone"]').forEach(el => el.textContent = s.phone);
  document.querySelectorAll('[data-cms="email"]').forEach(el => el.textContent = s.email);
  document.querySelectorAll('[data-cms="address"]').forEach(el => el.textContent = s.address);
  document.querySelectorAll('[data-cms="copyright"]').forEach(el => el.textContent = s.copyright);
  document.querySelectorAll('[data-cms="loginUrl"]').forEach(el => { el.href = s.loginUrl; });
  document.querySelectorAll('[data-cms="whatsapp"]').forEach(el => { el.href = 'https://wa.me/' + s.whatsapp.replace(/\D/g,''); el.textContent = s.whatsapp; });
  document.querySelectorAll('[data-cms="facebook"]').forEach(el => el.href = s.facebook);
  document.querySelectorAll('[data-cms="linkedin"]').forEach(el => el.href = s.linkedin);
  document.querySelectorAll('[data-cms="youtube"]').forEach(el => el.href = s.youtube);
}

function _applyHero(h) {
  const q = (id, fn) => { const el = document.getElementById(id); if(el) fn(el); };
  q('cms-hero-badge',   el => el.textContent = h.badge);
  q('cms-hero-line1',   el => el.textContent = h.line1);
  q('cms-hero-line2',   el => el.textContent = h.line2Gradient);
  q('cms-hero-line3',   el => el.textContent = h.line3);
  q('cms-hero-subtitle',el => el.textContent = h.subtitle);
  q('cms-hero-btn1',    el => { el.textContent = h.btn1Text; el.href = h.btn1Link; });
  q('cms-hero-btn2',    el => { el.textContent = h.btn2Text; el.href = h.btn2Link; });
  q('cms-stat1-num',    el => el.textContent = h.stat1Num);
  q('cms-stat1-label',  el => el.textContent = h.stat1Label);
  q('cms-stat2-num',    el => el.textContent = h.stat2Num);
  q('cms-stat2-label',  el => el.textContent = h.stat2Label);
  q('cms-stat3-num',    el => el.textContent = h.stat3Num);
  q('cms-stat3-label',  el => el.textContent = h.stat3Label);
}

function _applyClients(clients) {
  const track = document.getElementById('cms-marquee-track');
  if(!track || !clients.length) return;
  // Build 3 copies for seamless infinite scroll
  const html = [...clients, ...clients, ...clients].map(c => `
    <div class="logo-card">
      <div class="logo-icon" style="background:${c.bg};">
        <i class="fas ${c.icon} text-white"></i>
      </div>
      <span class="logo-name" style="color:${c.color};">${c.name}</span>
    </div>`).join('');
  track.innerHTML = html;
}

function _applySEO(seo) {
  const page = document.body.dataset.page;
  if(!page || !seo[page]) return;
  const s = seo[page];
  if(s.title) document.title = s.title;
  let desc = document.querySelector('meta[name="description"]');
  if(!desc) { desc = document.createElement('meta'); desc.name='description'; document.head.appendChild(desc); }
  desc.content = s.desc || '';
}

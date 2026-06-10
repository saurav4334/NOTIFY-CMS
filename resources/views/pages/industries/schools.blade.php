@extends('layouts.marketing')

@section('content')
@include('partials.breadcrumbs')

<section class="gradient-hero pt-6 pb-20 px-4">
  <div class="max-w-5xl mx-auto text-center">
    <span class="inline-flex items-center gap-2 bg-white border border-blue-100 shadow-sm text-ppnavy text-xs font-bold px-4 py-2 rounded-full mb-6"><i class="fas fa-graduation-cap" style="color:#009cde;"></i> BULK SMS FOR SCHOOLS & COACHING</span>
    <h1 class="text-4xl md:text-6xl font-black leading-tight mb-6" style="color:#003087;">Bulk SMS for <span class="gradient-text">Schools</span> & Coaching Centres</h1>
    <p class="text-lg md:text-xl text-slate-600 max-w-3xl mx-auto mb-9">Keep parents and students informed in real time. Send attendance alerts, exam results, fee reminders and emergency notices to thousands of guardians across Bangladesh — in Bangla or English — with a few clicks.</p>
    <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8">
      <a href="/contact" class="font-bold px-8 py-4 rounded-2xl text-base hover:opacity-90" style="background:#ffc439;color:#003087;box-shadow:0 8px 28px rgba(255,196,57,.4);"><i class="fas fa-school mr-2"></i>Connect Your Institution</a>
      <a href="/calculator" class="font-semibold px-8 py-4 rounded-2xl text-base border-2 hover:bg-blue-50" style="border-color:#003087;color:#003087;"><i class="fas fa-calculator mr-2"></i>Estimate Cost</a>
    </div>
  </div>
</section>

<section class="py-20 px-4 bg-white">
  <div class="max-w-6xl mx-auto">
    <div class="text-center mb-14"><h2 class="text-3xl md:text-4xl font-black mb-3" style="color:#003087;">One platform for every school notification</h2><p class="text-slate-500 max-w-2xl mx-auto">Strengthen the school-parent connection with instant, reliable SMS.</p></div>
    <div class="grid md:grid-cols-3 gap-6">
      @foreach ([
        ['fa-user-check','#003087','Attendance alerts','Notify parents the moment a student is absent — automatically from your attendance system.'],
        ['fa-award','#059669','Exam results','Publish marks and grades to guardians securely as soon as results are ready.'],
        ['fa-money-bill-wave','#f97316','Fee reminders','Reduce late payments with friendly, scheduled tuition-fee reminders.'],
        ['fa-bullhorn','#7c3aed','Notices & events','Announce holidays, PTMs, exam routines and events to everyone at once.'],
        ['fa-triangle-exclamation','#ef4444','Emergency alerts','Send urgent closure or safety notices to all parents in seconds.'],
        ['fa-language','#0ea5e9','Bangla & English','Compose messages in full Unicode Bangla or English for clear communication.'],
      ] as $f)
      <div class="feat-card bg-slate-50/70 border border-slate-100 rounded-2xl p-7">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-5" style="background:{{ $f[1] }};"><i class="fas {{ $f[0] }} text-white"></i></div>
        <h3 class="font-bold text-lg mb-2 text-slate-800">{{ $f[2] }}</h3>
        <p class="text-slate-600 text-sm leading-relaxed">{{ $f[3] }}</p>
      </div>
      @endforeach
    </div>
  </div>
</section>

<section class="py-16 px-4" style="background:#f5f8ff;">
  <div class="max-w-5xl mx-auto">
    <h2 class="text-2xl md:text-3xl font-black text-center mb-10" style="color:#003087;">Messages parents appreciate</h2>
    <div class="grid sm:grid-cols-2 gap-5">
      @foreach ([
        'Dear Parent, your child Tahsin was absent today (11 June). Please contact the office. — Sunrise School',
        'Result published: Tahsin scored 92% in the Mid-Term exam. Congratulations! Details at school portal.',
        'Reminder: June tuition fee (৳3,000) is due by 15 June. Please pay to avoid late charges.',
        'School will remain closed tomorrow due to weather. Stay safe. — Sunrise School',
      ] as $m)
      <div class="bg-white border border-slate-100 rounded-2xl p-5 flex gap-3"><i class="fas fa-comment-sms mt-1" style="color:#009cde;"></i><p class="text-slate-700 text-sm italic">{{ $m }}</p></div>
      @endforeach
    </div>
  </div>
</section>

<section class="py-16 px-4 bg-white"><div class="max-w-6xl mx-auto"><h2 class="text-2xl font-black mb-8 text-center" style="color:#003087;">Recommended solutions</h2>
  <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
    @foreach ([
      ['/bulk-sms','fa-paper-plane','Bulk SMS','Send to all parents at once.'],
      ['/masking-sms','fa-id-badge','Masking SMS','Show your school name as sender.'],
      ['/transactional-sms','fa-bell','Transactional SMS','Automated result & fee alerts.'],
      ['/sms-api','fa-code','SMS API','Integrate with your school software.'],
    ] as $r)
    <a href="{{ $r[0] }}" class="feat-card block bg-slate-50/70 border border-slate-100 rounded-2xl p-6 hover:border-blue-200"><i class="fas {{ $r[1] }} text-xl mb-3" style="color:#009cde;"></i><h3 class="font-bold text-slate-800 mb-1">{{ $r[2] }}</h3><p class="text-slate-500 text-sm">{{ $r[3] }}</p></a>
    @endforeach
  </div>
</div></section>

@include('partials.faq', ['faqs' => [
  ['q' => 'Can schools send SMS in Bangla?', 'a' => 'Yes. NotifySMS fully supports Unicode Bangla, so notices, results and reminders can be sent in clear Bangla or English.'],
  ['q' => 'Can I show my school name as the sender?', 'a' => 'Yes — with <a href="/masking-sms" style="color:#009cde;font-weight:600;">masking SMS</a> your institution’s name appears as the sender ID, which parents instantly recognise and trust.'],
  ['q' => 'Can SMS be sent automatically from our school management software?', 'a' => 'Yes. Connect your system to our <a href="/sms-api" style="color:#009cde;font-weight:600;">SMS API</a> to auto-send attendance, results and fee reminders.'],
  ['q' => 'How much does school SMS cost?', 'a' => 'Pricing starts from ৳0.26/SMS with slab discounts for volume. Use the <a href="/calculator" style="color:#009cde;font-weight:600;">calculator</a> to estimate your monthly cost.'],
]])

@include('partials.cta', ['ctaHeading' => 'Keep every parent in the loop', 'ctaSub' => 'Send attendance, results, fees and emergency alerts reliably. Set up bulk SMS for your school with NotifySMS.'])
@endsection

@extends('layouts.marketing')

@section('content')
@include('partials.breadcrumbs')

<section class="gradient-hero pt-6 pb-20 px-4">
  <div class="max-w-5xl mx-auto text-center">
    <span class="inline-flex items-center gap-2 bg-white border border-blue-100 shadow-sm text-ppnavy text-xs font-bold px-4 py-2 rounded-full mb-6"><i class="fas fa-house-medical" style="color:#009cde;"></i> BULK SMS FOR HOSPITALS & CLINICS</span>
    <h1 class="text-4xl md:text-6xl font-black leading-tight mb-6" style="color:#003087;">Bulk SMS for <span class="gradient-text">Hospitals</span> & Clinics</h1>
    <p class="text-lg md:text-xl text-slate-600 max-w-3xl mx-auto mb-9">Reduce no-shows and improve patient care with timely SMS. Send appointment reminders, report-ready alerts, OTP for patient portals and health campaigns — reliably delivered across Bangladesh, 24×7.</p>
    <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8">
      <a href="/contact" class="font-bold px-8 py-4 rounded-2xl text-base hover:opacity-90" style="background:#ffc439;color:#003087;box-shadow:0 8px 28px rgba(255,196,57,.4);"><i class="fas fa-stethoscope mr-2"></i>Talk to Us</a>
      <a href="/sms-api" class="font-semibold px-8 py-4 rounded-2xl text-base border-2 hover:bg-blue-50" style="border-color:#003087;color:#003087;"><i class="fas fa-plug mr-2"></i>Integrate HMS</a>
    </div>
  </div>
</section>

<section class="py-20 px-4 bg-white">
  <div class="max-w-6xl mx-auto">
    <div class="text-center mb-14"><h2 class="text-3xl md:text-4xl font-black mb-3" style="color:#003087;">SMS for better patient communication</h2><p class="text-slate-500 max-w-2xl mx-auto">Automate the reminders and alerts that keep patients on track.</p></div>
    <div class="grid md:grid-cols-3 gap-6">
      @foreach ([
        ['fa-calendar-check','#003087','Appointment reminders','Cut no-shows with automatic reminders the day before and hour before each visit.'],
        ['fa-file-medical','#059669','Report-ready alerts','Notify patients the moment lab reports or prescriptions are ready for collection.'],
        ['fa-key','#7c3aed','Patient portal OTP','Secure patient-portal and app logins with fast one-time-password SMS.'],
        ['fa-syringe','#0ea5e9','Health campaigns','Run vaccination drives, check-up reminders and awareness campaigns at scale.'],
        ['fa-user-doctor','#f97316','Doctor schedules','Inform patients about doctor availability, changes and queue position.'],
        ['fa-triangle-exclamation','#ef4444','Emergency notices','Send critical alerts to staff and patients instantly when every second counts.'],
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
    <h2 class="text-2xl md:text-3xl font-black text-center mb-10" style="color:#003087;">Sample patient messages</h2>
    <div class="grid sm:grid-cols-2 gap-5">
      @foreach ([
        'Reminder: your appointment with Dr. Karim is tomorrow at 11:00 AM. Reply C to confirm. — City Hospital',
        'Your lab report is ready. Please collect it from the reception or view online at portal.cityhospital.bd',
        'Your patient-portal verification code is 7391. It expires in 5 minutes.',
        'Free eye check-up camp this Friday 9 AM–1 PM. All are welcome. — City Hospital',
      ] as $m)
      <div class="bg-white border border-slate-100 rounded-2xl p-5 flex gap-3"><i class="fas fa-comment-sms mt-1" style="color:#009cde;"></i><p class="text-slate-700 text-sm italic">{{ $m }}</p></div>
      @endforeach
    </div>
  </div>
</section>

<section class="py-16 px-4 bg-white"><div class="max-w-6xl mx-auto"><h2 class="text-2xl font-black mb-8 text-center" style="color:#003087;">Recommended solutions</h2>
  <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
    @foreach ([
      ['/transactional-sms','fa-bell','Transactional SMS','Appointment & report alerts.'],
      ['/otp-sms','fa-key','OTP SMS','Secure patient-portal logins.'],
      ['/masking-sms','fa-id-badge','Masking SMS','Send from your hospital name.'],
      ['/sms-api','fa-code','SMS API','Connect your HMS/EMR system.'],
    ] as $r)
    <a href="{{ $r[0] }}" class="feat-card block bg-slate-50/70 border border-slate-100 rounded-2xl p-6 hover:border-blue-200"><i class="fas {{ $r[1] }} text-xl mb-3" style="color:#009cde;"></i><h3 class="font-bold text-slate-800 mb-1">{{ $r[2] }}</h3><p class="text-slate-500 text-sm">{{ $r[3] }}</p></a>
    @endforeach
  </div>
</div></section>

@include('partials.faq', ['faqs' => [
  ['q' => 'Can SMS reduce patient no-shows?', 'a' => 'Yes. Automated appointment reminders sent a day and an hour before the visit measurably reduce no-shows and keep schedules full.'],
  ['q' => 'Can I integrate SMS with our hospital management system?', 'a' => 'Yes. Connect your HMS or EMR to our <a href="/sms-api" style="color:#009cde;font-weight:600;">SMS API</a> to trigger appointment, report and OTP messages automatically.'],
  ['q' => 'Are OTP SMS fast enough for patient portals?', 'a' => 'Yes. Our <a href="/otp-sms" style="color:#009cde;font-weight:600;">OTP SMS</a> are delivered in under three seconds on priority routes, ideal for secure portal logins.'],
  ['q' => 'Can messages be sent from the hospital’s name?', 'a' => 'Yes, using <a href="/masking-sms" style="color:#009cde;font-weight:600;">masking SMS</a> your hospital or clinic name appears as the sender, which patients recognise and trust.'],
]])

@include('partials.cta', ['ctaHeading' => 'Improve patient care with timely SMS', 'ctaSub' => 'Send reminders, alerts and OTPs reliably 24×7. Set up hospital SMS with NotifySMS today.'])
@endsection

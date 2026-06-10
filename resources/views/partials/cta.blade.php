@php
    $ctaHeading = $ctaHeading ?? 'Ready to start sending SMS in minutes?';
    $ctaSub     = $ctaSub ?? 'Join 10,000+ Bangladeshi businesses delivering promotional, transactional and OTP SMS with NotifySMS. No setup fee, pay only for what you send.';
@endphp
<section class="py-20 px-4" style="background:linear-gradient(135deg,#003087 0%,#0072b5 100%)">
  <div class="max-w-4xl mx-auto text-center">
    <h2 class="text-3xl md:text-4xl font-black text-white mb-4">{{ $ctaHeading }}</h2>
    <p class="text-blue-100 text-lg mb-8 max-w-2xl mx-auto">{{ $ctaSub }}</p>
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
      <a href="/contact" class="font-black px-10 py-4 rounded-2xl hover:opacity-90 text-lg" style="background:#ffc439;color:#003087;">Start Free Today</a>
      <a href="/pricing" class="border-2 border-white/40 hover:border-white text-white font-black px-10 py-4 rounded-2xl text-lg hover:bg-white/10">View Pricing</a>
    </div>
    <p class="text-blue-200 text-sm mt-6"><i class="fas fa-shield-halved mr-1.5"></i>BTRC certified &nbsp;·&nbsp; <i class="fas fa-bolt mr-1.5"></i>&lt;3s delivery &nbsp;·&nbsp; <i class="fas fa-headset mr-1.5"></i>24×7 support</p>
  </div>
</section>

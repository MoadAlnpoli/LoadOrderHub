@extends('layouts.app')

@section('title', 'Terms of Service - LoadOrderHub')

@section('meta')
    <meta name="description" content="Read the Terms of Service for LoadOrderHub. Understand the rules, guidelines, and your responsibilities when using our modding community platform.">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ route('terms') }}">
    <meta property="og:title" content="Terms of Service - LoadOrderHub">
    <meta property="og:description" content="Read the Terms of Service for LoadOrderHub. Understand the rules, guidelines, and your responsibilities when using our modding community platform.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('terms') }}">
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-8 my-8">
    <div class="glass-card rounded-3xl p-8 md:p-12 border border-slate-800">
        <div class="flex items-center gap-4 mb-8 border-b border-slate-800 pb-6">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-blue-600 to-cyan-500 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-scale-balanced text-white text-xl"></i>
            </div>
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-white">Terms of Service</h1>
                <p class="text-slate-400 text-sm mt-1"><strong class="text-slate-300">Last Updated:</strong> {{ date('F d, Y') }}</p>
            </div>
        </div>

        <div class="space-y-8 text-slate-300 leading-relaxed text-sm">

            <div class="bg-blue-500/5 border border-blue-500/20 rounded-2xl p-5">
                <p class="text-slate-300">By accessing or using <strong class="text-white">LoadOrderHub</strong>, you confirm that you are at least 13 years of age, have read and understood these Terms of Service, and agree to be bound by them. If you do not agree with any part of these terms, please do not access our website.</p>
            </div>

            <section class="space-y-3">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <span class="text-blue-400 font-mono text-base">01.</span> Use of the Service
                </h2>
                <p>LoadOrderHub grants you a limited, non-exclusive, non-transferable, and revocable license to access and use the Site for your personal, non-commercial use only. You agree not to use the Site:</p>
                <ul class="list-disc list-inside space-y-2 ml-4 text-slate-400">
                    <li>For any unlawful purpose or in violation of any regulations.</li>
                    <li>To solicit others to perform or participate in any unlawful acts.</li>
                    <li>To violate any international, federal, provincial, or state regulations, rules, laws, or local ordinances.</li>
                    <li>To infringe upon or violate our intellectual property rights or the intellectual property rights of others.</li>
                    <li>To harass, abuse, insult, harm, defame, slander, disparage, intimidate, or discriminate.</li>
                    <li>To submit false or misleading information.</li>
                    <li>To upload or transmit viruses or any other type of malicious code.</li>
                    <li>To spam, phish, pharm, pretext, or scrape the Site.</li>
                </ul>
            </section>

            <section class="space-y-3">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <span class="text-blue-400 font-mono text-base">02.</span> User Accounts
                </h2>
                <p>When you create an account with us, you must provide accurate, complete, and current information. You are responsible for safeguarding the password that you use to access the Service and for any activities or actions under your password.</p>
                <p>You agree not to disclose your password to any third party. You must notify us immediately upon becoming aware of any breach of security or unauthorized use of your account.</p>
            </section>

            <section class="space-y-3">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <span class="text-blue-400 font-mono text-base">03.</span> User-Generated Content
                </h2>
                <p>Users may post, upload, or otherwise contribute content to the site (e.g., mod lists, load orders, comments, ratings, screenshots). By submitting User Content, you grant us a worldwide, non-exclusive, royalty-free license to use, reproduce, modify, adapt, publish, translate, distribute, and display such content.</p>
                <p>You represent and warrant that:</p>
                <ul class="list-disc list-inside space-y-1 ml-4 text-slate-400">
                    <li>You own or have the necessary rights to submit the content.</li>
                    <li>The content does not violate the privacy rights, publicity rights, copyrights, or other intellectual property rights of any person.</li>
                    <li>The content is not illegal, obscene, threatening, defamatory, or otherwise objectionable.</li>
                </ul>
                <p>We reserve the right to remove any User Content that violates these Terms at our sole discretion.</p>
            </section>

            <section class="space-y-3">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <span class="text-blue-400 font-mono text-base">04.</span> Third-Party Content & Mods
                </h2>
                <p>LoadOrderHub aggregates information about modifications (mods) created by third-party developers. <strong class="text-white">We do not host the mod files themselves</strong> unless explicitly stated. We are not responsible for the safety, functionality, or legality of third-party mods downloaded from external sites.</p>
                <p>Links to third-party websites (such as Nexus Mods, Mod DB, Google Drive, etc.) are provided for informational purposes only. We have no control over the content of those sites and accept no responsibility for them.</p>
            </section>

            <section class="space-y-3">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <span class="text-blue-400 font-mono text-base">05.</span> Intellectual Property
                </h2>
                <p>The Service and its original content (excluding User Content), features, and functionality are and will remain the exclusive property of LoadOrderHub and its licensors. Our trademarks and trade dress may not be used in connection with any product or service without the prior written consent of LoadOrderHub.</p>
                <p>All game names, logos, and brands mentioned on this site are property of their respective owners. Their use does not imply any affiliation with or endorsement of them.</p>
            </section>

            <section class="space-y-3">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <span class="text-blue-400 font-mono text-base">06.</span> Advertising
                </h2>
                <p>LoadOrderHub may display advertisements from third-party advertising partners, including Google AdSense. These advertisements help us maintain the free service we provide. By using the Site, you consent to the display of such advertisements.</p>
                <p>We are not responsible for the content of any third-party advertisements. If you have concerns about specific advertisement content, please contact the respective advertiser.</p>
            </section>

            <section class="space-y-3">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <span class="text-blue-400 font-mono text-base">07.</span> Disclaimer of Warranties
                </h2>
                <p>The Site is provided on an "AS IS" and "AS AVAILABLE" basis without any warranties of any kind, either express or implied, including but not limited to implied warranties of merchantability, fitness for a particular purpose, or non-infringement.</p>
                <p>We do not warrant that the Site will be uninterrupted, secure, or error-free, or that defects will be corrected.</p>
            </section>

            <section class="space-y-3">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <span class="text-blue-400 font-mono text-base">08.</span> Limitation of Liability
                </h2>
                <p>In no event shall LoadOrderHub, its directors, employees, partners, agents, suppliers, or affiliates, be liable for any indirect, incidental, special, consequential, or punitive damages, including without limitation, loss of profits, data, use, goodwill, or other intangible losses, resulting from your access to or use of (or inability to access or use) the Service.</p>
            </section>

            <section class="space-y-3">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <span class="text-blue-400 font-mono text-base">09.</span> Termination
                </h2>
                <p>We may terminate or suspend your account and access to the Service immediately, without prior notice or liability, for any reason, including without limitation if you breach these Terms of Service. Upon termination, your right to use the Service will cease immediately.</p>
            </section>

            <section class="space-y-3">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <span class="text-blue-400 font-mono text-base">10.</span> Governing Law
                </h2>
                <p>These Terms shall be governed and construed in accordance with applicable laws, without regard to its conflict of law provisions. Our failure to enforce any right or provision of these Terms will not be considered a waiver of those rights.</p>
            </section>

            <section class="space-y-3">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <span class="text-blue-400 font-mono text-base">11.</span> Changes to Terms
                </h2>
                <p>We reserve the right, at our sole discretion, to modify or replace these Terms at any time. We will provide notice of significant changes by updating the "Last Updated" date at the top of this page. By continuing to access or use our Service after those revisions become effective, you agree to be bound by the revised terms.</p>
            </section>

            <section class="space-y-3">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <span class="text-blue-400 font-mono text-base">12.</span> Contact Us
                </h2>
                <p>If you have any questions about these Terms of Service, please contact us via our <a href="{{ route('contact') }}" class="text-violet-400 hover:text-violet-300 underline">Contact Page</a>.</p>
            </section>

        </div>
    </div>
</div>
@endsection

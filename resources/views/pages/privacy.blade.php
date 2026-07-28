@extends('layouts.app')

@section('title', 'Privacy Policy - LoadOrderHub')

@section('meta')
    <meta name="description" content="Read the Privacy Policy of LoadOrderHub. Learn how we collect, use, and protect your personal information while using our modding platform.">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ route('privacy') }}">
    <meta property="og:title" content="Privacy Policy - LoadOrderHub">
    <meta property="og:description" content="Read the Privacy Policy of LoadOrderHub. Learn how we collect, use, and protect your personal information while using our modding platform.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('privacy') }}">
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-8 my-8">
    <div class="glass-card rounded-3xl p-8 md:p-12 border border-slate-800">
        <div class="flex items-center gap-4 mb-8 border-b border-slate-800 pb-6">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-violet-600 to-blue-500 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-shield-halved text-white text-xl"></i>
            </div>
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-white">Privacy Policy</h1>
                <p class="text-slate-400 text-sm mt-1"><strong class="text-slate-300">Effective Date:</strong> July 1, 2025 &nbsp;|&nbsp; <strong class="text-slate-300">Last Updated:</strong> {{ date('F d, Y') }}</p>
            </div>
        </div>

        <div class="space-y-8 text-slate-300 leading-relaxed text-sm">

            <section class="space-y-3">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <span class="text-violet-500 font-mono text-base">01.</span> Introduction
                </h2>
                <p>Welcome to <strong class="text-white">LoadOrderHub</strong> ("we", "our", "us"). We respect your privacy and are committed to protecting your personal data. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website at LoadOrderHub.com, including any other media form, media channel, mobile website, or mobile application related or connected to it.</p>
                <p>Please read this privacy policy carefully. If you do not agree with the terms of this privacy policy, please do not access the site.</p>
            </section>

            <section class="space-y-3">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <span class="text-violet-500 font-mono text-base">02.</span> Information We Collect
                </h2>
                <p>We may collect information about you in a variety of ways. The information we may collect on the Site includes:</p>
                <div class="space-y-3 ml-4">
                    <div class="bg-slate-950/50 rounded-xl p-4 border border-slate-800">
                        <h3 class="font-bold text-white mb-1">Personal Data</h3>
                        <p class="text-slate-400">Personally identifiable information, such as your name, username, email address, and password, that you voluntarily give to us when you register on the Site or when you choose to participate in various activities related to the Site, such as creating mod packs.</p>
                    </div>
                    <div class="bg-slate-950/50 rounded-xl p-4 border border-slate-800">
                        <h3 class="font-bold text-white mb-1">Derivative Data</h3>
                        <p class="text-slate-400">Information our servers automatically collect when you access the Site, such as your IP address, browser type, operating system, access times, and the pages you have viewed directly before and after accessing the Site.</p>
                    </div>
                    <div class="bg-slate-950/50 rounded-xl p-4 border border-slate-800">
                        <h3 class="font-bold text-white mb-1">User Content</h3>
                        <p class="text-slate-400">Mod lists, load orders, comments, ratings, and other content you voluntarily submit or share on the platform.</p>
                    </div>
                </div>
            </section>

            <section class="space-y-3">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <span class="text-violet-500 font-mono text-base">03.</span> How We Use Your Information
                </h2>
                <p>Having accurate information about you permits us to provide you with a smooth, efficient, and customized experience. Specifically, we may use your information to:</p>
                <ul class="list-disc list-inside space-y-2 ml-4 text-slate-400">
                    <li>Create and manage your user account.</li>
                    <li>Email you regarding your account or our services.</li>
                    <li>Enable user-to-user communications (comments, ratings).</li>
                    <li>Generate a personal profile about you to make future visits to the Site more personalized.</li>
                    <li>Monitor and analyze usage and trends to improve your experience with the Site.</li>
                    <li>Notify you of updates to the Site.</li>
                    <li>Prevent fraudulent transactions, monitor against theft, and protect against criminal activity.</li>
                    <li>Resolve disputes and troubleshoot problems.</li>
                    <li>Respond to product and customer service requests.</li>
                    <li>Deliver targeted advertising, newsletters, and other information regarding promotions to you.</li>
                </ul>
            </section>

            <section class="space-y-3">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <span class="text-violet-500 font-mono text-base">04.</span> Cookies and Tracking Technologies
                </h2>
                <p>We may use cookies, web beacons, tracking pixels, and other tracking technologies on the Site to help customize the Site and improve your experience. When you access the Site, your personal information is not collected through the use of tracking technology.</p>
                <p>Most browsers are set to accept cookies by default. You can remove or reject cookies, but be aware that such action could affect the availability and functionality of the Site.</p>
                <div class="bg-slate-950/50 rounded-xl p-4 border border-slate-800 space-y-2">
                    <h3 class="font-bold text-white">Types of Cookies We Use:</h3>
                    <ul class="list-disc list-inside space-y-1 text-slate-400 ml-2">
                        <li><strong class="text-slate-300">Essential Cookies:</strong> Required for the operation of our website (login sessions, CSRF protection).</li>
                        <li><strong class="text-slate-300">Analytics Cookies:</strong> Help us understand how visitors interact with our site (Google Analytics).</li>
                        <li><strong class="text-slate-300">Advertising Cookies:</strong> Used to deliver relevant advertisements and track ad performance (Google AdSense).</li>
                        <li><strong class="text-slate-300">Preference Cookies:</strong> Remember your language and theme settings.</li>
                    </ul>
                </div>
            </section>

            <section class="space-y-3">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <span class="text-violet-500 font-mono text-base">05.</span> Google AdSense & Third-Party Advertising
                </h2>
                <p>We use Google AdSense to serve ads on our website. Google AdSense uses cookies to serve ads based on your prior visits to our website or other websites. Google's use of advertising cookies enables it and its partners to serve ads to our users based on their visit to our sites and/or other sites on the Internet.</p>
                <p>You may opt out of personalized advertising by visiting <a href="https://www.google.com/settings/ads" target="_blank" rel="noopener noreferrer" class="text-violet-400 hover:text-violet-300 underline">Google Ads Settings</a>. Alternatively, you can opt out of a third-party vendor's use of cookies for personalized advertising by visiting <a href="https://www.aboutads.info" target="_blank" rel="noopener noreferrer" class="text-violet-400 hover:text-violet-300 underline">www.aboutads.info</a>.</p>
                <p>Google, as a third-party vendor, uses cookies to serve ads on our site. Google's use of the DART cookie enables it to serve ads to our users based on their visit to our sites and other sites on the Internet. Users may opt out of the use of the DART cookie by visiting the <a href="https://policies.google.com/technologies/ads" target="_blank" rel="noopener noreferrer" class="text-violet-400 hover:text-violet-300 underline">Google Ad and Content Network privacy policy</a>.</p>
            </section>

            <section class="space-y-3">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <span class="text-violet-500 font-mono text-base">06.</span> Third-Party Websites
                </h2>
                <p>The Site may contain links to third-party websites and applications of interest, including advertisements and external services, that are not affiliated with us. Once you have used these links to leave the Site, any information you provide to these third parties is not covered by this Privacy Policy, and we cannot guarantee the safety and privacy of your information.</p>
                <p>We encourage you to review the privacy policy of every site you visit before providing any information.</p>
            </section>

            <section class="space-y-3">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <span class="text-violet-500 font-mono text-base">07.</span> Security of Your Information
                </h2>
                <p>We use administrative, technical, and physical security measures to help protect your personal information. While we have taken reasonable steps to secure the personal information you provide to us, please be aware that despite our efforts, no security measures are perfect or impenetrable, and no method of data transmission can be guaranteed against any interception or other type of misuse.</p>
            </section>

            <section class="space-y-3">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <span class="text-violet-500 font-mono text-base">08.</span> Your Data Rights (GDPR)
                </h2>
                <p>If you are a resident of the European Economic Area (EEA), you have certain data protection rights. LoadOrderHub aims to take reasonable steps to allow you to correct, amend, delete, or limit the use of your Personal Data. You have the right to:</p>
                <ul class="list-disc list-inside space-y-2 ml-4 text-slate-400">
                    <li><strong class="text-slate-300">Access</strong> — Request copies of your personal data.</li>
                    <li><strong class="text-slate-300">Rectification</strong> — Request correction of inaccurate data.</li>
                    <li><strong class="text-slate-300">Erasure</strong> — Request deletion of your personal data ("right to be forgotten").</li>
                    <li><strong class="text-slate-300">Restriction</strong> — Request restriction of processing of your data.</li>
                    <li><strong class="text-slate-300">Portability</strong> — Request transfer of your data in a structured format.</li>
                    <li><strong class="text-slate-300">Objection</strong> — Object to processing of your personal data.</li>
                </ul>
                <p>To exercise these rights, please contact us through the <a href="{{ route('contact') }}" class="text-violet-400 hover:text-violet-300 underline">Contact page</a>.</p>
            </section>

            <section class="space-y-3">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <span class="text-violet-500 font-mono text-base">09.</span> Children's Privacy
                </h2>
                <p>Our Site is not directed to children under the age of 13, and we do not knowingly collect personal information from children under 13. If we discover that a child under 13 has provided us with personal information, we will promptly delete such information. If you believe we might have any information from or about a child under 13, please contact us.</p>
            </section>

            <section class="space-y-3">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <span class="text-violet-500 font-mono text-base">10.</span> Changes to This Policy
                </h2>
                <p>We may update this Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page and updating the "Last Updated" date at the top of this policy. You are advised to review this Privacy Policy periodically for any changes.</p>
            </section>

            <section class="space-y-3">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <span class="text-violet-500 font-mono text-base">11.</span> Contact Us
                </h2>
                <p>If you have questions or comments about this Privacy Policy, please contact us via our <a href="{{ route('contact') }}" class="text-violet-400 hover:text-violet-300 underline">Contact Page</a>.</p>
            </section>

        </div>
    </div>
</div>
@endsection

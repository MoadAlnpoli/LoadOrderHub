@extends('layouts.app')

@section('title', 'Privacy Policy - LoadOrderHub')

@section('meta')
    <meta name="description" content="Read the Privacy Policy of LoadOrderHub. Learn how we collect, use, and protect your personal information while using our modding platform.">
    <meta property="og:title" content="Privacy Policy - LoadOrderHub">
    <meta property="og:description" content="Read the Privacy Policy of LoadOrderHub. Learn how we collect, use, and protect your personal information while using our modding platform.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('privacy') }}">
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-8 my-8">
    <div class="glass-card rounded-3xl p-8 md:p-12 border border-slate-800">
        <h1 class="text-3xl md:text-4xl font-extrabold text-white mb-6 border-b border-slate-800 pb-4">Privacy Policy</h1>
        
        <div class="space-y-6 text-slate-300 leading-relaxed text-sm">
            <p><strong>Effective Date:</strong> {{ date('Y-m-d') }}</p>
            
            <section class="space-y-3">
                <h2 class="text-xl font-bold text-white">1. Introduction</h2>
                <p>Welcome to LoadOrderHub ("we", "our", "us"). We respect your privacy and are committed to protecting your personal data. This privacy policy will inform you about how we look after your personal data when you visit our website.</p>
            </section>

            <section class="space-y-3">
                <h2 class="text-xl font-bold text-white">2. Data We Collect</h2>
                <p>We may collect, use, store and transfer different kinds of personal data about you, including:</p>
                <ul class="list-disc list-inside space-y-1 ml-4 text-slate-400">
                    <li><strong>Identity Data:</strong> username, email address.</li>
                    <li><strong>Technical Data:</strong> internet protocol (IP) address, browser type and version, time zone setting and location.</li>
                    <li><strong>Usage Data:</strong> information about how you use our website, products and services.</li>
                </ul>
            </section>

            <section class="space-y-3">
                <h2 class="text-xl font-bold text-white">3. Third-Party Links & Advertising</h2>
                <p>This website may include links to third-party websites, plug-ins, and applications. Clicking on those links or enabling those connections may allow third parties to collect or share data about you. We do not control these third-party websites and are not responsible for their privacy statements.</p>
                <p>We use third-party advertising companies, such as Google AdSense, to serve ads when you visit our Website. These companies may use aggregated information (not including your name, address, email address, or telephone number) about your visits to this and other Web sites in order to provide advertisements about goods and services of interest to you.</p>
            </section>

            <section class="space-y-3">
                <h2 class="text-xl font-bold text-white">4. Cookies</h2>
                <p>You can set your browser to refuse all or some browser cookies, or to alert you when websites set or access cookies. If you disable or refuse cookies, please note that some parts of this website may become inaccessible or not function properly. We use cookies to personalize content and ads, to provide social media features and to analyze our traffic.</p>
            </section>

            <section class="space-y-3">
                <h2 class="text-xl font-bold text-white">5. Contact Us</h2>
                <p>If you have any questions about this privacy policy or our privacy practices, please contact us through our Contact page.</p>
            </section>
        </div>
    </div>
</div>
@endsection

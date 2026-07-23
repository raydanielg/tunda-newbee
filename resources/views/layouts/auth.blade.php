<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Tunda'))</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito:400,500,600,700,800,900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="referrer" content="strict-origin-when-cross-origin">

    <style>
        @keyframes simpleFadeIn { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:translateY(0); } }
        .ajax-loader { position:fixed; top:0; left:0; right:0; height:3px; background: linear-gradient(90deg, #991B1B, #F59E0B, #991B1B); background-size: 200% 100%; animation: ajaxProgress 1s linear infinite; z-index:9999; display:none; }
        @keyframes ajaxProgress { 0% { background-position: 100% 0; } 100% { background-position: -100% 0; } }
        .page-transition { animation: simpleFadeIn 0.35s ease-out both; }
        .btn-loading { position: relative; color: transparent !important; pointer-events: none; }
        .btn-loading::after { content: ''; position: absolute; top: 50%; left: 50%; width: 18px; height: 18px; margin: -9px 0 0 -9px; border: 2.5px solid rgba(255,255,255,0.4); border-top-color: #fff; border-radius: 50%; animation: btnSpin 0.6s linear infinite; }
        @keyframes btnSpin { to { transform: rotate(360deg); } }
        .swal2-popup { font-family: 'Nunito', sans-serif !important; border-radius: 12px !important; }
        .swal2-toast { font-family: 'Nunito', sans-serif !important; border-radius: 10px !important; box-shadow: 0 4px 24px rgba(0,0,0,0.12) !important; }
        .swal2-icon { border-radius: 50% !important; }
        .swal2-title { font-size: 14px !important; font-weight: 700 !important; padding: 0 !important; }
        .swal2-html-container { font-size: 12px !important; margin: 0 !important; }
        .swal2-confirm { border-radius: 8px !important; font-weight: 700 !important; font-size: 12px !important; padding: 6px 16px !important; }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        maroon: { 50:'#FBEDEF',100:'#F5D0D6',200:'#EBA1AD',300:'#C55B6E',400:'#991B1B',500:'#7F1D1D',600:'#6B1717',700:'#5A1212',800:'#450C0C',900:'#2E0808' },
                        amber: { 50:'#FFFBEB',100:'#FEF3C7',200:'#FDE68A',300:'#FCD34D',400:'#FBBF24',500:'#F59E0B',600:'#D97706',700:'#B45309',800:'#92400E',900:'#78350F' },
                        success: { 50:'#E8F5EE',100:'#C5E5D2',200:'#8FCCA8',300:'#5AB37E',400:'#2E9A5C',500:'#1E7A46',600:'#186238',700:'#124A2A',800:'#0C321C',900:'#061A0E' },
                        warning: { 50:'#FBF3E0',100:'#F5E2B8',200:'#EBCB7A',300:'#E0B43C',400:'#D69A1E',500:'#B98207',600:'#966A05',700:'#735204',800:'#503A02',900:'#2D2201' },
                        danger: { 50:'#FCEAEA',100:'#F8C5C5',200:'#F08A8A',300:'#E85050',400:'#D63A3A',500:'#C22F2F',600:'#A22525',700:'#821B1B',800:'#621111',900:'#420707' },
                        info: { 50:'#E8F2FB',100:'#C5E0F5',200:'#8FC2E9',300:'#5AA4DD',400:'#2E87CB',500:'#1D6FA5',600:'#185A87',700:'#134569',800:'#0E304B',900:'#091B2D' }
                    }
                }
            }
        }
    </script>
</head>
<body class="font-['Nunito',sans-serif] antialiased text-[#1C1B1B] min-h-screen bg-[#F7F4F1]">

    {{-- Auth Background --}}
    <div class="fixed inset-0 z-0">
        <div class="absolute inset-0 bg-gradient-to-br from-maroon-500 via-maroon-600 to-maroon-700"></div>
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(rgba(255,255,255,0.15) 1px, transparent 1px); background-size: 24px 24px;"></div>
        <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 20% 50%, rgba(153,27,27,0.3) 0%, transparent 50%), radial-gradient(circle at 80% 80%, rgba(245,158,11,0.2) 0%, transparent 50%);"></div>
    </div>

    {{-- AJAX Progress Bar --}}
    <div id="ajaxLoader" class="ajax-loader"></div>

    <main id="authMain" class="relative z-10 min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        @yield('content')
    </main>

    {{-- SweetAlert2 Alert System --}}
    <script>
    (function() {
        function showAlert(type, title, message) {
            const Swal = window.Swal || window.Sweetalert2;
            if (!Swal) return;
            const colors = {
                success: '#991B1B',
                error: '#C22F2F',
                warning: '#B98207',
                info: '#1D6FA5'
            };
            const SwalMixin = Swal.mixin ? Swal.mixin({
                toast: true,
                position: 'top',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                customClass: { popup: 'swal2-toast' },
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            }) : null;
            if (SwalMixin) {
                SwalMixin.fire({
                    icon: type,
                    title: title + (message ? ': ' + message : ''),
                    iconColor: colors[type] || '#0D3E63'
                });
            } else {
                Swal.fire({
                    icon: type,
                    title: title,
                    text: message || '',
                    confirmButtonColor: colors[type] || '#0D3E63',
                    confirmButtonText: 'OK'
                });
            }
        }
        window.showToast = showAlert;
        window.showAlert = showAlert;

        @if(session('status'))
            showAlert('success', 'Success!', '{{ session('status') }}');
        @endif
        @if(session('error'))
            showAlert('error', 'Oops...', '{{ session('error') }}');
        @endif
        @if(session('warning'))
            showAlert('warning', 'Warning', '{{ session('warning') }}');
        @endif
        @if(session('info'))
            showAlert('info', 'Info', '{{ session('info') }}');
        @endif

        @if($errors->any())
            @php $allErrors = $errors->all(); @endphp
            showAlert('error', 'Validation Error', '{{ implode("\n", $allErrors) }}');
        @endif
    })();

    // AJAX Navigation System for Auth Pages
    (function() {
        const authMain = document.getElementById('authMain');
        const ajaxLoader = document.getElementById('ajaxLoader');

        function showLoader() { if(ajaxLoader) ajaxLoader.style.display = 'block'; }
        function hideLoader() { if(ajaxLoader) ajaxLoader.style.display = 'none'; }

        function loadPage(url, pushState = true) {
            showLoader();
            fetch(url, {
                credentials: 'same-origin',
                headers: { 'Accept': 'text/html' }
            })
            .then(r => {
                if (!r.ok) throw new Error('HTTP ' + r.status);
                return r.text();
            })
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newContent = doc.querySelector('main');
                if (newContent && authMain) {
                    authMain.innerHTML = newContent.innerHTML;
                    authMain.classList.remove('page-transition');
                    void authMain.offsetWidth;
                    authMain.classList.add('page-transition');
                    document.title = doc.title;
                    rebindAjaxLinks();
                    rebindForms();
                    if (pushState) history.pushState({ url: url }, '', url);
                } else {
                    window.location.href = url;
                    return;
                }
                hideLoader();
            })
            .catch(err => {
                hideLoader();
                window.location.href = url;
            });
        }

        function rebindAjaxLinks() {
            document.querySelectorAll('a[href]').forEach(function(link) {
                const href = link.getAttribute('href');
                if (!href || href.startsWith('#') || href.startsWith('javascript') || href.startsWith('mailto') || href.startsWith('tel') || link.target === '_blank') return;
                const url = new URL(href, location.href);
                if (url.host !== location.host) return;
                if (!url.pathname.match(/\/(login|register|password|verify)/)) return;
                link.removeEventListener('click', handleAjaxClick);
                link.addEventListener('click', handleAjaxClick);
            });
        }

        function handleAjaxClick(e) {
            e.preventDefault();
            loadPage(this.getAttribute('href'));
        }

        function rebindForms() {
            document.querySelectorAll('form[method="POST"]').forEach(function(form) {
                form.removeEventListener('submit', handleAjaxSubmit);
                form.addEventListener('submit', handleAjaxSubmit);
            });
        }

        function handleAjaxSubmit(e) {
            e.preventDefault();
            const form = e.target;
            const btn = form.querySelector('button[type="submit"]');

            if (btn) {
                btn.disabled = true;
                btn.classList.add('btn-loading');
            }
            showLoader();

            const formData = new FormData(form);
            let actionUrl = form.action;

            fetch(actionUrl, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                credentials: 'same-origin',
                redirect: 'follow'
            })
            .then(r => {
                if (r.redirected) {
                    window.location.href = r.url;
                    return null;
                }
                if (r.type === 'opaqueredirect' || r.status === 0) {
                    window.location.reload();
                    return null;
                }
                const contentType = r.headers.get('content-type') || '';
                if (r.status === 204) {
                    return { json: {}, status: 204, type: 'json' };
                }
                if (contentType.includes('application/json')) {
                    return r.json().then(data => ({ json: data, status: r.status, type: 'json' }));
                }
                return r.text().then(html => ({ html, status: r.status, type: 'html' }));
            })
            .then(result => {
                if (result === null) return;
                hideLoader();
                if (btn) { btn.disabled = false; btn.classList.remove('btn-loading'); }

                if (result.type === 'json') {
                    if (result.status === 204) {
                        window.location.href = '/home';
                        return;
                    }
                    if (result.status === 200) {
                        if (result.json.redirect) {
                            window.location.href = result.json.redirect;
                            return;
                        }
                        if (result.json.message || result.json.status) {
                            const msg = result.json.message || result.json.status;
                            window.showAlert && window.showAlert('success', 'Success!', msg);
                            const actionUrl = form.getAttribute('action') || '';
                            if (actionUrl.includes('/password/reset') || actionUrl.includes('password.update')) {
                                setTimeout(function() { window.location.href = '/home'; }, 2000);
                            }
                            return;
                        }
                        window.location.href = '/home';
                        return;
                    }
                    if (result.status === 422) {
                        const messages = [];
                        if (result.json.errors) {
                            Object.values(result.json.errors).forEach(function(errs) {
                                if (Array.isArray(errs)) {
                                    errs.forEach(function(msg) { messages.push(msg); });
                                } else {
                                    messages.push(errs);
                                }
                            });
                        } else if (result.json.message) {
                            messages.push(result.json.message);
                        }
                        if (messages.length > 0) {
                            window.showAlert && window.showAlert('error', 'Error', messages.join('. '));
                        }
                        return;
                    }
                    if (result.json.redirect) {
                        window.location.href = result.json.redirect;
                        return;
                    }
                    if (result.json.message) {
                        const alertType = result.status >= 400 ? 'error' : 'success';
                        window.showAlert && window.showAlert(alertType, result.status >= 400 ? 'Error' : 'Success', result.json.message);
                    }
                    return;
                }

                if (result.html && (result.html.trim().startsWith('<!DOCTYPE') || result.html.trim().startsWith('<!doctype'))) {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(result.html, 'text/html');
                    const newContent = doc.querySelector('main');
                    if (newContent && authMain) {
                        authMain.innerHTML = newContent.innerHTML;
                        authMain.classList.remove('page-transition');
                        void authMain.offsetWidth;
                        authMain.classList.add('page-transition');
                        document.title = doc.title;
                        rebindAjaxLinks();
                        rebindForms();
                        if (doc.querySelector('meta[name="csrf-token"]')) {
                            const token = doc.querySelector('meta[name="csrf-token"]').content;
                            document.querySelector('meta[name="csrf-token"]').content = token;
                        }
                        const errEls = doc.querySelectorAll('.text-red-600');
                        if (errEls.length > 0) {
                            const msgs = [];
                            errEls.forEach(function(el) { const t = el.textContent.trim(); if (t) msgs.push(t); });
                            if (msgs.length > 0) window.showAlert && window.showAlert('error', 'Error', msgs.join('. '));
                        }
                    } else {
                        window.location.reload();
                    }
                } else {
                    window.location.reload();
                }
            })
            .catch(function(err) {
                hideLoader();
                if (btn) { btn.disabled = false; btn.classList.remove('btn-loading'); }
                window.showAlert && window.showAlert('error', 'Error', 'Something went wrong. Please try again.');
            });
        }

        window.addEventListener('popstate', function(e) {
            if (e.state && e.state.url) loadPage(e.state.url, false);
        });

        rebindAjaxLinks();
        rebindForms();
    })();
    </script>

</body>
</html>

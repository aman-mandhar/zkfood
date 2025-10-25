@extends('layouts.dashboard.promotor.layout')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h1 class="text-center">Dashboard</h1>
                <p class="text-center">Welcome to the admin dashboard!</p>
            </div>
        </div>
    </div>
    <div class="card p-3 shadow-sm mt-4">
        <h5 class="mb-2">Invite your friends 🎉</h5>
        <p class="small text-muted">Share this link to invite others. They'll join under you automatically.</p>
        
        <div class="input-group">
            <input type="text" id="refLink" class="form-control" readonly 
                value="{{ url('/register') . '?ref=' . auth()->user()->ref_code }}">
            <button class="btn btn-outline-secondary" type="button" onclick="copyReferralLink()">
                Copy
            </button>
        </div>
    </div>
    <div class="col-lg-9 col-md-8 mb-4">
        {{-- DIRECTORY CTAs – Polished --}}
                <style>
                    .action-list .list-group-item{
                        border:1px solid #eee; border-left-width:8px; border-left-style:solid;
                        border-radius:12px; padding:1rem 1rem; display:flex; align-items:center;
                        gap:.9rem; transition:transform .15s ease, box-shadow .15s ease, border-color .15s ease;
                        background:#fff;
                    }
                    .action-list .list-group-item:hover{
                        transform:translateY(-2px);
                        box-shadow:0 10px 22px rgba(0,0,0,.06);
                        text-decoration:none;
                    }
                    .action-list .icon-pill{
                        width:42px; height:42px; border-radius:50%; display:grid; place-items:center;
                        font-size:1.15rem; background:#f4f5f7;
                    }
                    .action-list .label{
                        font-weight:700; color:#111; line-height:1.25;
                    }
                    .action-list .chev{ margin-left:auto; font-weight:700; opacity:.35; transition:transform .2s ease, opacity .2s ease; }
                    .action-list .list-group-item:hover .chev{ transform:translateX(2px); opacity:.6; }

                    /* Accent colors per item (change if you like) */
                    .action-list .list-group-item:nth-child(1){ border-left-color:#0d6efd; } /* blue */
                    .action-list .list-group-item:nth-child(2){ border-left-color:#20c997; } /* teal */
                    .action-list .list-group-item:nth-child(3){ border-left-color:#fd7e14; } /* orange */
                    .action-list .list-group-item:nth-child(4){ border-left-color:#d63384; } /* pink */

                    /* Compact on xs */
                    @media (max-width:576px){
                        .action-list .list-group-item{ padding:.85rem .9rem; }
                        .action-list .icon-pill{ width:38px; height:38px; font-size:1.05rem; }
                    }
                </style>

                <nav aria-label="Quick directories">
                    <div class="list-group action-list">
                        <a href="{{ route('sikh.directories.create') }}" class="list-group-item list-group-item-action">
                        <span class="icon-pill">🛕</span>
                        <span class="label">ਪੰਥਕ ਡਾਇਰੈਕਟਰੀ ਵਿਚ ਆਪਣਾ ਨਾਮ ਦਰਜ਼ ਕਰਵਾਓ।</span>
                        <span class="chev">→</span>
                        </a>
                        <a href="{{ route('job.directories.create') }}" class="list-group-item list-group-item-action">
                        <span class="icon-pill">💼</span>
                        <span class="label">ਨੌਕਰੀ ਲੈਣ ਵਾਸਤੇ ਆਪਣਾ ਨਾਮ ਦਰਜ ਕਰਵਾਓ।</span>
                        <span class="chev">→</span>
                        </a>
                        <a href="{{ route('business.directories.create') }}" class="list-group-item list-group-item-action">
                        <span class="icon-pill">🏪</span>
                        <span class="label">ਆਪਣੇ ਕਾਰੋਬਾਰ ਦੀ ਜਾਣਕਾਰੀ ਦਰਜ ਕਰਵਾਓ ਅਤੇ ਡਿਜਿਟਲ ਵਿਸੀਟਿੰਗ ਕਾਰਡ ਬਣਵਾਓ।</span>
                        <span class="chev">→</span>
                        </a>
                        <a href="{{ route('matrimonial.directories.create') }}" class="list-group-item list-group-item-action">
                        <span class="icon-pill">💍</span>
                        <span class="label">ਕੇਵਲ ਆਨੰਦ ਕਾਰਜ਼ ਕਰਵਾਓਣ ਵਾਸਤੇ ਆਪਣਾ ਨਾਮ ਦਰਜ਼ ਕਰਵਾਓ।</span>
                        <span class="chev">→</span>
                        </a>
                    </div>
                </nav>
        @livewire('sikh')
        @livewire('business-page')
    </div>

<script>
    function copyReferralLink() {
        const copyText = document.getElementById("refLink");
        copyText.select();
        copyText.setSelectionRange(0, 99999); // for mobile
        document.execCommand("copy");
        alert("Referral link copied!");
    }
</script>
@endsection
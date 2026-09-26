<x-filament-panels::page>
    <style>
        .soltech-page {
            --st-blue: #2563eb;
            --st-indigo: #4f46e5;
            --st-green: #10b981;
            --st-text: #111827;
            --st-muted: #6b7280;
            --st-border: #e5e7eb;
            width: 100%;
        }

        .dark .soltech-page {
            --st-text: #f9fafb;
            --st-muted: #9ca3af;
            --st-border: rgba(255,255,255,.10);
        }

        .soltech-page *,
        .soltech-page *::before,
        .soltech-page *::after {
            box-sizing: border-box;
        }

        .st-container {
            width: 100%;
            max-width: 1440px;
            margin: 0 auto;
            padding: 8px 8px 32px;
        }

        .st-hero {
            position: relative;
            overflow: hidden;
            border-radius: 24px;
            padding: 42px 46px;
            color: white;
            background:
                radial-gradient(circle at 85% 15%, rgba(59,130,246,.32), transparent 28%),
                radial-gradient(circle at 15% 90%, rgba(16,185,129,.16), transparent 28%),
                linear-gradient(135deg, #07111f 0%, #101827 52%, #172554 100%);
            box-shadow: 0 20px 50px rgba(0,0,0,.16);
        }

        .st-hero-grid {
            position: relative;
            z-index: 2;
            display: grid;
            grid-template-columns: minmax(0, 1fr) 250px;
            gap: 42px;
            align-items: center;
        }

        .st-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            padding: 8px 13px;
            border: 1px solid rgba(255,255,255,.13);
            border-radius: 999px;
            background: rgba(255,255,255,.08);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .02em;
        }

        .st-dot {
            width: 8px;
            height: 8px;
            flex: 0 0 8px;
            border-radius: 50%;
            background: #34d399;
            box-shadow: 0 0 0 5px rgba(52,211,153,.10);
        }

        .st-hero h1 {
            max-width: 850px;
            margin: 20px 0 0;
            color: #fff;
            font-size: clamp(30px, 4vw, 52px);
            line-height: 1.06;
            font-weight: 900;
            letter-spacing: -.035em;
        }

        .st-gradient-text {
            color: #93c5fd;
        }

        .st-hero-desc {
            max-width: 760px;
            margin: 18px 0 0;
            color: #cbd5e1;
            font-size: 16px;
            line-height: 1.75;
        }

        .st-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 11px;
            margin-top: 28px;
        }

        .st-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            min-height: 46px;
            padding: 0 17px;
            border-radius: 12px;
            text-decoration: none !important;
            font-size: 14px;
            font-weight: 800;
            transition: .2s ease;
        }

        .st-btn:hover {
            transform: translateY(-2px);
        }

        .st-btn-wa {
            color: #fff !important;
            background: #10b981;
            box-shadow: 0 9px 24px rgba(16,185,129,.22);
        }

        .st-btn-wa:hover {
            background: #059669;
        }

        .st-btn-light {
            color: #fff !important;
            border: 1px solid rgba(255,255,255,.15);
            background: rgba(255,255,255,.08);
        }

        .st-btn-light:hover {
            background: rgba(255,255,255,.14);
        }

        .st-logo-box {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 230px;
            height: 230px;
            margin: 0 auto;
            padding: 22px;
            border-radius: 28px;
            background: rgba(255,255,255,.98);
            box-shadow: 0 25px 60px rgba(0,0,0,.28);
        }

        .st-logo {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .st-section-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 340px;
            gap: 24px;
            margin-top: 24px;
            align-items: start;
        }

        .st-main {
            min-width: 0;
            display: grid;
            gap: 24px;
        }

        .st-card {
            border: 1px solid var(--st-border);
            border-radius: 22px;
            background: #fff;
            overflow: hidden;
        }

        .dark .st-card {
            background: #111827;
        }

        .st-card-pad {
            padding: 30px;
        }

        .st-section-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 20px;
        }

        .st-kicker {
            display: inline-flex;
            padding: 6px 10px;
            border-radius: 8px;
            background: #eff6ff;
            color: #2563eb;
            font-size: 10px;
            font-weight: 900;
            letter-spacing: .08em;
        }

        .dark .st-kicker {
            background: rgba(37,99,235,.13);
            color: #93c5fd;
        }

        .st-card h2 {
            margin: 12px 0 0;
            color: var(--st-text);
            font-size: 25px;
            line-height: 1.2;
            font-weight: 900;
            letter-spacing: -.02em;
        }

        .st-subtitle {
            max-width: 720px;
            margin: 9px 0 0;
            color: var(--st-muted);
            font-size: 14px;
            line-height: 1.7;
        }

        .st-tool-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 52px;
            height: 52px;
            flex: 0 0 52px;
            border-radius: 15px;
            background: #eff6ff;
            color: #2563eb;
            font-size: 25px;
            font-weight: 800;
        }

        .dark .st-tool-icon {
            background: rgba(37,99,235,.13);
        }

        .st-feature-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 15px;
            margin-top: 25px;
        }

        .st-feature {
            display: flex;
            gap: 15px;
            padding: 20px;
            border: 1px solid var(--st-border);
            border-radius: 17px;
            background: rgba(249,250,251,.75);
            transition: .2s ease;
        }

        .dark .st-feature {
            background: rgba(255,255,255,.025);
        }

        .st-feature:hover {
            transform: translateY(-2px);
            border-color: rgba(37,99,235,.35);
            box-shadow: 0 12px 30px rgba(0,0,0,.06);
        }

        .st-feature-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            flex: 0 0 44px;
            border-radius: 13px;
            background: #eff6ff;
            color: #2563eb;
            font-size: 20px;
            font-weight: 900;
        }

        .st-feature h3 {
            margin: 1px 0 5px;
            color: var(--st-text);
            font-size: 15px;
            font-weight: 850;
        }

        .st-feature p {
            margin: 0;
            color: var(--st-muted);
            font-size: 13px;
            line-height: 1.65;
        }

        .st-cta {
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
            padding: 30px;
            border-radius: 22px;
            color: #fff;
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            box-shadow: 0 18px 40px rgba(37,99,235,.18);
        }

        .st-cta h2 {
            margin: 0;
            color: #fff;
            font-size: 23px;
        }

        .st-cta p {
            max-width: 760px;
            margin: 8px 0 0;
            color: #dbeafe;
            font-size: 13px;
            line-height: 1.7;
        }

        .st-cta .st-btn {
            flex: 0 0 auto;
            color: #1d4ed8 !important;
            background: #fff;
        }

        .st-contact {
            position: sticky;
            top: 20px;
        }

        .st-contact-head {
            padding: 25px;
            color: #fff;
            background: linear-gradient(135deg, #10b981, #0f766e);
        }

        .st-contact-person {
            display: flex;
            align-items: center;
            gap: 13px;
        }

        .st-avatar {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            border-radius: 15px;
            background: rgba(255,255,255,.15);
            font-size: 24px;
        }

        .st-contact-label {
            margin: 0 0 3px;
            color: #d1fae5;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .08em;
        }

        .st-contact-name {
            margin: 0;
            color: #fff;
            font-size: 17px;
            font-weight: 900;
        }

        .st-contact-body {
            padding: 20px;
        }

        .st-info {
            padding: 16px;
            border: 1px solid var(--st-border);
            border-radius: 15px;
            background: rgba(249,250,251,.7);
        }

        .dark .st-info {
            background: rgba(255,255,255,.025);
        }

        .st-info + .st-info {
            margin-top: 12px;
        }

        .st-info-label {
            margin: 0 0 5px;
            color: #9ca3af;
            font-size: 10px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: .08em;
        }

        .st-info-value {
            margin: 0;
            color: var(--st-text);
            font-size: 14px;
            line-height: 1.65;
            font-weight: 700;
        }

        .st-contact-btn {
            width: 100%;
            margin-top: 14px;
        }

        .st-note {
            margin-top: 16px;
            padding-top: 15px;
            border-top: 1px solid var(--st-border);
            color: #9ca3af;
            font-size: 11px;
            line-height: 1.65;
            text-align: center;
        }

        .st-footer {
            padding: 2px 0 0;
            color: #9ca3af;
            font-size: 11px;
            text-align: center;
        }

        @media (max-width: 1100px) {
            .st-section-grid {
                grid-template-columns: minmax(0, 1fr) 300px;
            }

            .st-hero {
                padding: 34px;
            }

            .st-hero-grid {
                grid-template-columns: minmax(0, 1fr) 200px;
            }

            .st-logo-box {
                width: 190px;
                height: 190px;
            }
        }

        @media (max-width: 900px) {
            .st-section-grid {
                grid-template-columns: 1fr;
            }

            .st-contact {
                position: static;
            }

            .st-hero-grid {
                grid-template-columns: 1fr;
            }

            .st-logo-box {
                width: 150px;
                height: 150px;
                margin: 0;
            }
        }

        @media (max-width: 640px) {
            .st-container {
                padding: 2px 0 24px;
            }

            .st-hero {
                border-radius: 18px;
                padding: 25px 20px;
            }

            .st-hero h1 {
                font-size: 32px;
            }

            .st-hero-desc {
                font-size: 14px;
            }

            .st-logo-box {
                width: 125px;
                height: 125px;
                border-radius: 18px;
                padding: 14px;
            }

            .st-card-pad,
            .st-cta,
            .st-contact-head {
                padding: 22px;
            }

            .st-feature-grid {
                grid-template-columns: 1fr;
            }

            .st-section-head {
                align-items: center;
            }

            .st-tool-icon {
                display: none;
            }

            .st-cta {
                align-items: flex-start;
                flex-direction: column;
            }

            .st-cta .st-btn {
                width: 100%;
            }
        }
    </style>

    <div class="soltech-page">
        <div class="st-container">

            {{-- HERO --}}
            <section class="st-hero">
                <div class="st-hero-grid">

                    <div>
                        <div class="st-eyebrow">
                            <span class="st-dot"></span>
                            Terima kasih sudah menggunakan aplikasi kami
                        </div>

                        <h1>
                            Terima Kasih Telah Memilih
                            <span class="st-gradient-text">Aplikasi Kami</span> 🎉
                        </h1>

                        <p class="st-hero-desc">
                            Kami sangat menghargai kepercayaan Anda.
                            Semoga aplikasi ini membantu pekerjaan menjadi lebih
                            cepat, mudah, rapi, dan efisien.
                        </p>

                        <div class="st-actions">
                            <a
                                class="st-btn st-btn-wa"
                                href="https://wa.me/6285178113101?text=Halo%20Soltech%20Wonosobo%2C%20saya%20ingin%20bertanya%20tentang%20aplikasi"
                                target="_blank"
                                rel="noopener noreferrer"
                            >
                                <span>💬</span>
                                Hubungi via WhatsApp
                            </a>

                            <a class="st-btn st-btn-light" href="#promo">
                                <span>✨</span>
                                Lihat Layanan
                            </a>
                        </div>
                    </div>

                    <div class="st-logo-box">
                        <img
                            class="st-logo"
                            src="{{ asset('images/logo-soltech.jpg') }}"
                            alt="Soltech Wonosobo"
                        >
                    </div>

                </div>
            </section>

            {{-- MAIN --}}
            <div class="st-section-grid">

                <main class="st-main">

                    {{-- PROMO --}}
                    <section id="promo" class="st-card">
                        <div class="st-card-pad">

                            <div class="st-section-head">
                                <div>
                                    <span class="st-kicker">LAYANAN PENGEMBANGAN</span>

                                    <h2>Ingin Aplikasi Lebih Lengkap?</h2>

                                    <p class="st-subtitle">
                                        Kami siap membantu mengembangkan aplikasi sesuai
                                        kebutuhan bisnis, sekolah, toko, organisasi, maupun
                                        kebutuhan pribadi Anda.
                                    </p>
                                </div>

                                <div class="st-tool-icon">⚙</div>
                            </div>

                            <div class="st-feature-grid">

                                <article class="st-feature">
                                    <div class="st-feature-icon">＋</div>
                                    <div>
                                        <h3>Tambah Fitur</h3>
                                        <p>
                                            Tambahkan modul atau fitur baru sesuai kebutuhan aplikasi.
                                        </p>
                                    </div>
                                </article>

                                <article class="st-feature">
                                    <div class="st-feature-icon">🎨</div>
                                    <div>
                                        <h3>Custom Tampilan</h3>
                                        <p>
                                            Sesuaikan warna, logo, layout, dan identitas brand.
                                        </p>
                                    </div>
                                </article>

                                <article class="st-feature">
                                    <div class="st-feature-icon">▥</div>
                                    <div>
                                        <h3>Dashboard & Laporan</h3>
                                        <p>
                                            Tambahkan grafik, statistik, laporan, dan monitoring data.
                                        </p>
                                    </div>
                                </article>

                                <article class="st-feature">
                                    <div class="st-feature-icon">✓</div>
                                    <div>
                                        <h3>Maintenance</h3>
                                        <p>
                                            Bantuan perbaikan, update, backup, dan pengembangan lanjutan.
                                        </p>
                                    </div>
                                </article>

                            </div>
                        </div>
                    </section>

                    {{-- CTA --}}
                    <section class="st-cta">

                        <div>
                            <h2>Ada Pertanyaan atau Ingin Upgrade?</h2>

                            <p>
                                Silakan hubungi kami melalui WhatsApp untuk konsultasi,
                                bantuan penggunaan, perbaikan, atau penambahan fitur aplikasi.
                            </p>
                        </div>

                        <a
                            class="st-btn"
                            href="https://wa.me/6285178113101?text=Halo%20Soltech%20Wonosobo%2C%20saya%20ingin%20konsultasi%20tentang%20aplikasi"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            💬 Chat WhatsApp
                        </a>

                    </section>

                </main>

                {{-- CONTACT SIDEBAR --}}
                <aside class="st-contact">

                    <section class="st-card">

                        <div class="st-contact-head">
                            <div class="st-contact-person">

                                <div class="st-avatar">👤</div>

                                <div>
                                    <p class="st-contact-label">Kontak</p>
                                    <h3 class="st-contact-name">Soltech Wonosobo</h3>
                                </div>

                            </div>
                        </div>

                        <div class="st-contact-body">

                            <div class="st-info">
                                <p class="st-info-label">WhatsApp</p>
                                <p class="st-info-value">0851-7811-3101</p>
                            </div>

                            <div class="st-info">
                                <p class="st-info-label">Layanan</p>
                                <p class="st-info-value">
                                    Konsultasi, bantuan penggunaan, custom fitur,
                                    upgrade dan maintenance aplikasi.
                                </p>
                            </div>

                            <a
                                class="st-btn st-btn-wa st-contact-btn"
                                href="https://wa.me/6285178113101?text=Halo%20Soltech%20Wonosobo%2C%20saya%20membutuhkan%20bantuan%20untuk%20aplikasi"
                                target="_blank"
                                rel="noopener noreferrer"
                            >
                                💬 Hubungi Sekarang
                            </a>

                            <div class="st-note">
                                Ada pertanyaan atau ingin upgrade fitur?
                                Kami siap membantu.
                            </div>

                        </div>

                    </section>

                </aside>

            </div>

            <div class="st-footer">
                © {{ date('Y') }} Soltech Wonosobo · Terima kasih atas kepercayaan Anda.
            </div>

        </div>
    </div>
</x-filament-panels::page>

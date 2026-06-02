<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Performa Agen - {{ $bulan_nama }} {{ $tahun }}</title>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body { 
            font-family: Arial, sans-serif; 
            margin: 0;
            padding: 0;
            background: white;
        }
        
        .title {
            font-size: 12pt;
            font-weight: normal;
        }
        
        .footer {
            text-align: right;
            font-size: 12pt;
        }
        
        table, td, th {
            padding: 3px 4px 3px 4px;
            font-size: 12pt;
        }
        
        .table, .table td, .table th {
            border: 1px solid black;
            padding: 3px 4px 3px 4px;
            font-size: 12pt;
        }
        
        table {
            border-collapse: collapse;
            width: 100%;
        }
        
        /* Print Styles - METODE BARU */
        @media print {
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
            
            @page {
                size: A4 portrait;
                margin: 0;
            }
            
            html, body {
                width: 210mm;
                height: 297mm;
                margin: 0;
                padding: 0;
            }
            
            /* Halaman dengan background header & footer */
            .page {
                width: 210mm;
                height: 297mm;
                page-break-after: always;
                position: relative;
                background: white;
                padding: 0;
                margin: 0;
            }
            
            .page:last-child {
                page-break-after: auto;
            }
            
            /* Background Header - FIXED */
            .page::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 210mm;
                height: 120px;
                background-image: url('https://fos01.kobin.co.id/images/bg/header-new.jpeg');
                background-size: 210mm auto;
                background-repeat: no-repeat;
                background-position: top center;
                z-index: 1;
                display: block;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            
            /* Background Footer - FIXED di KANAN BAWAH */
            .page::after {
                content: '';
                position: absolute;
                bottom: 0;
                right: 0;
                width: 350px;
                height: 350px;
                background-image: url('https://fos01.kobin.co.id/images/bg/footer-new2.png');
                background-size: contain;
                background-repeat: no-repeat;
                background-position: bottom right;
                opacity: 0.15;
                z-index: 1;
                display: block;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            
            /* Konten di atas background */
            .page-content {
                position: relative;
                z-index: 10;
                padding: 130px 20mm 50px 20mm;
                min-height: 297mm;
            }
            
            /* Hide screen elements */
            .no-print,
            .action-buttons,
            .loading-overlay,
            .header-screen,
            .footer-screen {
                display: none !important;
            }
        }
        
        /* Screen Styles */
        @media screen {
            body {
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
                background-color: #f3f4f6;
                margin: 0;
                padding: 20px;
            }
            
            /* Halaman untuk tampilan screen */
            .page {
                max-width: 210mm;
                width: 210mm;
                min-height: 297mm;
                margin: 20px auto;
                background: white;
                box-shadow: 0 0 20px rgba(0,0,0,0.1);
                border-radius: 8px;
                position: relative;
                overflow: hidden;
            }
            
            /* Background Header untuk Screen */
            .page::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 120px;
                background-image: url('https://fos01.kobin.co.id/images/bg/header-new.jpeg');
                background-size: cover;
                background-repeat: no-repeat;
                background-position: top center;
                z-index: 1;
                display: block;
            }
            
            /* Background Footer untuk Screen */
            .page::after {
                content: '';
                position: absolute;
                bottom: 0;
                right: 0;
                width: 350px;
                height: 350px;
                background-image: url('https://fos01.kobin.co.id/images/bg/footer-new2.png');
                background-size: contain;
                background-repeat: no-repeat;
                background-position: bottom right;
                opacity: 0.15;
                z-index: 1;
                display: block;
            }
            
            /* Konten di atas background */
            .page-content {
                position: relative;
                z-index: 10;
                padding: 140px 30px 50px 30px;
                min-height: 297mm;
            }
            
            /* Action Buttons */
            .action-buttons {
                position: fixed;
                bottom: 20px;
                right: 20px;
                z-index: 1000;
                display: flex;
                gap: 10px;
                background: white;
                padding: 15px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            }
            
            .btn {
                padding: 10px 20px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                font-weight: 500;
                transition: all 0.3s ease;
            }
            
            .btn-print {
                background-color: #28a745;
                color: white;
            }
            
            .btn-print:hover {
                background-color: #218838;
            }
            
            .btn-back {
                background-color: #6c757d;
                color: white;
            }
            
            .btn-back:hover {
                background-color: #5a6268;
            }
            
            /* Loading Overlay */
            .loading-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(255, 255, 255, 0.9);
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 9999;
                flex-direction: column;
            }
            
            .loading-spinner {
                border: 4px solid #f3f3f3;
                border-top: 4px solid #3498db;
                border-radius: 50%;
                width: 50px;
                height: 50px;
                animation: spin 1s linear infinite;
                margin-bottom: 15px;
            }
            
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
            
            .loading-text {
                font-size: 16px;
                color: #333;
                margin-bottom: 5px;
            }
            
            .progress-text {
                font-size: 14px;
                color: #666;
            }
        }
        
        /* Common Styles */
        .hidden {
            display: none !important;
        }
        
        .text-center {
            text-align: center !important;
        }
        
        .text-right {
            text-align: right !important;
        }
        
        .text-left {
            text-align: left !important;
        }
        
        .text-justify {
            text-align: justify !important;
        }
    </style>
</head>
<body>
    <!-- Action Buttons (Screen Only) -->
    <div class="action-buttons no-print">
        <button onclick="window.print()" class="btn btn-print" id="btnPrint" style="display: none;">
            🖨️ Cetak / Save as PDF
        </button>
        <a href="{{ route('report-performa-agen.index') }}" class="btn btn-back" style="text-decoration: none; display: inline-block;">
            ↩️ Kembali
        </a>
    </div>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="loading-overlay">
        <div class="loading-spinner"></div>
        <div class="loading-text">Memuat data performa agen...</div>
        <div id="progressText" class="progress-text"></div>
    </div>

    <!-- Report Content (akan diisi oleh JavaScript) -->
    <div id="reportContent" class="hidden">
        <!-- Content akan diisi di sini -->
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', async function() {
            const loadingOverlay = document.getElementById('loadingOverlay');
            const reportContent = document.getElementById('reportContent');
            const progressText = document.getElementById('progressText');
            const btnPrint = document.getElementById('btnPrint');
            const loadingText = document.querySelector('.loading-text');
            
            // Data dari controller
            const tahun = '{{ $tahun }}';
            const bulan = '{{ $bulan }}';
            const bulanNama = '{{ $bulan_nama }}';
            const kategori = '{{ $kategori }}';
            const kategoriNama = '{{ $kategori_nama }}';
            const tanggalCetak = '{{ $tanggal_cetak }}';
            const agentCodes = @json($agentCodes);
            const agentsInfo = @json($agentsInfo->keyBy('id_customer')->toArray());
            
            // Konversi bulan ke nomor
            const bulanNomor = parseInt(bulan);
            
            try {
                // Tampilkan loading
                loadingOverlay.style.display = 'flex';
                reportContent.classList.add('hidden');
                
                const cardcodesParam = agentCodes.join(',');
                
                // Hanya 1 API call untuk semua agen dalam 1 bulan
                loadingText.textContent = 'Mengambil data performa...';
                progressText.textContent = `Mengambil data untuk ${agentCodes.length} agen...`;

                let allAgentsData = [];

                try {
                    // Tentukan endpoint berdasarkan kategori
                    const apiEndpoint = kategori === 'granit' 
                        ? 'https://web.kobin.co.id/api/fos/performaagengranit/get_data_performa_agen_dremiooptimized.php'
                        : 'https://web.kobin.co.id/api/fos/performaagenkeramik/get_data_performa_agen_dremiooptimized.php';
                    
                    // API call untuk SEMUA AGEN dalam 1 BULAN
                    const apiUrl = `${apiEndpoint}?tahun=${tahun}&bulan=${bulanNomor}&cardcode=${encodeURIComponent(cardcodesParam)}`;
                    
                    console.log(`Fetching data report (${kategori}): ${apiUrl}`);
                    
                    const apiResponse = await fetch(apiUrl, {
                        method: 'GET',
                        headers: { 'Accept': 'application/json' }
                    });
                    
                    if (apiResponse.ok) {
                        const apiData = await apiResponse.json();
                        
                        if (apiData.success && apiData.data) {
                            const monthData = apiData.data;
                            
                            // Proses data untuk setiap agen dari response
                            allAgentsData = agentCodes.map((cardCode, index) => {
                                const agentInfo = agentsInfo[cardCode] || {};
                                
                                // Update progress
                                if (index % 10 === 0) {
                                    progressText.textContent = `Memproses agen ${index + 1} dari ${agentCodes.length}`;
                                }
                                
                                // Cari data agen ini dari response
                                let agentMonthData;
                                
                                if (Array.isArray(monthData)) {
                                    // Response berupa array
                                    agentMonthData = monthData.find(item => item.CardCode === cardCode);
                                    
                                    // Jika tidak ditemukan tapi response hanya 1 item dan kita hanya minta 1 cardcode
                                    if (!agentMonthData && monthData.length === 1 && agentCodes.length === 1) {
                                        agentMonthData = monthData[0];
                                    }
                                } else if (monthData.CardCode === cardCode) {
                                    // Response berupa object tunggal
                                    agentMonthData = monthData;
                                }
                                
                                if (agentMonthData) {
                                    // Ambil brand langsung dari API Dremio
                                    const brand = agentMonthData.Brand || '-';
                                    
                                    return {
                                        cardcode: agentMonthData.CardCode || cardCode,
                                        cardname: agentMonthData.CardName || agentInfo.name || cardCode,
                                        city: agentMonthData.City || '-',
                                        area: agentMonthData.Area || '-',
                                        target: parseFloat(agentMonthData.Target || 0),
                                        email: agentMonthData.Email || agentInfo.email || '',
                                        brand: brand,
                                        realisasi: parseFloat(agentMonthData.Realisasi || 0),
                                        achievement: parseFloat(agentMonthData.Achievement || 0)
                                    };
                                } else {
                                    // Data tidak ditemukan
                                    return {
                                        cardcode: cardCode,
                                        cardname: agentInfo.name || cardCode,
                                        city: '-',
                                        area: '-',
                                        target: 0,
                                        email: agentInfo.email || '',
                                        brand: '-',
                                        realisasi: 0,
                                        achievement: 0
                                    };
                                }
                            });
                        } else {
                            // Jika API error, set default untuk semua agen
                            allAgentsData = agentCodes.map(cardCode => {
                                const agentInfo = agentsInfo[cardCode] || {};
                                return {
                                    cardcode: cardCode,
                                    cardname: agentInfo.name || cardCode,
                                    city: '-',
                                    area: '-',
                                    target: 0,
                                    email: agentInfo.email || '',
                                    brand: '-',
                                    realisasi: 0,
                                    achievement: 0
                                };
                            });
                        }
                    } else {
                        // Jika fetch gagal, set default untuk semua agen
                        allAgentsData = agentCodes.map(cardCode => {
                            const agentInfo = agentsInfo[cardCode] || {};
                            return {
                                cardcode: cardCode,
                                cardname: agentInfo.name || cardCode,
                                city: '-',
                                area: '-',
                                target: 0,
                                email: agentInfo.email || '',
                                brand: '-',
                                realisasi: 0,
                                achievement: 0
                            };
                        });
                    }
                } catch (apiError) {
                    console.error('Error fetching data for report:', apiError);
                    
                    // Default jika error
                    allAgentsData = agentCodes.map(cardCode => {
                        const agentInfo = agentsInfo[cardCode] || {};
                        return {
                            cardcode: cardCode,
                            cardname: agentInfo.name || cardCode,
                            city: '-',
                            area: '-',
                            target: 0,
                            email: agentInfo.email || '',
                            brand: '-',
                            realisasi: 0,
                            achievement: 0
                        };
                    });
                }
                
                // Render report setelah semua data terkumpul
                renderReport(allAgentsData);
                
                // Sembunyikan loading
                loadingOverlay.style.display = 'none';
                reportContent.classList.remove('hidden');
                btnPrint.style.display = 'inline-block';
                
                // Auto print setelah 5 detik
                setTimeout(() => {
                    window.print();
                }, 5000);
                
            } catch (error) {
                console.error('Error generating report:', error);
                loadingOverlay.innerHTML = `
                    <div style="text-align: center;">
                        <div style="color: #dc3545; font-size: 24px; margin-bottom: 20px;">❌</div>
                        <div style="font-size: 18px; color: #333; margin-bottom: 10px;">Error: Gagal memuat data</div>
                        <div style="font-size: 14px; color: #666;">${error.message}</div>
                        <button onclick="window.location.href='{{ route('report-performa-agen.index') }}'" 
                                style="margin-top: 20px; padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">
                            Kembali
                        </button>
                    </div>
                `;
            }
            
            // Fungsi untuk merender report dengan METODE BARU
            function renderReport(agentsData) {
                let html = '';
                
                agentsData.forEach((agent, index) => {
                    // Format angka
                    const formattedTarget = formatNumber(agent.target);
                    const formattedRealisasi = formatNumber(agent.realisasi);
                    const formattedAchievement = formatNumber(agent.achievement, 2);
                    
                    // Tentukan pesan berdasarkan achievement
                    let message = '';
                    if (agent.achievement >= 100) {
                        message = 'Dengan penuh apresiasi, kami mengucapkan selamat atas pencapaian Anda dalam mencapai target penjualan yang telah ditetapkan. Untuk memaksimalkan momentum positif ini, kami sangat terbuka untuk mendukung Bapak/Ibu dalam menambah variasi produk dan meningkatkan kuantitas order demi performa bisnis kita secara berkelanjutan.';
                    } else if (agent.achievement >= 60) {
                        message = 'Pencapaian Bapak/Ibu dalam periode ini menunjukkan kinerja yang cukup konsisten. Kami mengapresiasi usaha yang telah dilakukan dan percaya bahwa masih terdapat ruang untuk pertumbuhan lebih lanjut. Kami siap mendukung upaya-upaya strategis Bapak/Ibu untuk meningkatkan performa.';
                    } else {
                        message = 'Performa penjualan saat ini tercatat belum mencapai target yang disepakati. Meski demikian, kami percaya bahwa masih terdapat potensi besar yang bisa digali di wilayah Bapak/Ibu. Kami terbuka untuk diskusi strategis mengenai kondisi di lapangan yang dapat mendukung peningkatan kinerja secara konkret di wilayah Bapak/Ibu.';
                    }
                    
                    // METODE BARU: Setiap agen dalam div.page dengan background ::before dan ::after
                    html += `
                        <div class="page">
                            <div class="page-content">
                                <table style="margin-top: 20px;">
                                    <tr>
                                        <td width="100px">Nama Agen</td>
                                        <td>: ${agent.cardname}</td>
                                        <td width="40px">Tanggal</td>
                                        <td width="150px">: ${tanggalCetak}</td>
                                    </tr>
                                    <tr>
                                        <td>Kota/Provinsi</td>
                                        <td colspan="3">: ${agent.city} / ${agent.area}</td>
                                    </tr>
                                    <tr>
                                        <td>Brand</td>
                                        <td colspan="3">: ${agent.brand}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" style="padding-top:40px" align="justify">
                                            Dengan Hormat,<br/>
                                            Bersama ini kami sampaikan pencapaian agen untuk periode ${bulanNama} ${tahun}, dengan detail sebagai berikut:
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" style="padding:20px">
                                            <table class="table">
                                                <tr>
                                                    <th rowspan="2">TARGET</th>
                                                    <th colspan="3">PENCAPAIAN PER BULAN</th>
                                                </tr>
                                                <tr>
                                                    <th>BULAN</th>
                                                    <th>BOX</th>
                                                    <th>%</th>
                                                </tr>
                                                <tr>
                                                    <td align="center">${formattedTarget}</td>
                                                    <td align="center">${bulanNama}</td>
                                                    <td align="center">${formattedRealisasi}</td>
                                                    <td align="center">${formattedAchievement}%</td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" style="padding-bottom:40px" align="justify">
                                            ${message}<br/>
                                            <br>
                                            Terima kasih atas perhatian dan kerjasamanya.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="padding-left:100px">
                                            Hormat kami,<br/><br/><br/><br/><br/><br/><br/>
                                            Welly Santoso<br/>
                                            Direktur Kobin
                                        </td>
                                        <td colspan="2" style="padding-left:50px">
                                            <br/><br/><br/><br/><br/><br/><br/><br/>
                                            (Agen)
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    `;
                });
                
                // Set HTML ke reportContent
                reportContent.innerHTML = html;
            }
            
            // Helper function untuk format angka
            function formatNumber(num, decimals = 0) {
                if (isNaN(num) || num === null || num === undefined) return '0';
                
                // Format number dengan separator ribuan
                const number = parseFloat(num);
                const parts = number.toFixed(decimals).split('.');
                parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                
                return parts.join(',');
            }
        });
    </script>
</body>
</html>
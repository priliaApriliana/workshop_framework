(function () {
    var page = document.getElementById('barcode-scanner-page');
    if (!page) {
        return;
    }

    if (!window.Html5QrcodeScanner) {
        var statusEl = document.getElementById('scanner-status');
        if (statusEl) {
            statusEl.className = 'alert alert-danger py-2 mb-3';
            statusEl.textContent = 'Library scanner tidak termuat. Silakan refresh halaman atau periksa file html5-qrcode.';
        }
        return;
    }

    var searchUrl = page.getAttribute('data-search-url');
    var isProcessing = false;
    var scannerPaused = false;
    var audioCtx = null;
    var scanner = null;
    var audioInitialized = false;

    var scannerStatus = document.getElementById('scanner-status');
    var scannerCard = document.getElementById('scanner-card');
    var restartButton = document.getElementById('btn-restart-scanner');
    var resultContainer = document.getElementById('result-container');
    var errorContainer = document.getElementById('error-container');
    var errorMessage = document.getElementById('error_message');

    function setScannerStatus(message, tone) {
        if (!scannerStatus) return;
        scannerStatus.textContent = message;
        scannerStatus.className = 'alert py-2 mb-3';
        if (tone === 'success') {
            scannerStatus.classList.add('alert-success');
        } else if (tone === 'warning') {
            scannerStatus.classList.add('alert-warning');
        } else if (tone === 'danger') {
            scannerStatus.classList.add('alert-danger');
        } else {
            scannerStatus.classList.add('alert-secondary');
        }
    }

    /**
     * Initialize audio context on first use
     * This will be called during first successful scan
     */
    function initAudio() {
        try {
            if (!audioCtx) {
                var AudioCtx = window.AudioContext || window.webkitAudioContext;
                if (!AudioCtx) {
                    console.warn('Browser tidak mendukung Web Audio API');
                    return Promise.resolve(false);
                }
                audioCtx = new AudioCtx();
            }
            
            if (audioCtx.state === 'suspended') {
                return audioCtx.resume().then(function () {
                    audioInitialized = true;
                    return true;
                }).catch(function (err) {
                    console.warn('Audio resume failed:', err);
                    return false;
                });
            }
            
            audioInitialized = true;
            return Promise.resolve(true);
        } catch (e) {
            console.warn('initAudio error', e);
            return Promise.resolve(false);
        }
    }

    function showVisualBeep(duration) {
        var indicator = document.getElementById('beep-indicator');
        if (!indicator) return;
        indicator.style.display = 'block';
        window.setTimeout(function () {
            indicator.style.display = 'none';
        }, (duration || 200) + 250);
    }

    /**
     * Play beep sound otomatis
     * Ini akan dipanggil otomatis saat barcode berhasil di-scan
     */
    function playBeep(duration, frequency) {
        var beepDuration = duration || 200;
        var beepFrequency = frequency || 800;

        return Promise.resolve(initAudio()).then(function (ready) {
            if (!ready || !audioCtx) {
                showVisualBeep(beepDuration);
                return;
            }

            if (audioCtx.state === 'suspended') {
                return audioCtx.resume().then(function () {
                    playBeep(beepDuration, beepFrequency);
                }).catch(function () {
                    showVisualBeep(beepDuration);
                });
            }

            try {
                var oscillator = audioCtx.createOscillator();
                var gainNode = audioCtx.createGain();
                oscillator.connect(gainNode);
                gainNode.connect(audioCtx.destination);
                oscillator.frequency.value = beepFrequency;
                oscillator.type = 'sine';
                gainNode.gain.setValueAtTime(0.3, audioCtx.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + beepDuration / 1000);
                oscillator.start(audioCtx.currentTime);
                oscillator.stop(audioCtx.currentTime + beepDuration / 1000);
            } catch (e) {
                console.warn('playBeep failed', e);
            }

            showVisualBeep(beepDuration);
        });
    }

    function showError(message) {
        if (errorMessage) {
            errorMessage.textContent = message;
        }
        if (errorContainer) {
            errorContainer.style.display = 'block';
        }
        if (resultContainer) {
            resultContainer.style.display = 'none';
        }
        setScannerStatus(message || 'Terjadi kesalahan.', 'danger');
    }

    function hideMessages() {
        if (errorContainer) {
            errorContainer.style.display = 'none';
        }
        if (resultContainer) {
            resultContainer.style.display = 'none';
        }
    }

    function formatHarga(value) {
        return 'Rp ' + window.Intl.NumberFormat('id-ID').format(value || 0);
    }

    function setResult(data) {
        var idBarang = document.getElementById('id_barang');
        var namaBarang = document.getElementById('nama_barang');
        var hargaBarang = document.getElementById('harga_barang');

        if (idBarang) idBarang.textContent = data.id_barang || '-';
        if (namaBarang) namaBarang.textContent = data.nama || '-';
        if (hargaBarang) hargaBarang.textContent = formatHarga(data.harga);

        if (scannerCard) {
            scannerCard.style.display = 'none';
        }

        if (resultContainer) {
            resultContainer.style.display = 'block';
        }
        setScannerStatus('Barcode terbaca. Scanner ditutup sementara.', 'success');
    }

    function searchBarang(idBarang) {
        var csrfToken = document.querySelector('meta[name="csrf-token"]');
        var token = csrfToken ? csrfToken.getAttribute('content') : '';

        return fetch(searchUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ id_barang: idBarang })
        })
        .then(function (response) {
            return response.json().then(function (payload) {
                return {
                    ok: response.ok,
                    status: response.status,
                    payload: payload
                };
            });
        })
        .then(function (result) {
            if (result.payload && result.payload.success) {
                setResult(result.payload.data);
            } else {
                showError((result.payload && result.payload.message) ? result.payload.message : 'Data tidak ditemukan');
            }
        })
        .catch(function (error) {
            showError('Error: ' + error.message);
        })
        .finally(function () {
            isProcessing = false;
        });
    }

    /**
     * Ketika barcode berhasil di-scan
     * Sistem otomatis akan:
     * 1. Play beep sound
     * 2. Pause scanner
     * 3. Fetch data barang dari API
     */
    function onScanSuccess(decodedText) {
        if (isProcessing) {
            return;
        }

        isProcessing = true;
        scannerPaused = true;
        hideMessages();
        setScannerStatus('Barcode terdeteksi. Memproses data...', 'warning');

        if (scanner && typeof scanner.pause === 'function') {
            scanner.pause();
        }

        // AUTO BEEP - Tidak perlu user click tombol apapun
        playBeep(200, 800).then(function () {
            return searchBarang(decodedText);
        });
    }

    function onScanError(errorMessageText) {
        if (errorMessageText) {
            console.log(errorMessageText);
        }
    }

    function resumeScanner() {
        hideMessages();
        isProcessing = false;
        scannerPaused = false;

        if (scannerCard) {
            scannerCard.style.display = 'block';
        }

        setScannerStatus('Scanner aktif kembali. Arahkan barcode ke kamera.', 'secondary');

        if (scanner && typeof scanner.resume === 'function') {
            scanner.resume();
        }
    }

    if (restartButton) {
        restartButton.addEventListener('click', function () {
            resumeScanner();
        });
    }

    // Initialize scanner
    try {
        scanner = new Html5QrcodeScanner('qr-reader', {
            fps: 30,
            qrbox: { width: 300, height: 300 },
            rememberLastUsedCamera: true,
            supportedFormats: [
                'CODE_128', 'CODE_39', 'CODE_93', 'CODE_11',
                'EAN_13', 'EAN_8',
                'UPC_A', 'UPC_E',
                'ITF', 'RSS_14'
            ]
        }, false);

        scanner.render(onScanSuccess, onScanError);
        setScannerStatus('Scanner siap. Silakan arahkan barcode ke kamera.', 'secondary');
    } catch (e) {
        console.error('Scanner initialization error:', e);
        setScannerStatus('Error inisialisasi scanner: ' + e.message, 'danger');
    }
})();

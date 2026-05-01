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

    var audioStatus = document.getElementById('audio-status');
    var scannerStatus = document.getElementById('scanner-status');
    var enableSoundButton = document.getElementById('btn-enable-sound');
    var restartButton = document.getElementById('btn-restart-scanner');
    var resultContainer = document.getElementById('result-container');
    var errorContainer = document.getElementById('error-container');
    var errorMessage = document.getElementById('error_message');

    function setAudioStatus(message, tone) {
        if (!audioStatus) return;
        audioStatus.textContent = message;
        audioStatus.className = 'text-muted small';
        if (tone === 'success') {
            audioStatus.className = 'text-success small';
        } else if (tone === 'warning') {
            audioStatus.className = 'text-warning small';
        } else if (tone === 'danger') {
            audioStatus.className = 'text-danger small';
        }
    }

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

    function initAudio() {
        try {
            if (!audioCtx) {
                var AudioCtx = window.AudioContext || window.webkitAudioContext;
                if (!AudioCtx) {
                    setAudioStatus('Browser tidak mendukung audio beep.', 'warning');
                    return false;
                }
                audioCtx = new AudioCtx();
            }
            if (audioCtx.state === 'suspended') {
                return audioCtx.resume().then(function () {
                    setAudioStatus('Suara aktif.', 'success');
                    return true;
                }).catch(function () {
                    setAudioStatus('Suara belum aktif.', 'warning');
                    return false;
                });
            }
            setAudioStatus('Suara aktif.', 'success');
            return Promise.resolve(true);
        } catch (e) {
            console.warn('initAudio error', e);
            setAudioStatus('Suara belum aktif.', 'warning');
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

        if (resultContainer) {
            resultContainer.style.display = 'block';
        }
        setScannerStatus('Barcode terbaca. Scanner dijeda sementara.', 'success');
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
        setScannerStatus('Scanner aktif kembali. Arahkan barcode ke kamera.', 'secondary');

        if (scanner && typeof scanner.resume === 'function') {
            scanner.resume();
        }
    }

    if (enableSoundButton) {
        enableSoundButton.addEventListener('click', function () {
            initAudio();
        });
    }

    if (restartButton) {
        restartButton.addEventListener('click', function () {
            resumeScanner();
        });
    }

    scanner = new window.Html5QrcodeScanner('qr-reader', {
        fps: 20,
        qrbox: { width: 250, height: 250 },
        rememberLastUsedCamera: true,
        formatsToSupport: [
            window.Html5QrcodeSupportedFormats.CODE_128,
            window.Html5QrcodeSupportedFormats.CODE_39,
            window.Html5QrcodeSupportedFormats.EAN_13,
            window.Html5QrcodeSupportedFormats.EAN_8,
            window.Html5QrcodeSupportedFormats.UPC_A,
            window.Html5QrcodeSupportedFormats.QR_CODE
        ]
    });

    scanner.render(onScanSuccess, onScanError);

    setAudioStatus('Suara belum aktif.', 'warning');
    setScannerStatus('Scanner siap. Izinkan kamera dan klik "Aktifkan Suara" sekali.', 'secondary');
})();
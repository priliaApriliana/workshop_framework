<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Vendor;
use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Payment;
use App\Models\User;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap;
use Midtrans\Transaction as MidtransTransaction;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Color\Color;

class CustomerOrderController extends Controller
{
    public function __construct()
    {
        // Konfigurasi Midtrans
        MidtransConfig::$serverKey = config('midtrans.server_key');
        MidtransConfig::$isProduction = config('midtrans.is_production');
        MidtransConfig::$isSanitized = config('midtrans.is_sanitized');
        MidtransConfig::$is3ds = config('midtrans.is_3ds');
    }

    // Generate nama customer otomatis: Guest_000001, Guest_000002, dst.
    // Tanpa konflik (mengambil nomor terakhir + 1)
    private function generateGuestName(): string
    {
        $lastGuest = Pesanan::where('nama_customer', 'like', 'Guest_%')
            ->orderBy('id_pesanan', 'desc')
            ->first();

        if ($lastGuest) {
            // Ambil nomor terakhir dari format Guest_XXXXXX
            $lastNumber = (int) str_replace('Guest_', '', $lastGuest->nama_customer);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return 'Guest_' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    }

    //  Halaman utama customer: Pilih Vendor
    //  PUBLIC - tanpa login
    public function index()
    {
        $vendors = Vendor::withCount('menus')->get();
        return view('customer.order.index', compact('vendors'));
    }

    // Tampilkan menu berdasarkan vendor yang dipilih
    // SELECT BERJENJANG: Customer pilih vendor â†’ muncul menu vendor tsb
    public function show($id_vendor)
    {
        $vendor = Vendor::findOrFail($id_vendor);
        $menus = Menu::where('id_vendor', $id_vendor)->get();

        return view('customer.order.show', compact('vendor', 'menus'));
    }

    // Buat pesanan + Generate Guest user otomatis
    // Setelah order dibuat â†’ redirect ke halaman pembayaran
    public function store(Request $request)
    {
        $request->validate([
            'id_vendor' => 'required|exists:vendor,id_vendor',
            'items' => 'required|string',
            'total' => 'required|integer|min:1',
        ]);

        $items = json_decode($request->items, true);

        if (empty($items)) {
            return response()->json([
                'success' => false,
                'message' => 'Pilih minimal 1 menu.'
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Auto-generate guest name
            $namaCustomer = $this->generateGuestName();

            // Hitung total dari server-side (keamanan)
            $totalHarga = 0;
            foreach ($items as $item) {
                $menu = Menu::findOrFail($item['id_menu']);
                $totalHarga += $menu->harga * $item['jumlah'];
            }

            // Simpan pesanan
            $pesanan = Pesanan::create([
                'nama_customer' => $namaCustomer,
                'total' => $totalHarga,
                'metode_bayar' => 0, // belum dipilih
                'status_bayar' => 0, // pending
            ]);

            // Simpan detail pesanan terakhir untuk menu customer
            session()->put('last_order_id', $pesanan->id_pesanan);

            // Simpan detail pesanan
            foreach ($items as $item) {
                $menu = Menu::findOrFail($item['id_menu']);
                DetailPesanan::create([
                    'id_pesanan' => $pesanan->id_pesanan,
                    'id_menu' => $item['id_menu'],
                    'jumlah' => $item['jumlah'],
                    'harga' => $menu->harga,
                    'subtotal' => $menu->harga * $item['jumlah'],
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat!',
                'id_pesanan' => $pesanan->id_pesanan,
                'nama_customer' => $namaCustomer,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Customer Order Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pesanan: ' . $e->getMessage()
            ], 500);
        }
    }

    // Halaman Pembayaran: Buat Snap Token Midtrans
    // Customer bayar via VA atau QRIS
    public function payment($id_pesanan)
    {
        $pesanan = Pesanan::with('detailPesanans.menu')->findOrFail($id_pesanan);

        $snapToken = null;
        $clientKey = config('midtrans.client_key');

        // Hanya buat snap token jika belum lunas
        if ($pesanan->status_bayar == 0) {
            try {
                $orderId = 'ORDER-' . $pesanan->id_pesanan . '-' . time();

                $itemDetails = [];
                foreach ($pesanan->detailPesanans as $detail) {
                    $itemDetails[] = [
                        'id' => $detail->id_menu,
                        'price' => $detail->harga,
                        'quantity' => $detail->jumlah,
                        'name' => substr($detail->menu->nama_menu ?? 'Menu', 0, 50),
                    ];
                }

                $params = [
                    'transaction_details' => [
                        'order_id' => $orderId,
                        'gross_amount' => $pesanan->total,
                    ],
                    'customer_details' => [
                        'first_name' => $pesanan->nama_customer,
                        'email' => 'guest@workshop.local',
                    ],
                    'item_details' => $itemDetails,
                    'enabled_payments' => [
                        'bank_transfer', 'echannel', 'bca_va', 'bni_va', 'bri_va',
                        'permata_va', 'other_va', 'gopay', 'shopeepay', 'qris'
                    ],
                ];

                $snapToken = Snap::getSnapToken($params);

                // Simpan payment record awal
                Payment::create([
                    'id_pesanan' => $pesanan->id_pesanan,
                    'payment_method' => 'pending',
                    'payment_reference' => $orderId,
                    'amount' => $pesanan->total,
                    'status' => 'pending',
                ]);

            } catch (\Exception $e) {
                Log::error('Midtrans Snap Error: ' . $e->getMessage());
                $snapToken = null;
            }
        }

        return view('customer.order.payment', compact('pesanan', 'snapToken', 'clientKey'));
    }

    // Halaman Status Pesanan
    public function status($id_pesanan)
    {
        $pesanan = Pesanan::with(['detailPesanans.menu', 'payments'])->findOrFail($id_pesanan);

        $qrBase64 = null;

        // Hanya generate QR jika pesanan sudah LUNAS
        if ($pesanan->status_bayar == 1) {
            $qrCode = new QrCode(
                data: (string) $pesanan->id_pesanan,
                encoding: new Encoding('UTF-8'),
                errorCorrectionLevel: ErrorCorrectionLevel::High,
                size: 200,
                margin: 10,
            );

            $writer = new PngWriter();
            $result = $writer->write($qrCode);
            $qrBase64 = base64_encode($result->getString());
        }
        return view('customer.order.status', compact('pesanan', 'qrBase64'));
    }

    //  * Update status pembayaran dari client-side (setelah Snap callback)
    public function updateStatus(Request $request, $id_pesanan)
    {
        $pesanan = Pesanan::findOrFail($id_pesanan);

        $transactionStatus = $request->input('transaction_status');
        $paymentType = $request->input('payment_type', 'unknown');

        // Tentukan metode bayar
        $metodeBayar = 0;
        if (in_array($paymentType, ['bank_transfer', 'echannel'])) {
            $metodeBayar = 1; // VA
        } elseif (in_array($paymentType, ['gopay', 'shopeepay', 'qris'])) {
            $metodeBayar = 2; // QRIS
        }

        // Update metode bayar
        if ($metodeBayar > 0) {
            $pesanan->metode_bayar = $metodeBayar;
        }

        // Jika settlement â†’ lunas
        if ($transactionStatus === 'settlement' || $transactionStatus === 'capture') {
            $pesanan->status_bayar = 1;

            // Update payment record
            $payment = Payment::where('id_pesanan', $id_pesanan)
                ->orderBy('id_payment', 'desc')
                ->first();

            if ($payment) {
                $payment->update([
                    'status' => 'completed',
                    'payment_method' => $paymentType,
                    'paid_at' => now(),
                ]);
            }
        }

        $pesanan->save();

        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diperbarui',
            'status_bayar' => $pesanan->status_bayar,
        ]);
    }

    //  * Cek status pembayaran langsung ke Midtrans API
    //  * Digunakan saat customer klik "Cek Pembayaran"
    public function checkPaymentStatus($id_pesanan)
    {
        $pesanan = Pesanan::findOrFail($id_pesanan);

        // Cari payment reference terakhir
        $payment = Payment::where('id_pesanan', $id_pesanan)
            ->orderBy('id_payment', 'desc')
            ->first();

        if (!$payment || !$payment->payment_reference) {
            return response()->json([
                'success' => false,
                'status' => 'not_found',
                'message' => 'Belum ada transaksi pembayaran untuk pesanan ini.',
            ]);
        }

        try {
            // Cek status ke Midtrans API
            $status = MidtransTransaction::status($payment->payment_reference);

            $transactionStatus = $status->transaction_status ?? 'unknown';
            $paymentType = $status->payment_type ?? 'unknown';

            // Jika settlement â†’ update ke lunas
            if ($transactionStatus === 'settlement' || $transactionStatus === 'capture') {
                $pesanan->status_bayar = 1;

                // Tentukan metode bayar
                if (in_array($paymentType, ['bank_transfer', 'echannel'])) {
                    $pesanan->metode_bayar = 1;
                } elseif (in_array($paymentType, ['gopay', 'shopeepay', 'qris'])) {
                    $pesanan->metode_bayar = 2;
                }

                $pesanan->save();

                // Update payment record
                $payment->update([
                    'status' => 'completed',
                    'payment_method' => $paymentType,
                    'paid_at' => now(),
                ]);

                return response()->json([
                    'success' => true,
                    'status' => 'settlement',
                    'message' => 'Pembayaran berhasil! Status pesanan sudah diperbarui menjadi LUNAS.',
                ]);
            }

            return response()->json([
                'success' => true,
                'status' => $transactionStatus,
                'message' => 'Status pembayaran: ' . $transactionStatus,
            ]);

        } catch (\Exception $e) {
            Log::error('Midtrans Check Status Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => 'Gagal mengecek status: ' . $e->getMessage(),
            ]);
        }
    }

    //  * Midtrans Webhook / Callback (server-to-server)
    //  * Route tanpa CSRF (sudah dikecualikan di bootstrap/app.php)
    public function midtransCallback(Request $request)
    {
        try {
            $serverKey = config('midtrans.server_key');
            $hashed = hash('sha512',
                $request->order_id .
                $request->status_code .
                $request->gross_amount .
                $serverKey
            );

            // Verifikasi signature
            if ($hashed !== $request->signature_key) {
                Log::warning('Midtrans callback: Invalid signature', $request->all());
                return response()->json(['message' => 'Invalid signature'], 403);
            }

            $orderId = $request->order_id;
            $transactionStatus = $request->transaction_status;
            $paymentType = $request->payment_type;

            // Cari payment berdasarkan reference
            $payment = Payment::where('payment_reference', $orderId)->first();

            if (!$payment) {
                Log::warning('Midtrans callback: Payment not found for order ' . $orderId);
                return response()->json(['message' => 'Payment not found'], 404);
            }

            $pesanan = Pesanan::find($payment->id_pesanan);

            if (!$pesanan) {
                return response()->json(['message' => 'Pesanan not found'], 404);
            }

            // Update berdasarkan status
            if ($transactionStatus === 'settlement' || $transactionStatus === 'capture') {
                $pesanan->status_bayar = 1; // Lunas

                if (in_array($paymentType, ['bank_transfer', 'echannel'])) {
                    $pesanan->metode_bayar = 1; // VA
                } elseif (in_array($paymentType, ['gopay', 'shopeepay', 'qris'])) {
                    $pesanan->metode_bayar = 2; // QRIS
                }

                $pesanan->save();

                $payment->update([
                    'status' => 'completed',
                    'payment_method' => $paymentType,
                    'paid_at' => now(),
                ]);

            } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                $payment->update([
                    'status' => 'failed',
                    'payment_method' => $paymentType,
                ]);
            } elseif ($transactionStatus === 'pending') {
                $payment->update([
                    'status' => 'pending',
                    'payment_method' => $paymentType,
                ]);
            }

            Log::info('Midtrans callback processed', [
                'order_id' => $orderId,
                'status' => $transactionStatus,
                'payment_type' => $paymentType,
            ]);

            return response()->json(['message' => 'OK']);

        } catch (\Exception $e) {
            Log::error('Midtrans Callback Error: ' . $e->getMessage());
            return response()->json(['message' => 'Error processing callback'], 500);
        }
    }

    public function permanentQRCode()
    {
        $idPesanan = session('last_order_id');

        if (!$idPesanan) {
            return redirect()->route('customer.order.index')->with('error', 'Belum ada pesanan terakhir yang bisa dibuka.');
        }

        return redirect()->route('customer.order.qrcode', $idPesanan);
    }
    public function showQRCode($id_pesanan)
    {
        $pesanan = Pesanan::find($id_pesanan);

        if (!$pesanan) {
            abort(404, 'Pesanan tidak ditemukan');
        }

        $payment = Payment::where('id_pesanan', $id_pesanan)->first();

        return view('qrcode_display', [
            'pesanan' => $pesanan,
            'payment' => $payment,
        ]);
    }
}

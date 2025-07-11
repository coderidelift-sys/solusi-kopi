# Setup Midtrans Integration

## Konfigurasi Environment Variables

Tambahkan variabel berikut ke file `.env`:

```env
# Midtrans Configuration
MIDTRANS_SERVER_KEY=your_midtrans_server_key_here
MIDTRANS_CLIENT_KEY=your_midtrans_client_key_here
MIDTRANS_MERCHANT_ID=your_midtrans_merchant_id_here
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```

## Setup Sandbox (Development)

1. Daftar akun di [Midtrans Dashboard](https://dashboard.midtrans.com/)
2. Pilih environment **Sandbox**
3. Ambil Server Key dan Client Key dari dashboard
4. Set `MIDTRANS_IS_PRODUCTION=false` di file `.env`

## Setup Production

1. Set `MIDTRANS_IS_PRODUCTION=true` di file `.env`
2. Gunakan Server Key dan Client Key dari environment Production
3. Pastikan notification URL sudah benar: `https://yourdomain.com/api/midtrans/notification`

## Database Migration

Jalankan migration untuk menambahkan kolom `snap_token`:

```bash
php artisan migrate
```

## Testing

1. Buat order baru
2. Pilih metode pembayaran QRIS
3. Scan QR code yang muncul
4. Lakukan pembayaran di aplikasi e-wallet
5. Status pembayaran akan otomatis terupdate

## Troubleshooting

### Error: "Midtrans error: Unable to get snap token"

- Pastikan Server Key dan Client Key sudah benar
- Pastikan environment (sandbox/production) sudah sesuai
- Cek log Laravel untuk detail error

### Error: "Invalid signature"

- Pastikan Server Key yang digunakan untuk signature verification sudah benar
- Pastikan notification URL sudah terdaftar di Midtrans Dashboard

### QR Code tidak muncul

- Pastikan Client Key sudah benar
- Cek browser console untuk error JavaScript
- Pastikan internet connection stabil

## Notification URL

URL untuk menerima callback dari Midtrans:
```
https://yourdomain.com/api/midtrans/notification
```

Pastikan URL ini sudah terdaftar di Midtrans Dashboard. 

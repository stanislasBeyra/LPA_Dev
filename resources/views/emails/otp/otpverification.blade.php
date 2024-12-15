<!DOCTYPE html>
  <html lang="en">
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>OTP Verification</title>

      <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap');
          
          body {
              font-family: 'Poppins', sans-serif;
              display: flex;
              justify-content: center;
              align-items: center;
              height: 100vh;
              margin: 0;
              background-color: #fff;
              color: #000;
              background-image: 
                  radial-gradient(circle at 25% 25%, rgba(166, 86, 246, 0.1) 2%, transparent 0%),
                  radial-gradient(circle at 75% 75%, rgba(102, 101, 241, 0.1) 2%, transparent 0%);
              background-size: 60px 60px;
          }
          .container {
              background-color: '#fff';
              padding: 3rem;
              border-radius: 16px;
              box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
              text-align: center;
              backdrop-filter: blur(10px);
              border: 1px solid rgba(255, 255, 255, 0.1);
              max-width: 400px;
              width: 100%;
          }
          h1 {
              margin-bottom: 1.5rem;
              color: #000;
              font-weight: 600;
              font-size: 2rem;
          }
          p {
              margin-bottom: 2rem;
              color: #b0b0b0;
              font-weight: 300;
          }
          .otp-input {
              display: flex;
              justify-content: center;
              margin-bottom: 2rem;
          }
          .otp-input input {
              width: 50px;
              height: 50px;
              margin: 0 8px;
              text-align: center;
              font-size: 1.5rem;
              border: 2px solid #6665F1;
              border-radius: 12px;
              background-color: "#f98c40";
              color: #000;
              transition: all 0.3s ease;
          }
          .otp-input input:focus {
              border-color: #f98c40;
              box-shadow: 0 0 0 2px rgba(166, 86, 246, 0.3);
              outline: none;
          }
          .otp-input input::-webkit-outer-spin-button,
          .otp-input input::-webkit-inner-spin-button {
              -webkit-appearance: none;
              margin: 0;
          }
          .otp-input input[type=number] {
              -moz-appearance: textfield;
          }
          button {
              background: linear-gradient(135deg, #6665F1, #A556F6);
              color: white;
              border: 2px solid #6665F1;
              padding: 12px 24px;
              font-size: 1rem;
              border-radius: 8px;
              cursor: pointer;
              margin: 5px;
              transition: all 0.3s ease;
              font-weight: 500;
              letter-spacing: 0.5px;
          }
          button:hover {
              background: linear-gradient(135deg, #A556F6, #6665F1);
              transform: translateY(-2px);
              box-shadow: 0 4px 8px rgba(166, 86, 246, 0.3);
          }
          button:disabled {
              background: #cccccc;
              border-color: #999999;
              color: #666666;
              cursor: not-allowed;
              transform: none;
              box-shadow: none;
          }
          #timer {
              font-size: 1rem;
              color: #A556F6;
              font-weight: 500;
              margin-left: 10px;
          }
          @keyframes pulse {
              0% { opacity: 1; }
              50% { opacity: 0.5; }
              100% { opacity: 1; }
          }
          .expired {
              animation: pulse 2s infinite;
              color: #ff4444;
          }
          .resend-text {
              margin-top: 1rem;
              font-size: 0.9rem;
              color: #b0b0b0;
          }
          .resend-link {
              color: #6665F1;
              text-decoration: none;
              cursor: pointer;
              transition: color 0.3s ease;
          }
          .resend-link:hover {
              color: #A556F6;
              text-decoration: underline;
          }
          #email {
              color: #A556F6;
              font-weight: 500;
          }
      </style>
 
  </head>
  <body>
      <div class="container">
          <h1>OTP Verification</h1>
          <p>Enter the OTP you received to <span id="email"></span></p>
          <div class="otp-input">
              <input type="number" min="0" max="9" required>
              <input type="number" min="0" max="9" required>
              <input type="number" min="0" max="9" required>
              <input type="number" min="0" max="9" required>
              <input type="number" min="0" max="9" required>
              <input type="number" min="0" max="9" required>
          </div>
          <button onclick="verifyOTP()">Verify</button>
          <div class="resend-text">
              Didn't receive the code? 
              <span class="resend-link" onclick="resendOTP()">Resend Code</span>
              <span id="timer"></span>
          </div>
      </div>
      <script>
         const inputs = document.querySelectorAll('.otp-input input');
          const timerDisplay = document.getElementById('timer');
          const resendLink = document.querySelector('.resend-link');
          const emailSpan = document.getElementById('email');
          let timeLeft = 120; // 2 minutes in seconds
          let timerId;
          let canResend = true;

          // Simulating an email for demonstration
          emailSpan.textContent = "user@example.com";

          function startTimer() {
              timerId = setInterval(() => {
                  if (timeLeft <= 0) {
                      clearInterval(timerId);
                      timerDisplay.textContent = "Code expired";
                      timerDisplay.classList.add('expired');
                      inputs.forEach(input => input.disabled = true);
                      canResend = false;
                  } else {
                      const minutes = Math.floor(timeLeft / 60);
                      const seconds = timeLeft % 60;
                      timerDisplay.textContent = `(${minutes}:${seconds.toString().padStart(2, '0')})`;
                      timeLeft--;
                  }
              }, 1000);
          }

          function resendOTP() {
              if (canResend) {
                  alert("New OTP sent!");
                  timeLeft = 120;
                  inputs.forEach(input => {
                      input.value = '';
                      input.disabled = false;
                  });
                  inputs[0].focus();
                  clearInterval(timerId);
                  timerDisplay.classList.remove('expired');
                  startTimer();
              } else {
                  alert("Cannot resend code. Time has expired.");
              }
          }

          inputs.forEach((input, index) => {
              input.addEventListener('input', (e) => {
                  if (e.target.value.length > 1) {
                      e.target.value = e.target.value.slice(0, 1);
                  }
                  if (e.target.value.length === 1) {
                      if (index < inputs.length - 1) {
                          inputs[index + 1].focus();
                      }
                  }
              });

              input.addEventListener('keydown', (e) => {
                  if (e.key === 'Backspace' && !e.target.value) {
                      if (index > 0) {
                          inputs[index - 1].focus();
                      }
                  }
                  if (e.key === 'e') {
                      e.preventDefault();
                  }
              });
          });

          function verifyOTP() {
              const otp = Array.from(inputs).map(input => input.value).join('');
              if (otp.length === 6) {
                  if (timeLeft > 0) {
                      alert(`Verifying OTP: ${otp}`);
                  } else {
                      alert('OTP has expired. Please request a new one.');
                  }
              } else {
                  alert('Please enter a 6-digit OTP');
              }
          }

          startTimer();
      </script>
  </body>
  </html>
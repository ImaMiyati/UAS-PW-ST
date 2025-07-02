<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Welcome to One Piece</title>
    <link rel="icon" type="image/png" href="log.png">
    <style>
        body, html {
  margin: 0;
  padding: 0;
  height: 100%;
  font-family: 'Segoe UI', sans-serif;
  overflow: hidden;
  background: radial-gradient(circle at center, #0a0f2c, #05081a);
  position: relative;
}

/* Efek kabut biru transparan lembut */
body::before {
  content: "";
  position: fixed;
  top: 0; left: 0;
  width: 100%; height: 100%;
  background: radial-gradient(circle, rgba(90, 110, 255, 0.05) 10%, transparent 80%);
  animation: mistMove 30s ease-in-out infinite;
  z-index: 0;
}

/* Efek bintang */
body::after {
  content: "";
  position: fixed;
  top: 0; left: 0;
  width: 100%; height: 100%;
  background: url("https://www.transparenttextures.com/patterns/stardust.png");
  opacity: 0.2;
  animation: moveStars 100s linear infinite;
  z-index: 0;
}

@keyframes mistMove {
  0%, 100% { background-position: 0 0; }
  50% { background-position: 100px 200px; }
}

@keyframes moveStars {
  0% { background-position: 0 0; }
  100% { background-position: 800px 1000px; }
}

#splash {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  height: 100%;
  position: relative;
  z-index: 1;
  text-align: center;
}

#splash video {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  z-index: -1;
  filter: brightness(0.4);
}

.splash-img {
  width: 220px;
  margin-bottom: 25px;
  animation: fadeInUp 1.5s ease, pulse 4s ease-in-out infinite;
  filter: drop-shadow(0 0 20px rgba(0, 191, 255, 0.8));
  transition: transform 0.4s ease;
}

.splash-img:hover {
  transform: scale(1.08);
}

h2 {
  font-size: 42px;
  margin-bottom: 20px;
  color: #ffffff;
  text-shadow: 0 0 15px #3c9bff;
  animation: fadeInUp 2s ease;
}

p.subtitle {
  font-size: 18px;
  color: #e0eaff;
  text-shadow: 0 0 5px #1a6bff;
  margin-bottom: 20px;
  animation: fadeInUp 2.2s ease;
}

#typewriter {
  font-size: 15px;
  color: #c6d4ff;
  text-shadow: 1px 1px 4px #5a8eff;
  margin-bottom: 30px;
  animation: fadeInUp 2.4s ease;
}

/* Tombol anime-style glowing */
button {
  padding: 14px 30px;
  font-size: 16px;
  background: transparent;
  color: #ffffff;
  border: 2px solid #50aaff;
  border-radius: 12px;
  cursor: pointer;
  position: relative;
  overflow: hidden;
  box-shadow: 0 0 15px rgba(64, 123, 255, 0.4);
  transition: all 0.4s ease;
  animation: fadeInUp 2.6s ease;
}

button::before {
  content: "";
  position: absolute;
  top: 50%;
  left: 50%;
  width: 300%;
  height: 300%;
  background: rgba(74, 144, 255, 0.15);
  transform: translate(-50%, -50%) scale(0);
  border-radius: 50%;
  z-index: 0;
  transition: transform 0.4s ease;
}

button:hover::before {
  transform: translate(-50%, -50%) scale(1);
}

button:hover {
  color: #cceeff;
  border-color: #89cfff;
  box-shadow: 0 0 20px rgba(100, 180, 255, 0.8);
}

button:active {
  transform: scale(0.96);
  box-shadow: 0 0 10px rgba(7, 17, 34, 0.7);
}

button span {
  position: relative;
  z-index: 1;
}

/* Animasi masuk */
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(25px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Glow napas untuk logo */
@keyframes pulse {
  0%, 100% {
    filter: drop-shadow(0 0 15px rgba(0, 191, 255, 0.7));
  }
  50% {
    filter: drop-shadow(0 0 30px rgba(0, 191, 255, 1));
  }
}


    </style>
</head>

<body>
       <audio id="bg-audio" src="luffy.mp3" preload="auto" autoplay></audio>
    <div id="splash">
        <video autoplay muted loop playsinline>
            <source src="https://res.cloudinary.com/dlqmvkrwa/video/upload/v1747967605/bgklik_nb0ggu.mp4"
                type="video/mp4">
        </video>
        <img src="logo.png" alt="Luffy" class="splash-img">
        <h2>Yōkoso Nakama Wanpīsu</h2>
        <p class="subtitle">Klik tombol di bawah untuk masuk ke dunia One Piece!</p>
        <p class="subtitle" id="typewriter"></p>
        <button onclick="masuk()"><span>Klik di sini!</span></button>
    </div>

    <script>
        const text = "ワンピースのキャラクターたちを発見しよう！";
        let i = 0;
        function typeEffect() {
            if (i < text.length) {
                document.getElementById("typewriter").innerHTML += text.charAt(i);
                i++;
                setTimeout(typeEffect, 50);
            }
        }

        window.onload = function () {
            typeEffect();
        };

        function masuk() {
            window.location.href = "login.html";
        }
    </script>
</body>

</html>

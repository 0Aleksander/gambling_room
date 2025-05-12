<?php
session_start(); // Start the session
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Darknet Dice</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="terminal-bg">
<canvas id="matrixCanvas"></canvas>
  <div class="nexus-terminal">
    <div class="terminal-header">
      <div class="terminal-title">DARKNET DICE v1.3.37</div>
      <div class="terminal-controls">
        <div class="control-dot close"></div>
        <div class="control-dot minimize"></div>
        <div class="control-dot maximize"></div>
      </div>
    </div>
    
    <div class="terminal-content">
      <h1 class="welcome-message">🧬 Welcome to Darknet Dice</h1>
      
      <div class="terminal-prompt">> Accessing encrypted gambling protocol...</div>
      <div class="terminal-prompt">> Establishing secure connection...</div>
      <div class="terminal-prompt">> Bet your data. Crack the odds.</div>
      
      <a href="play.php" class="btn-nexus">
        <span class="btn-text">Connect to Node</span>
        <span class="btn-glitch"></span>
      </a>
      
      <div class="terminal-status">
        <span>> STATUS: <span class="online">ONLINE</span></span>
        <span>> ENCRYPTION: AES-256</span>
      </div>
    </div>
  </div>

  <script>
  // Enhanced Matrix Background Effect
  const state = {
    fps: 25,
    color: "#33ff33",
    charset: "01アァカサタナハマヤャラワガザダバパイィキシチニヒミリヰギジヂビピウゥクスツヌフムユュルグズブヅプエェケセテネヘメレヱゲゼデベペオォコソトノホモヨョロヲゴゾドボポヴッン",
    size: 18
  };

  const matrixCanvas = document.getElementById("matrixCanvas");
  const matrixCtx = matrixCanvas.getContext("2d");

  let w, h, p;
  const resize = () => {
    w = matrixCanvas.width = innerWidth;
    h = matrixCanvas.height = innerHeight;
    p = Array(Math.ceil(w / state.size)).fill(0);
  };
  window.addEventListener("resize", resize);
  resize();

  const random = (items) => items[Math.floor(Math.random() * items.length)];

  const drawMatrix = () => {
    matrixCtx.fillStyle = "rgba(0,0,0,.03)";
    matrixCtx.fillRect(0, 0, w, h);
    matrixCtx.fillStyle = state.color;
    matrixCtx.font = `bold ${state.size}px 'Courier New', monospace`;

    for (let i = 0; i < p.length; i++) {
      let v = p[i];
      const opacity = Math.min(1, v/h * 1.5);
      matrixCtx.globalAlpha = opacity;
      matrixCtx.fillText(random(state.charset), i * state.size, v);
      p[i] = v >= h || v >= 10000 * Math.random() ? 0 : v + state.size;
    }
    matrixCtx.globalAlpha = 1;
  };

  setInterval(drawMatrix, 1000 / state.fps);
  </script>
</body>
</html>
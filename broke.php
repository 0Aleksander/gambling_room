<?php
session_start();
// If somehow they have money, send them back to play
if ($_SESSION['balance'] > 0) {
    header("Location: play.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>BANKRUPT</title>
  <style>
    /* ===== TERMINAL CORE STYLES ===== */
    body.terminal-bg {
        background-color: #0a0a0a;
        color: #33ff33;
        font-family: 'Courier Prime', 'Courier New', monospace;
        min-height: 100vh;
        margin: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden;
        position: relative;
        line-height: 1.5;
    }

    /* ===== MATRIX BACKGROUND ===== */
    #matrixCanvas {
        position: fixed;
        top: 0;
        left: 0;
        z-index: 0;
        opacity: 0.15;
    }

    /* ===== TERMINAL CONTAINER ===== */
    .terminal-container {
        position: relative;
        width: 90%;
        max-width: 700px;
        border: 1px solid #ff3333;
        box-shadow: 0 0 30px rgba(255, 51, 51, 0.5),
                    inset 0 0 20px rgba(255, 51, 51, 0.3);
        background-color: rgba(10, 10, 10, 0.9);
        backdrop-filter: blur(3px);
        padding: 0;
        overflow: hidden;
    }

    /* ===== TERMINAL HEADER BAR ===== */
    .terminal-header {
        background: linear-gradient(to right, #3a0a0a, #0d0d0d);
        padding: 8px 15px;
        border-bottom: 1px solid #ff3333;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .terminal-title {
        font-weight: bold;
        text-shadow: 0 0 8px #ff3333;
        letter-spacing: 1px;
    }

    .terminal-controls {
        display: flex;
        gap: 10px;
    }

    .terminal-btn {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 1px solid #ff3333;
    }

    .terminal-btn.close { background-color: #ff3333; }
    .terminal-btn.minimize { background-color: #ffcc33; }
    .terminal-btn.maximize { background-color: #33cc33; }

    /* ===== TERMINAL CONTENT ===== */
    .terminal-content {
        padding: 25px;
        position: relative;
        text-align: center;
    }

    .terminal-prompt {
        color: #ff3333;
        margin-bottom: 20px;
    }

    .terminal-prompt::before {
        content: "> ";
        color: #ff3333;
    }

    /* ===== DICE DISPLAY ===== */
    .dice-display {
        font-family: monospace;
        white-space: pre;
        margin: 20px 0;
        color: #ff3333;
        text-align: center;
        font-size: 1.2rem;
        line-height: 1.3;
        text-shadow: 0 0 5px #ff3333;
    }

    /* ===== RESULT DISPLAY ===== */
    .terminal-result {
        margin: 20px 0;
        padding: 15px;
        border-left: 3px solid #ff3333;
        background-color: rgba(30, 0, 0, 0.3);
        animation: textShadow 1.5s infinite alternate;
    }

    @keyframes textShadow {
        from { text-shadow: 0 0 5px #ff3333; }
        to { text-shadow: 0 0 10px #ff3333, 0 0 20px #ff3333; }
    }

    /* ===== BUTTON STYLING ===== */
    .terminal-btn-secondary {
        background-color: transparent;
        color: #ff3333;
        border: 1px solid #ff3333;
        padding: 10px 20px;
        margin-top: 15px;
        display: inline-block;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .terminal-btn-secondary:hover {
        background-color: rgba(255, 51, 51, 0.1);
        box-shadow: 0 0 15px rgba(255, 51, 51, 0.3);
    }

    /* ===== STATUS BAR ===== */
    .terminal-status {
        display: flex;
        justify-content: space-between;
        margin-top: 30px;
        padding-top: 10px;
        border-top: 1px dashed rgba(255, 51, 51, 0.5);
        font-size: 0.9rem;
        color: #b82e2e;
    }
  </style>
  <script>
    // Redirect after 10 seconds
    setTimeout(function() {
        window.location.href = "index.php";
    }, 10000);
  </script>
</head>
<body class="terminal-bg">

<!-- Matrix background canvas -->
<canvas id="matrixCanvas"></canvas>

<div class="terminal-container">
    <div class="terminal-header">
        <div class="terminal-title">BANKRUPT TERMINAL</div>
        <div class="terminal-controls">
            <div class="terminal-btn close"></div>
            <div class="terminal-btn minimize"></div>
            <div class="terminal-btn maximize"></div>
        </div>
    </div>
    
    <div class="terminal-content">
        <div class="dice-display">
┌───────┐
│ ✖   ✖ │
│ BROKE │
│ ✖   ✖ │
└───────┘
        </div>
        
        <div class="terminal-prompt">> ACCOUNT DEPLETED</div>
        <div class="terminal-result">You've run out of packets. Connection will reset in 10 seconds...</div>
        
        <a href="index.php" class="terminal-btn-secondary">RESTART NOW</a>
        
        <div class="terminal-status">
            <span>STATUS: TERMINATED</span>
            <span>v1.0.0</span>
        </div>
    </div>
</div>

<!-- Add the Matrix JavaScript -->
<script>
  const state = {
    fps: 20,
    color: "#ff3333",
    charset: "01",
    size: 25
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
    matrixCtx.fillStyle = "rgba(0,0,0,.05)";
    matrixCtx.fillRect(0, 0, w, h);
    matrixCtx.fillStyle = state.color;
    matrixCtx.font = state.size + "px monospace";

    for (let i = 0; i < p.length; i++) {
      let v = p[i];
      matrixCtx.fillText(random(state.charset), i * state.size, v);
      p[i] = v >= h || v >= 10000 * Math.random() ? 0 : v + state.size;
    }
  };

  setInterval(drawMatrix, 1000 / state.fps);
</script>

</body>
</html>
<<?php
session_start();

// Initialize the balance if it doesn't exist yet
if (!isset($_SESSION['balance'])) {
    $_SESSION['balance'] = 100;
}

// Check if balance is zero and redirect to broke.php
if ($_SESSION['balance'] <= 0) {
    header("Location: broke.php");
    exit();
}

$result = '';
$diceArt = ''; // Initialize dice art variable

// ASCII dice art representations
$diceFaces = [
    1 => "┌─────────┐\n│         │\n│    ●    │\n│         │\n└─────────┘",
    2 => "┌─────────┐\n│ ●       │\n│         │\n│       ● │\n└─────────┘",
    3 => "┌─────────┐\n│ ●       │\n│    ●    │\n│       ● │\n└─────────┘",
    4 => "┌─────────┐\n│ ●     ● │\n│         │\n│ ●     ● │\n└─────────┘",
    5 => "┌─────────┐\n│ ●     ● │\n│    ●    │\n│ ●     ● │\n└─────────┘",
    6 => "┌─────────┐\n│ ●     ● │\n│ ●     ● │\n│ ●     ● │\n└─────────┘"
];

// Handle the bet submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_bet'])) {
    // Validate the bet amount
    if (isset($_POST['bet']) && is_numeric($_POST['bet'])) {
        $bet = (int)$_POST['bet'];
        
        // Check if bet is valid
        if ($bet <= 0) {
            $result = "ERROR: Bet must be positive";
        } elseif ($bet > $_SESSION['balance']) {
            $result = "ERROR: Insufficient balance";
        } else {
            // Process the bet
            $diceRoll = rand(1, 6); // Roll a 6-sided die
            $diceArt = $diceFaces[$diceRoll]; // Get corresponding dice art
            
            if ($diceRoll >= 4) { // Win condition (4, 5, or 6)
                $winAmount = $bet * 2;
                $_SESSION['balance'] += $winAmount;
                $result = "SUCCESS: Rolled $diceRoll. You won $winAmount packets!";
            } else { // Lose condition (1, 2, or 3)
                $_SESSION['balance'] -= $bet;
                $result = "FAILURE: Rolled $diceRoll. You lost $bet packets.";
            }
            
            // Check if balance reached zero after the bet
            if ($_SESSION['balance'] <= 0) {
                header("Location: broke.php");
                exit();
            }
        }
    } else {
        $result = "ERROR: Invalid bet amount";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Darknet Dice</title>
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
        border: 1px solid #33ff33;
        box-shadow: 0 0 30px rgba(51, 255, 51, 0.5),
                    inset 0 0 20px rgba(51, 255, 51, 0.3);
        background-color: rgba(10, 10, 10, 0.9);
        backdrop-filter: blur(3px);
        padding: 0;
        overflow: hidden;
    }

    /* ===== TERMINAL HEADER BAR ===== */
    .terminal-header {
        background: linear-gradient(to right, #0a3a0a, #0d0d0d);
        padding: 8px 15px;
        border-bottom: 1px solid #33ff33;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .terminal-title {
        font-weight: bold;
        text-shadow: 0 0 8px #33ff33;
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
        border: 1px solid #33ff33;
    }

    .terminal-btn.close { background-color: #ff3333; }
    .terminal-btn.minimize { background-color: #ffcc33; }
    .terminal-btn.maximize { background-color: #33cc33; }

    /* ===== TERMINAL CONTENT ===== */
    .terminal-content {
        padding: 25px;
        position: relative;
    }

    .terminal-prompt {
        color: #33ff33;
        margin-bottom: 20px;
    }

    .terminal-prompt::before {
        content: "> ";
        color: #33ff33;
    }

    /* ===== INPUT STYLING ===== */
    .terminal-input-group {
        display: flex;
        gap: 10px;
        margin: 25px 0;
        align-items: center;
    }

    .terminal-input {
        background-color: rgba(0, 0, 0, 0.5);
        color: #33ff33;
        border: 1px solid #33ff33;
        padding: 12px 15px;
        font-family: inherit;
        font-size: 1rem;
        flex-grow: 1;
        outline: none;
        box-shadow: inset 0 0 10px rgba(51, 255, 51, 0.2),
                    0 0 15px rgba(51, 255, 51, 0.1);
        transition: all 0.3s ease;
        caret-color: #33ff33;
    }

    .terminal-input:focus {
        border-color: #2eb82e;
        box-shadow: inset 0 0 15px rgba(46, 184, 46, 0.3),
                    0 0 20px rgba(46, 184, 46, 0.2);
    }

    /* Remove number input arrows */
    .terminal-input::-webkit-inner-spin-button,
    .terminal-input::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    .terminal-input {
        -moz-appearance: textfield;
    }

    /* ===== BUTTON STYLING ===== */
    .terminal-btn-submit {
        background-color: #0a3a0a;
        color: #33ff33;
        border: 1px solid #33ff33;
        padding: 12px 25px;
        font-family: inherit;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 1px;
        box-shadow: 0 0 15px rgba(51, 255, 51, 0.2);
        white-space: nowrap;
    }

    .terminal-btn-submit:hover {
        background-color: #33ff33;
        color: #000;
        box-shadow: 0 0 25px rgba(51, 255, 51, 0.5);
    }

    .terminal-btn-secondary {
        background-color: transparent;
        color: #33ff33;
        border: 1px solid #33ff33;
        padding: 10px 20px;
        margin-top: 15px;
        display: inline-block;
        transition: all 0.3s ease;
    }

    .terminal-btn-secondary:hover {
        background-color: rgba(51, 255, 51, 0.1);
        box-shadow: 0 0 15px rgba(51, 255, 51, 0.3);
    }

    /* ===== RESULT DISPLAY ===== */
    .terminal-result {
        margin: 20px 0;
        padding: 15px;
        border-left: 3px solid #33ff33;
        background-color: rgba(0, 30, 0, 0.3);
        animation: textShadow 1.5s infinite alternate;
    }

    @keyframes textShadow {
        from { text-shadow: 0 0 5px #33ff33; }
        to { text-shadow: 0 0 10px #33ff33, 0 0 20px #33ff33; }
    }

    /* ===== STATUS BAR ===== */
    .terminal-status {
        display: flex;
        justify-content: space-between;
        margin-top: 30px;
        padding-top: 10px;
        border-top: 1px dashed rgba(51, 255, 51, 0.5);
        font-size: 0.9rem;
        color: #2eb82e;
    }

    /* ===== SCROLLBAR ===== */
    ::-webkit-scrollbar {
        width: 8px;
    }
    ::-webkit-scrollbar-track {
        background: #0a0a0a;
    }
    ::-webkit-scrollbar-thumb {
        background: #33ff33;
        border-radius: 4px;
    }

    .dice-display {
        font-family: monospace;
        white-space: pre;
        margin: 20px 0;
        color: #33ff33;
        text-align: center;
        font-size: 1.2rem;
        line-height: 1.3;
        text-shadow: 0 0 5px #33ff33;
    }
  </style>
  <script>
    // Save scroll position before form submit
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        if(form) {
            form.addEventListener('submit', function() {
                sessionStorage.setItem('matrixScroll', window.scrollY);
            });
        }
        
        // Restore scroll position after page load
        window.addEventListener('load', function() {
            if (sessionStorage.getItem('matrixScroll')) {
                window.scrollTo(0, parseInt(sessionStorage.getItem('matrixScroll')));
                sessionStorage.removeItem('matrixScroll');
            }
        });
    });
  </script>
</head>
<body class="terminal-bg">

<!-- Matrix background canvas -->
<canvas id="matrixCanvas"></canvas>

<div class="terminal-container">
    <div class="terminal-header">
        <div class="terminal-title">DICE TERMINAL</div>
        <div class="terminal-controls">
            <div class="terminal-btn close"></div>
            <div class="terminal-btn minimize"></div>
            <div class="terminal-btn maximize"></div>
        </div>
    </div>
    
    <div class="terminal-content">
        <div class="terminal-prompt">Current Balance: <?php echo $_SESSION['balance']; ?> packets</div>

        <?php if ($result): ?>
            <?php if ($diceArt): ?>
                <div class="dice-display"><?= $diceArt ?></div>
            <?php endif; ?>
            <div class="terminal-result"><?= htmlspecialchars($result) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="terminal-input-group">
                <input type="number" class="terminal-input" name="bet" min="1" max="<?php echo $_SESSION['balance']; ?>" placeholder="Enter bet amount" required>
                <button type="submit" name="submit_bet" class="terminal-btn-submit">ROLL</button>
            </div>
        </form>

        <a href="index.php" class="terminal-btn-secondary">DISCONNECT</a>
        
        <div class="terminal-status">
            <span>STATUS: OPERATIONAL</span>
            <span>v1.0.0</span>
        </div>
    </div>
</div>


<!-- Add the Matrix JavaScript -->
<script>
  const state = {
    fps: 20,
    color: "#33ff33",
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
Darknet Dice 🎲
Terminal-Based Dice Game with a Cyberpunk Aesthetic


A terminal-style dice game designed with a Matrix-inspired interface. Built using PHP and CSS, Darknet Dice captures the essence of a darknet terminal while delivering a simple yet addictive dice-rolling experience.

🎮 Features
Terminal UI – Authentic terminal-style interface with matrix-like background

Dice Game Logic – Win on rolls 4-6, lose on 1-3

Balance Tracking – Persistent session-based balance

ASCII Art Dice – Visual representation of dice using ASCII art

Responsive Design – Plays well on various screen sizes

Cyberpunk Theme – Green-on-black color scheme with glowing, hacker-inspired styling

🧰 Requirements
PHP 7.0 or higher

A web server (Apache, Nginx, etc.)

Modern web browser

🚀 Installation
Clone or Download this repository:


Place the files in your web server's document root (e.g., /var/www/html/).

Ensure PHP is configured correctly on your server.

Open your browser and visit the page:

arduino
Kopiraj
Uredi
http://localhost/darknet-dice/
🕹️ How to Play
Enter your bet amount in the input field

Click "ROLL" to roll the dice

If you roll 4, 5, or 6 → you win 2× your bet

If you roll 1, 2, or 3 → you lose your bet

Your balance persists as long as the browser session is active

📏 Game Rules
Starting balance: 100 packets

Win condition: Roll 4, 5, or 6 (double your bet)

Lose condition: Roll 1, 2, or 3 (lose your bet)

Minimum bet: 1 packet

Maximum bet: Your current balance

🛠 Technical Details
Frontend: HTML5 + CSS3 with terminal-style visuals

Backend: PHP (game logic + session management)

Dynamic Elements: JavaScript matrix effect

Responsive: Optimized for desktop and mobile (limited)

🚧 Known Issues
Session balance is not stored server-side, only in browser session

No account system

Limited mobile optimization

📈 Future Improvements
User accounts with persistent balances

Leaderboard and statistics

Multiple game modes

Enhanced animations

Sound effects and audio feedback

🤝 Contributing
Contributions are welcome!
To contribute:

Fork the repository

Create your feature branch: git checkout -b feature-name

Commit your changes: git commit -am 'Add new feature'

Push to the branch: git push origin feature-name

Open a pull request

💻 License
MIT License (or specify your preferred license here)

Enjoy the game, runner.
The darknet never sleeps. 🕶️🟩

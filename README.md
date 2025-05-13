Darknet Dice - Terminal-Based Dice Game
Darknet Dice Screenshot



A terminal-style dice game with a cyberpunk aesthetic, built with PHP and styled with CSS to resemble a darknet terminal interface.

Features
Terminal UI: Authentic terminal interface with matrix-style background

Dice Game: Simple dice rolling game where you win on 4-6 and lose on 1-3

Balance Tracking: Persistent balance across sessions

ASCII Art Dice: Visual dice representation using ASCII art

Responsive Design: Works on various screen sizes

Cyberpunk Style: Green-on-black color scheme with glowing elements



Requirements
PHP 7.0 or higher

Web server (Apache, Nginx, etc.)

Modern web browser



Installation
Clone this repository or download the ZIP file

Place the files in your web server's document root

Ensure PHP is properly configured on your server

Visit the page in your browser



How to Play
Enter your bet amount in the input field

Click "ROLL" to roll the dice

If you roll 4, 5, or 6, you win double your bet

If you roll 1, 2, or 3, you lose your bet

Your balance persists until you close the session




Game Rules
Starting balance: 100 packets

Win condition: Roll 4, 5, or 6 (wins 2× bet)

Lose condition: Roll 1, 2, or 3 (loses bet)

Minimum bet: 1 packet

Maximum bet: Your current balance




Technical Details
Frontend: HTML5, CSS3 with terminal styling

Backend: PHP for game logic and session management

Dynamic Elements: JavaScript for the matrix background effect

Responsive: Works on desktop and mobile devices




Contributing
Contributions are welcome! Please fork the repository and submit a pull request with your improvements.

Known Issues
Session persistence relies on browser cookies



No server-side persistence of balances between sessions

Limited mobile optimization

Future Improvements
User accounts with persistent balances

Leaderboard system

Multiple game modes

Enhanced animations

Sound effects

Enjoy the game!

const games = [
  {
    id: 'word-guess',
    title: 'স্পেলিং চ্যালেঞ্জ',
    desc: 'উপর থেকে পড়া অক্ষর দিয়ে সঠিক বানানটি মেলাতে হবে।',
    image: './assets/word_guess_icon.png',
    init: initWordGuess
  },
  {
    id: 'emoji-guess',
    title: 'ইমোজি থেকে শব্দ',
    desc: 'ইমোজি দেখে সিনেমার নাম বা সঠিক শব্দটি অনুমান করতে হবে।',
    image: './assets/emoji_guess_icon.png',
    init: initEmojiGuess
  },
  {
    id: 'bubble-shooter',
    title: 'বাবল শুটার',
    desc: 'একই রঙের ৩টি বাবল মিলিয়ে ফাটাতে হবে।',
    image: './assets/bubble_shooter_icon.png',
    init: initBubbleShooter
  },
  {
    id: 'block-blast',
    title: 'ব্লক ব্লাস্ট',
    desc: 'গ্রিডে ব্লকগুলো মিলিয়ে লাইনগুলো পরিষ্কার করতে হবে।',
    image: './assets/block_blast_icon.png',
    init: initBlockBlast
  },
  {
    id: 'color-sort',
    title: 'কালার সর্ট পাজল',
    desc: 'জারগুলোতে একই রঙের তরল আলাদা করে মেলাতে হবে।',
    image: './assets/color_sort_icon.png',
    init: initColorSort
  }
];

// LocalStorage Manager
const StorageManager = {
  getScore: (gameId) => parseInt(localStorage.getItem(`${gameId}_score`)) || 0,
  getLevel: (gameId) => parseInt(localStorage.getItem(`${gameId}_level`)) || 1,
  saveScore: (gameId, score) => localStorage.setItem(`${gameId}_score`, score),
  saveLevel: (gameId, level) => localStorage.setItem(`${gameId}_level`, level)
};

// --- Animated Background Engine ---
class BackgroundEngine {
  constructor(canvasId) {
    this.canvas = document.getElementById(canvasId);
    if (!this.canvas) return;
    this.ctx = this.canvas.getContext('2d');
    this.particles = [];
    this.isRunning = false;
    this.resize = this.resize.bind(this);
    window.addEventListener('resize', this.resize);
    this.resize();
    
    this.emojis = ['😎', '🎬', '🧩', '🔤', '🧱', '🟦', '🟩', '🫧', '💧', '🧪', '✨', '🔥', '🏆'];
    this.colors = ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#14b8a6'];
    
    this.themes = ['emoji', 'bubble'];
    this.currentTheme = 'bubble';
    this.lastThemeChangeTime = 0;
  }
  
  resize() {
    this.canvas.width = window.innerWidth;
    this.canvas.height = window.innerHeight;
  }
  
  createParticle(theme) {
    let p = {
      x: Math.random() * this.canvas.width,
      y: -50,
      size: Math.random() * 30 + 10,
      speedY: Math.random() * 0.8 + 0.2, // Slower drop
      speedX: (Math.random() - 0.5) * 0.5,
      rotation: Math.random() * Math.PI * 2,
      rotationSpeed: (Math.random() - 0.5) * 0.02, // Slower rotation
      alpha: Math.random() * 0.4 + 0.2,
      type: theme
    };
    
    if (theme === 'emoji') {
      p.char = this.emojis[Math.floor(Math.random() * this.emojis.length)];
      p.size = Math.random() * 20 + 20;
    } else if (theme === 'block') {
      p.color = this.colors[Math.floor(Math.random() * this.colors.length)];
      p.size = Math.random() * 20 + 15;
    } else if (theme === 'bubble') {
      p.y = this.canvas.height + 50; 
      p.speedY = -(Math.random() * 0.8 + 0.2); // Slower rise
      p.color = this.colors[Math.floor(Math.random() * this.colors.length)];
      p.size = Math.random() * 30 + 10;
    } else if (theme === 'word') {
      p.text = this.words[Math.floor(Math.random() * this.words.length)];
      p.color = this.colors[Math.floor(Math.random() * this.colors.length)];
      p.size = Math.random() * 15 + 15;
    } else if (theme === 'orb') {
      p.color = this.colors[Math.floor(Math.random() * this.colors.length)];
      p.size = Math.random() * 150 + 50;
      p.alpha = Math.random() * 0.15 + 0.05;
      p.speedY = (Math.random() - 0.5) * 0.2; // Very slow drift
      p.y = Math.random() * this.canvas.height;
    }
    return p;
  }
  
  update() {
    if (!this.isRunning) return;
    
    let now = performance.now();
    if (now - this.lastThemeChangeTime > 5000) {
      this.currentTheme = this.themes[Math.floor(Math.random() * this.themes.length)];
      this.lastThemeChangeTime = now;
    }
    
    // Drop very few particles (max 12) at a very slow rate
    if (this.particles.length < 12 && Math.random() < 0.015) {
      this.particles.push(this.createParticle(this.currentTheme));
    }
    
    this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
    
    for (let i = 0; i < this.particles.length; i++) {
      let p = this.particles[i];
      p.y += p.speedY;
      p.x += p.speedX;
      p.rotation += p.rotationSpeed;
      
      this.ctx.save();
      this.ctx.translate(p.x, p.y);
      this.ctx.rotate(p.rotation);
      this.ctx.globalAlpha = p.alpha;
      
      if (p.type === 'emoji') {
        this.ctx.font = `${p.size}px Arial`;
        this.ctx.textAlign = 'center';
        this.ctx.textBaseline = 'middle';
        this.ctx.fillText(p.char, 0, 0);
      } else if (p.type === 'block') {
        this.ctx.fillStyle = p.color;
        this.ctx.fillRect(-p.size/2, -p.size/2, p.size, p.size);
        this.ctx.strokeStyle = 'rgba(255,255,255,0.5)';
        this.ctx.lineWidth = 2;
        this.ctx.strokeRect(-p.size/2, -p.size/2, p.size, p.size);
      } else if (p.type === 'bubble') {
        this.ctx.beginPath();
        this.ctx.arc(0, 0, p.size, 0, Math.PI * 2);
        this.ctx.fillStyle = p.color;
        this.ctx.globalAlpha = p.alpha * 0.4;
        this.ctx.fill();
        this.ctx.strokeStyle = 'rgba(255,255,255,0.8)';
        this.ctx.lineWidth = 1;
        this.ctx.stroke();
        this.ctx.beginPath();
        this.ctx.arc(-p.size/3, -p.size/3, p.size/4, 0, Math.PI * 2);
        this.ctx.fillStyle = 'rgba(255,255,255,0.8)';
        this.ctx.globalAlpha = p.alpha;
        this.ctx.fill();
      } else if (p.type === 'word') {
        this.ctx.font = `bold ${p.size}px Outfit, sans-serif`;
        this.ctx.fillStyle = p.color;
        this.ctx.textAlign = 'center';
        this.ctx.textBaseline = 'middle';
        this.ctx.fillText(p.text, 0, 0);
      } else if (p.type === 'orb') {
        let grad = this.ctx.createRadialGradient(0, 0, 0, 0, 0, p.size);
        grad.addColorStop(0, p.color);
        grad.addColorStop(1, 'transparent');
        this.ctx.fillStyle = grad;
        this.ctx.globalAlpha = p.alpha;
        this.ctx.beginPath();
        this.ctx.arc(0, 0, p.size, 0, Math.PI * 2);
        this.ctx.fill();
      }
      
      this.ctx.restore();
      
      if ((p.speedY > 0 && p.y > this.canvas.height + 150) || 
          (p.speedY < 0 && p.y < -150)) {
        this.particles.splice(i, 1);
        i--;
      }
    }
    requestAnimationFrame(() => this.update());
  }
  
  start() {
    if (this.isRunning) return;
    this.isRunning = true;
    this.update();
  }
  
  stop() {
    this.isRunning = false;
    this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
    this.particles = [];
  }
}

const bgEngine = new BackgroundEngine('bg-canvas');

// UI Manager
const app = document.getElementById('app');

function renderHub() {
  if(bgEngine) bgEngine.start();
  app.innerHTML = `
    <h1>Game Hub</h1>
    <p style="text-align: center; color: #94a3b8; font-size: 1.2rem; margin-top: -1.5rem; margin-bottom: 2.5rem; font-weight: 500; letter-spacing: 0.5px;">জনপ্রিয় ৫টি গেম এক সাথে</p>
    <div class="hub-grid">
      ${games.map(g => `
        <div class="game-card" data-id="${g.id}">
          <div class="score-badge" style="position: absolute; top: 0; right: 0; background: rgba(16, 185, 129, 0.4); color: #10b981; border-bottom: 1px solid rgba(16,185,129,0.3); border-left: 1px solid rgba(16,185,129,0.3); padding: 4px 12px; border-radius: 0 16px 0 12px; font-size: 0.75rem; font-weight: bold; z-index: 10;">Level ${StorageManager.getLevel(g.id)}</div>
          <div class="game-image-container">
            <img src="${g.image}" alt="${g.title}" class="game-thumbnail">
          </div>
          <h2>${g.title}</h2>
          <div class="game-desc">${g.desc}</div>
          <button class="play-btn">খেলুন</button>
        </div>
      `).join('')}
    </div>
  `;

  document.querySelectorAll('.game-card').forEach(card => {
    card.addEventListener('click', () => {
      const gameId = card.getAttribute('data-id');
      const game = games.find(g => g.id === gameId);
      openGame(game);
    });
  });
}

function openGame(game) {
  if(bgEngine) bgEngine.stop();
  app.innerHTML = `
    <div class="game-container" id="game-container" style="display: block;">
      <div class="game-header">
        <button class="back-btn" id="back-btn">← ফিরে যান</button>
        <h2>${game.title}</h2>
        <div style="display: flex; gap: 10px; align-items: center;">
          <button id="reset-game-btn" title="রিস্টার্ট করুন" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: white; padding: 5px 8px; border-radius: 6px; cursor: pointer; font-size: 0.8rem; transition: 0.2s;">🔄</button>
          <div class="level-indicator" id="level-indicator">Level ${StorageManager.getLevel(game.id)}</div>
        </div>
      </div>
      <div class="game-content" id="game-content">
        <!-- Game specific DOM goes here -->
      </div>
    </div>
  `;

  document.getElementById('back-btn').addEventListener('click', renderHub);
  
  const resetBtn = document.getElementById('reset-game-btn');
  resetBtn.onmouseover = () => resetBtn.style.background = 'rgba(239, 68, 68, 0.5)';
  resetBtn.onmouseout = () => resetBtn.style.background = 'rgba(255, 255, 255, 0.1)';
  resetBtn.addEventListener('click', () => {
    if(confirm('আপনি কি এই গেমটি লেভেল ১ থেকে আবার শুরু করতে চান?')) {
      StorageManager.saveLevel(game.id, 1);
      StorageManager.saveScore(game.id, 0);
      openGame(game);
    }
  });
  
  const contentDiv = document.getElementById('game-content');
  
  // Show Let's Go screen first
  contentDiv.innerHTML = `
    <div style="display:flex; flex-direction:column; align-items:center; justify-content:center; height: 100%; min-height: 400px; width: 100%; animation: fadeIn 0.4s ease forwards;">
      <img src="${game.image}" style="width: 120px; height: 120px; border-radius: 24px; margin-bottom: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.5); border: 2px solid rgba(255,255,255,0.2);">
      <h2 style="font-size: 2rem; margin-bottom: 10px; text-shadow: 0 2px 4px rgba(0,0,0,0.5);">${game.title}</h2>
      <p style="text-align: center; color: #cbd5e1; max-width: 300px; margin-bottom: 30px; font-size: 1rem;">${game.desc}</p>
      <button id="lets-go-btn" class="play-btn" style="font-size: 1.2rem; padding: 0.8rem 2.5rem; border-radius: 25px; box-shadow: 0 0 20px rgba(59, 130, 246, 0.5);">Let's Go!</button>
    </div>
  `;
  
  document.getElementById('lets-go-btn').addEventListener('click', () => {
    contentDiv.innerHTML = '';
    // Initialize the specific game logic
    game.init(
      contentDiv, 
      StorageManager.getLevel(game.id), 
      StorageManager.getScore(game.id),
      (newLevel, newScore) => {
        StorageManager.saveLevel(game.id, newLevel);
        if(newScore !== undefined && newScore !== null) StorageManager.saveScore(game.id, newScore);
        document.getElementById('level-indicator').innerText = `Level ${newLevel}`;
      }
    );
  });
}

// Initial render
renderHub();

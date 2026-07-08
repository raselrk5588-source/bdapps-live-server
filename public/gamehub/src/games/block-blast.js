function initBlockBlast(container, initialLevel, initialScore, saveProgress) {
  let currentLevel = initialLevel;
  let score = initialScore || 0;
  container.innerHTML = `
    <div style="display: flex; justify-content: space-between; width: 100%; max-width: 300px; margin-bottom: 15px; background: rgba(255,255,255,0.05); padding: 10px 20px; border-radius: 20px; border: 1px solid rgba(255,255,255,0.1); box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
      <span style="font-weight: bold; color: #38bdf8; text-shadow: 0 0 5px rgba(56,189,248,0.5); font-size: 1.1rem;">Score: <span id="bb-score" style="color: white;">${score}</span></span>
      <span style="font-weight: bold; color: #f59e0b; text-shadow: 0 0 5px rgba(245,158,11,0.5); font-size: 1.1rem;">Target: <span id="bb-target" style="color: white;">0</span></span>
    </div>
    <div style="position: relative; width: 300px; height: 500px; border-radius: 16px; overflow: hidden; background: rgba(30, 41, 59, 0.9); border: 1px solid rgba(255,255,255,0.2); box-shadow: 0 15px 35px rgba(0,0,0,0.3), inset 0 0 20px rgba(255,255,255,0.05);">
      <canvas id="bb-canvas" width="300" height="500" style="position: absolute; top:0; left:0; touch-action: none;"></canvas>
    </div>
    <div id="bb-msg" style="margin-top: 15px; font-weight: bold; height: 20px; color: #10b981; text-align: center; font-size: 1.1rem; text-shadow: 0 0 10px rgba(16, 185, 129, 0.5);"></div>
    <div style="margin-top: 15px; font-size: 0.85rem; color: #cbd5e1; text-align: center; max-width: 300px; background: rgba(0,0,0,0.2); padding: 10px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
      💡 টিপস: ডানে-বামে ড্র্যাগ করে ব্লক ঘোরান, ট্যাপ করে ঘোরান, আর দ্রুত নিচে ফেলতে সোয়াইপ ডাউন করুন!
    </div>
  `;

  const canvas = document.getElementById('bb-canvas');
  const ctx = canvas.getContext('2d');
  const msgEl = document.getElementById('bb-msg');
  const scoreEl = document.getElementById('bb-score');
  const targetEl = document.getElementById('bb-target');

  const COLS = 12;
  const ROWS = 20;
  const BLOCK_SIZE = 25; // 300 / 12 = 25, 500 / 20 = 25
  
  let grid = createMatrix(COLS, ROWS);
  
  const SHAPES = [
    [[0,1,0,0], [0,1,0,0], [0,1,0,0], [0,1,0,0]], // I
    [[0,2,0], [0,2,0], [0,2,2]], // L
    [[0,3,0], [0,3,0], [3,3,0]], // J
    [[4,4], [4,4]], // O
    [[0,0,0], [5,5,5], [0,5,0]], // T
    [[0,6,6], [6,6,0], [0,0,0]], // S
    [[7,7,0], [0,7,7], [0,0,0]], // Z
    [[8,0,8], [8,8,8], [0,0,0]], // U (Advanced)
    [[0,9,0], [9,9,9], [0,9,0]], // Cross (Advanced)
    [[10,10,10], [10,10,10], [10,10,10]], // Big Block (Advanced)
  ];
  
  const COLORS = [
    null,
    '#0ea5e9', // 1 I Cyan
    '#f97316', // 2 L Orange
    '#3b82f6', // 3 J Blue
    '#eab308', // 4 O Yellow
    '#a855f7', // 5 T Purple
    '#22c55e', // 6 S Green
    '#ef4444', // 7 Z Red
    '#ec4899', // 8 U Pink
    '#14b8a6', // 9 Cross Teal
    '#64748b', // 10 Big Slate
  ];

  let dropCounter = 0;
  let dropInterval = 1000;
  let lastTime = 0;
  let loopId = null;
  let isGameOver = false;
  let targetScore = 0;

  const player = {
    pos: {x: 0, y: 0},
    matrix: null,
  };

  function createMatrix(w, h) {
    const matrix = [];
    while (h--) {
      matrix.push(new Array(w).fill(0));
    }
    return matrix;
  }

  function collide(grid, player) {
    const m = player.matrix;
    const o = player.pos;
    for (let y = 0; y < m.length; ++y) {
      for (let x = 0; x < m[y].length; ++x) {
        if (m[y][x] !== 0 &&
           (grid[y + o.y] && grid[y + o.y][x + o.x]) !== 0) {
          return true;
        }
      }
    }
    return false;
  }

  function merge(grid, player) {
    player.matrix.forEach((row, y) => {
      row.forEach((value, x) => {
        if (value !== 0) {
          grid[y + player.pos.y][x + player.pos.x] = value;
        }
      });
    });
  }

  function rotate(matrix, dir) {
    for (let y = 0; y < matrix.length; ++y) {
      for (let x = 0; x < y; ++x) {
        [matrix[x][y], matrix[y][x]] = [matrix[y][x], matrix[x][y]];
      }
    }
    if (dir > 0) {
      matrix.forEach(row => row.reverse());
    } else {
      matrix.reverse();
    }
  }

  function playerRotate(dir) {
    const pos = player.pos.x;
    let offset = 1;
    rotate(player.matrix, dir);
    while (collide(grid, player)) {
      player.pos.x += offset;
      offset = -(offset + (offset > 0 ? 1 : -1));
      if (offset > player.matrix[0].length) {
        rotate(player.matrix, -dir); // Undo
        player.pos.x = pos;
        return;
      }
    }
  }

  function playerDrop() {
    player.pos.y++;
    if (collide(grid, player)) {
      player.pos.y--;
      merge(grid, player);
      playerReset();
      clearLines();
    }
    dropCounter = 0;
  }

  function playerMove(dir) {
    player.pos.x += dir;
    if (collide(grid, player)) {
      player.pos.x -= dir;
    }
  }

  function playerReset() {
    // Determine max shape index based on level (introduce harder shapes later)
    let maxShape = Math.min(SHAPES.length, 7 + Math.floor((currentLevel - 1) / 25));
    const idx = Math.floor(Math.random() * maxShape);
    
    // Deep copy matrix
    player.matrix = JSON.parse(JSON.stringify(SHAPES[idx]));
    player.pos.y = 0;
    player.pos.x = Math.floor(COLS / 2) - Math.floor(player.matrix[0].length / 2);
    
    if (collide(grid, player)) {
      gameOver();
    }
  }

  function clearLines() {
    let linesCleared = 0;
    outer: for (let y = grid.length - 1; y >= 0; --y) {
      for (let x = 0; x < grid[y].length; ++x) {
        if (grid[y][x] === 0) {
          continue outer;
        }
      }
      
      const row = grid.splice(y, 1)[0].fill(0);
      grid.unshift(row);
      ++y;
      linesCleared++;
    }
    
    if (linesCleared > 0) {
      // Score multiplier for multiple lines
      let points = (linesCleared === 1) ? 100 : (linesCleared === 2) ? 300 : (linesCleared === 3) ? 500 : 800;
      score += points;
      scoreEl.innerText = score;
      
      if (score >= targetScore) {
        levelComplete();
      }
    }
  }

  function drawBlock(x, y, size, color) {
    ctx.fillStyle = color;
    ctx.fillRect(x, y, size, size);
    
    // Soft 3D Bevel Highlight
    ctx.fillStyle = 'rgba(255,255,255,0.4)';
    ctx.beginPath();
    ctx.moveTo(x, y);
    ctx.lineTo(x + size, y);
    ctx.lineTo(x + size - 4, y + 4);
    ctx.lineTo(x + 4, y + 4);
    ctx.fill();
    
    ctx.beginPath();
    ctx.moveTo(x, y);
    ctx.lineTo(x, y + size);
    ctx.lineTo(x + 4, y + size - 4);
    ctx.lineTo(x + 4, y + 4);
    ctx.fill();
    
    // Soft 3D Bevel Shadow
    ctx.fillStyle = 'rgba(0,0,0,0.4)';
    ctx.beginPath();
    ctx.moveTo(x, y + size);
    ctx.lineTo(x + size, y + size);
    ctx.lineTo(x + size - 4, y + size - 4);
    ctx.lineTo(x + 4, y + size - 4);
    ctx.fill();
    
    ctx.beginPath();
    ctx.moveTo(x + size, y);
    ctx.lineTo(x + size, y + size);
    ctx.lineTo(x + size - 4, y + size - 4);
    ctx.lineTo(x + size - 4, y + 4);
    ctx.fill();
  }

  function drawMatrix(matrix, offset) {
    matrix.forEach((row, y) => {
      row.forEach((value, x) => {
        if (value !== 0) {
          drawBlock(
            (x + offset.x) * BLOCK_SIZE,
            (y + offset.y) * BLOCK_SIZE,
            BLOCK_SIZE,
            COLORS[value]
          );
        }
      });
    });
  }

  function draw() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    
    // Draw grid background lines (subtle)
    ctx.strokeStyle = 'rgba(255,255,255,0.05)';
    ctx.lineWidth = 1;
    for(let c=0; c<=COLS; c++) {
      ctx.beginPath(); ctx.moveTo(c*BLOCK_SIZE, 0); ctx.lineTo(c*BLOCK_SIZE, canvas.height); ctx.stroke();
    }
    for(let r=0; r<=ROWS; r++) {
      ctx.beginPath(); ctx.moveTo(0, r*BLOCK_SIZE); ctx.lineTo(canvas.width, r*BLOCK_SIZE); ctx.stroke();
    }

    drawMatrix(grid, {x: 0, y: 0});
    if (player.matrix) {
      drawMatrix(player.matrix, player.pos);
    }
  }

  function update(time = 0) {
    if (!document.body.contains(container)) return;
    if (isGameOver) return;
    const deltaTime = time - lastTime;
    lastTime = time;

    dropCounter += deltaTime;
    if (dropCounter > dropInterval) {
      playerDrop();
    }

    draw();
    loopId = requestAnimationFrame(update);
  }

  // --- Input Handling ---
  let touchStartX = 0;
  let touchStartY = 0;
  let touchCurrentX = 0;
  let touchCurrentY = 0;
  let isDragging = false;

  const handleTouchStart = (x, y) => {
    if (isGameOver) return;
    touchStartX = x;
    touchStartY = y;
    touchCurrentX = x;
    touchCurrentY = y;
    isDragging = true;
  };

  const handleTouchMove = (x, y) => {
    if (!isDragging || isGameOver) return;
    touchCurrentX = x;
    touchCurrentY = y;
    
    let dx = touchCurrentX - touchStartX;
    // 25 pixels of drag = 1 block movement
    let cols = Math.floor(dx / BLOCK_SIZE);
    if (Math.abs(cols) > 0) {
      playerMove(cols > 0 ? 1 : -1);
      touchStartX = touchCurrentX; 
    }
  };

  const handleTouchEnd = () => {
    if (!isDragging || isGameOver) return;
    isDragging = false;
    
    let dx = Math.abs(touchCurrentX - touchStartX);
    let dy = touchCurrentY - touchStartY;
    
    if (dx < 10 && Math.abs(dy) < 10) {
      // Tap detected -> Rotate only if tapped on the block
      const rect = canvas.getBoundingClientRect();
      const scaleX = canvas.width / rect.width;
      const scaleY = canvas.height / rect.height;
      const canvasX = (touchStartX - rect.left) * scaleX;
      const canvasY = (touchStartY - rect.top) * scaleY;
      
      const blockPixelX = player.pos.x * BLOCK_SIZE;
      const blockPixelY = player.pos.y * BLOCK_SIZE;
      const blockPixelWidth = player.matrix[0].length * BLOCK_SIZE;
      const blockPixelHeight = player.matrix.length * BLOCK_SIZE;
      
      const PADDING = 15; // Extra padding for easier tapping
      
      if (canvasX >= blockPixelX - PADDING && canvasX <= blockPixelX + blockPixelWidth + PADDING &&
          canvasY >= blockPixelY - PADDING && canvasY <= blockPixelY + blockPixelHeight + PADDING) {
        playerRotate(1);
      } else {
        // Tapped ELSEWHERE on the grid -> Move to that column and hard drop!
        const targetCol = Math.floor(canvasX / BLOCK_SIZE);
        const blockCenterCol = player.pos.x + Math.floor(player.matrix[0].length / 2);
        
        let diff = targetCol - blockCenterCol;
        if (diff !== 0) {
            let dir = diff > 0 ? 1 : -1;
            for (let i = 0; i < Math.abs(diff); i++) {
                let oldX = player.pos.x;
                playerMove(dir);
                if (player.pos.x === oldX) break; // Blocked by wall or other blocks
            }
        }
        
        // Hard Drop
        while (!collide(grid, player)) {
          player.pos.y++;
        }
        player.pos.y--;
        merge(grid, player);
        playerReset();
        clearLines();
        dropCounter = 0;
      }
    } else if (dy > 40 && dy > dx) {
      // Swipe down detected -> Hard Drop
      while (!collide(grid, player)) {
        player.pos.y++;
      }
      player.pos.y--;
      merge(grid, player);
      playerReset();
      clearLines();
      dropCounter = 0;
    }
  };

  // Touch Events
  canvas.addEventListener('touchstart', (e) => {
    e.preventDefault();
    handleTouchStart(e.touches[0].clientX, e.touches[0].clientY);
  }, {passive: false});
  canvas.addEventListener('touchmove', (e) => {
    e.preventDefault();
    handleTouchMove(e.touches[0].clientX, e.touches[0].clientY);
  }, {passive: false});
  canvas.addEventListener('touchend', (e) => {
    e.preventDefault();
    handleTouchEnd();
  }, {passive: false});

  // Mouse Events (for desktop testing)
  canvas.addEventListener('mousedown', (e) => handleTouchStart(e.clientX, e.clientY));
  canvas.addEventListener('mousemove', (e) => {
    if(e.buttons === 1) handleTouchMove(e.clientX, e.clientY);
  });
  canvas.addEventListener('mouseup', handleTouchEnd);
  canvas.addEventListener('mouseleave', () => { isDragging = false; });

  // Keyboard controls for desktop
  const handleKeyDown = (e) => {
    if (!document.body.contains(container)) {
      document.removeEventListener('keydown', handleKeyDown);
      return;
    }
    if (isGameOver) return;
    if (e.key === 'ArrowLeft') playerMove(-1);
    else if (e.key === 'ArrowRight') playerMove(1);
    else if (e.key === 'ArrowDown') playerDrop();
    else if (e.key === 'ArrowUp' || e.key === ' ') playerRotate(1);
  };
  document.addEventListener('keydown', handleKeyDown);

  function initLevel() {
    if (currentLevel > 100) {
      msgEl.innerText = "অভিনন্দন! আপনি ১০০ লেভেল শেষ করেছেন!";
      isGameOver = true;
      return;
    }
    
    // Difficulty curve (Faster)
    dropInterval = 600 - (currentLevel * 12); 
    if (dropInterval < 60) dropInterval = 60;
    
    targetScore = score + 500 + (currentLevel * 200);
    targetEl.innerText = targetScore;
    msgEl.innerText = '';
    
    grid = createMatrix(COLS, ROWS);
    isGameOver = false;
    
    playerReset();
    lastTime = performance.now();
    
    if(loopId) cancelAnimationFrame(loopId);
    loopId = requestAnimationFrame(update);
  }

  function levelComplete() {
    msgEl.style.color = "var(--success)";
    msgEl.innerText = `লেভেল ${currentLevel} সম্পন্ন!`;
    isGameOver = true;
    setTimeout(() => {
      currentLevel++;
      saveProgress(currentLevel, score);
      initLevel();
    }, 1500);
  }

  function gameOver() {
    msgEl.style.color = "var(--danger)";
    msgEl.innerText = "গেম ওভার! জায়গা নেই।";
    isGameOver = true;
    setTimeout(() => {
      score = initialScore || 0;
      initLevel();
    }, 2000);
  }

  initLevel();

  // Cleanup hook
  const observer = new MutationObserver(() => {
    if (!document.body.contains(container) || container.innerHTML === '') {
      if(loopId) cancelAnimationFrame(loopId);
      document.removeEventListener('keydown', handleKeyDown);
      observer.disconnect();
    }
  });
  observer.observe(container, { childList: true, subtree: true });
}
